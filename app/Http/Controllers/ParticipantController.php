<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
    // Dashboard view with statistics
    public function dashboard()
    {
        $totalRegistered = Participant::where('status', 'approved')->count();
        $remainingSlots = 5000 - $totalRegistered;
        // Check if database has status column, if not, fallback or ensure migration ran.
        // Assuming migration ran.
        $withVoterId = Participant::where('status', 'approved')->where('voter_id', true)->count();
        $withoutVoterId = Participant::where('status', 'approved')->where('voter_id', false)->count();

        return view('dashboard', compact('totalRegistered', 'remainingSlots', 'withVoterId', 'withoutVoterId'));
    }

    // Get approved participants (with search/filter)
    public function index(Request $request)
    {
        $query = Participant::where('status', 'approved');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('token', 'LIKE', "%{$search}%")
                    ->orWhere('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%");
            });
        }

        // Filter by voter ID
        if ($request->has('voter_filter') && $request->voter_filter != '') {
            $query->where('voter_id', $request->voter_filter);
        }

        $participants = $query->orderBy('created_at', 'desc')->paginate(20);

        if ($request->ajax()) {
            return view('participants.partials.table', compact('participants'))->render();
        }

        return view('participants.index', compact('participants'));
    }

    // Get pending participants
    public function pending(Request $request)
    {
        $query = Participant::where('status', 'pending');

        // Search Filter (Name, Mobile, Token)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('token', 'LIKE', "%{$search}%")
                    ->orWhere('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile_number', 'LIKE', "%{$search}%");
            });
        }

        // Voter ID Filter
        if ($request->has('voter_filter') && $request->voter_filter != '') {
            $query->where('voter_id', $request->voter_filter);
        }

        $pendingParticipants = $query->orderBy('created_at', 'asc')->paginate(20);
        $totalRegistered = Participant::where('status', 'approved')->count();

        return view('participants.pending', compact('pendingParticipants', 'totalRegistered'));
    }

    // Approve participant
    public function approve(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);

        // 1. Check limit
        if (Participant::where('status', 'approved')->count() >= 5000) {
            return redirect()->back()->with('error', 'Registration limit reached (5000). Cannot approve more users.');
        }

        // 2. Validate Token
        $validator = Validator::make($request->all(), [
            'token' => [
                'required',
                'string',
                'max:4', // Up to 4 digits
                'regex:/^[0-9]+$/', // Numeric
                'unique:participants,token'
            ]
        ]);

        $validator->after(function ($validator) use ($request) {
            $tokenInt = intval($request->token);
            if ($tokenInt < 1 || $tokenInt > 5000) {
                $validator->errors()->add('token', 'Token must be between 1 and 5000.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 3. Update Status
        $participant->update([
            'token' => $request->token,
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', 'Participant approved successfully with Token: ' . $request->token);
    }

    // Register new participant (Admin)
    public function store(Request $request)
    {
        // Check registration limit
        $totalRegistered = Participant::where('status', 'approved')->count();
        if ($totalRegistered >= 5000) {
            return response()->json([
                'success' => false,
                'message' => 'Registration Closed! Maximum limit of 5000 participants reached.'
            ], 400);
        }

        // Validate basic formats first
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|max:75',
            'mobile_number' => 'required|digits:10',
            'token' => 'required|string|max:4|regex:/^[0-9]+$/',
            'voter_id' => 'boolean',
            'voter_member_count' => 'nullable|integer|min:0',
            'epic_voter_id_no' => 'nullable|string|size:10',
            'adharcard_no' => 'nullable|digits:12',
            'permanent_address' => 'required|string'
        ], [
            'mobile_number.digits' => 'Mobile number must be exactly 10 digits.',
            'epic_voter_id_no.size' => 'Voter ID must be exactly 10 characters.',
            'adharcard_no.digits' => 'Adhar Card number must be exactly 12 digits.',
            'age.max' => 'Invalid age — maximum allowed age is 75.',
            'token.max' => 'Token must not exceed 4 digits.',
            'token.regex' => 'Token must be numeric.',
        ]);

        // Custom validation hooks
        $validator->after(function ($validator) use ($request) {
            // Check token range
            $tokenInt = intval($request->token);
            if ($tokenInt < 1 || $tokenInt > 5000) {
                $validator->errors()->add('token', 'Token must be between 1 and 5000.');
            }

            // Check for Mobile Number (Active vs Trash)
            $existingMobile = Participant::withTrashed()->where('mobile_number', $request->mobile_number)->first();
            if ($existingMobile) {
                if (!$existingMobile->trashed()) {
                    $validator->errors()->add('mobile_number', 'User already registered');
                }
            }

            // Check for Aadhaar Uniqueness (if provided)
            if ($request->adharcard_no) {
                $existingAdhar = Participant::withTrashed()->where('adharcard_no', $request->adharcard_no)->first();
                if ($existingAdhar) {
                    if (!$existingAdhar->trashed()) {
                        $validator->errors()->add('adharcard_no', 'User already registered');
                    }
                }
            }

            // Check for Token Uniqueness
            $existingTokenOwner = Participant::withTrashed()->where('token', $request->token)->first();
            if ($existingTokenOwner) {
                // Check if it's the SAME user (same mobile) we are updating/restoring
                $isSameUser = $existingMobile && $existingMobile->id === $existingTokenOwner->id;

                if (!$isSameUser) {
                    $validator->errors()->add('token', 'This token already exists.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle Registration (Restore or Create)
        try {
            $existingParticipant = Participant::withTrashed()->where('mobile_number', $request->mobile_number)->first();

            if ($existingParticipant && $existingParticipant->trashed()) {
                // Restore and Update
                $existingParticipant->restore();
                $existingParticipant->update([
                    'full_name' => $request->full_name,
                    'age' => $request->age,
                    'voter_id' => $request->has('voter_id') ? true : false,
                    'voter_member_count' => $request->voter_member_count ?? 0,
                    'epic_voter_id_no' => $request->epic_voter_id_no,
                    'adharcard_no' => $request->adharcard_no,
                    'permanent_address' => $request->permanent_address,
                    'token' => $request->token,
                    'status' => 'approved'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Participant re-registered successfully!',
                    'participant' => $existingParticipant->fresh()
                ]);
            }

            // Create New
            $participant = Participant::create([
                'full_name' => $request->full_name,
                'age' => $request->age,
                'mobile_number' => $request->mobile_number,
                'voter_id' => $request->has('voter_id') ? true : false,
                'voter_member_count' => $request->voter_member_count ?? 0,
                'epic_voter_id_no' => $request->epic_voter_id_no,
                'adharcard_no' => $request->adharcard_no,
                'permanent_address' => $request->permanent_address,
                'token' => $request->token,
                'status' => 'approved'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Participant registered successfully!',
                'participant' => $participant
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle race condition for unique token/mobile
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token or Mobile already registered.'
                ], 422);
            }
            throw $e;
        }
    }
    public function update(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|max:75',
            'mobile_number' => 'required|digits:10',
            // 'token' => 'required|string|max:4|regex:/^[0-9]+$/', // Token editing might be restricted or careful
            'voter_id' => 'boolean',
            'voter_member_count' => 'nullable|integer|min:0',
            'epic_voter_id_no' => 'nullable|string|size:10',
            'adharcard_no' => 'nullable|digits:12',
            'permanent_address' => 'required|string'
        ]);

        $validator->after(function ($validator) use ($request, $participant) {
            // Check for Mobile Number Uniqueness (excluding current user)
            $existingMobile = Participant::withTrashed()->where('mobile_number', $request->mobile_number)->where('id', '!=', $participant->id)->first();
            if ($existingMobile) {
                $validator->errors()->add('mobile_number', 'Mobile number already registered.');
            }

            // Check for Aadhaar Uniqueness (excluding current user)
            if ($request->adharcard_no) {
                $existingAdhar = Participant::withTrashed()->where('adharcard_no', $request->adharcard_no)->where('id', '!=', $participant->id)->first();
                if ($existingAdhar) {
                    $validator->errors()->add('adharcard_no', 'Adhar number already registered.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $participant->update([
            'full_name' => $request->full_name,
            'age' => $request->age,
            'mobile_number' => $request->mobile_number,
            'voter_id' => $request->has('voter_id') ? true : false,
            'voter_member_count' => $request->voter_member_count ?? 0,
            'epic_voter_id_no' => $request->epic_voter_id_no,
            'adharcard_no' => $request->adharcard_no,
            'permanent_address' => $request->permanent_address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Participant updated successfully!',
            'participant' => $participant
        ]);
    }

    // Soft delete participant
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Admin can delete participants.'
            ], 403);
        }

        $participant = Participant::findOrFail($id);
        $participant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Participant moved to trash successfully!'
        ]);
    }

    // Get deleted participants
    public function trashed()
    {
        $trashedParticipants = Participant::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(20);
        return view('participants.trashed', compact('trashedParticipants'));
    }

    // Restore deleted participant
    public function restore($id)
    {
        $participant = Participant::onlyTrashed()->findOrFail($id);
        $participant->restore();
        return redirect()->back()->with('success', 'Participant restored successfully!');
    }

    // Permanently delete participant
    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized. Only Admin can delete participants.');
        }

        $participant = Participant::onlyTrashed()->findOrFail($id);
        $participant->forceDelete();

        return redirect()->back()->with('success', 'Participant permanently deleted!');
    }

    public function checkToken(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return response()->json(['exists' => false]);
        }

        // Check format
        if (!preg_match('/^[0-9]{1,4}$/', $token)) {
            return response()->json(['exists' => false, 'error' => 'Token must be numeric and up to 4 digits.']);
        }
        $val = intval($token);
        if ($val < 1 || $val > 5000) {
            return response()->json(['exists' => false, 'error' => 'Token must be between 1 and 5000.']);
        }

        $exists = Participant::withTrashed()->where('token', $token)->exists();
        if ($exists) {
            return response()->json(['exists' => true, 'error' => 'This token already exists.']);
        }

        return response()->json(['exists' => false]);
    }

    public function checkMobile(Request $request)
    {
        $mobile = $request->query('mobile');
        if (!$mobile) {
            return response()->json(['exists' => false]);
        }

        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            return response()->json(['exists' => false, 'error' => 'Mobile number must be 10 digits.']);
        }

        // Check if exists (including trashed)
        $exists = Participant::withTrashed()->where('mobile_number', $mobile)->exists();
        if ($exists) {
            return response()->json(['exists' => true, 'error' => 'Mobile number is already registered.']);
        }

        return response()->json(['exists' => false]);
    }

    // Export to Excel
    public function export()
    {
        $participants = Participant::where('status', 'approved')->orderBy('created_at', 'desc')->get();

        $filename = 'participants_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($participants) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper Excel display of UTF-8 characters
            fputs($file, "\xEF\xBB\xBF");

            // Add CSV headers
            fputcsv($file, [
                'Token',
                'Full Name',
                'Age',
                'Mobile Number',
                'Adhar Card No',
                'Voter ID',
                'Epic Voter ID No',
                'Voter Member Count',
                'Permanent Address',
                'Registered At'
            ]);

            // Add data rows
            foreach ($participants as $participant) {
                fputcsv($file, [
                    $participant->token,
                    $participant->full_name,
                    $participant->age,
                    $participant->mobile_number . "\t", // Force text format
                    $participant->adharcard_no . "\t", // Force text format
                    $participant->voter_id ? 'Yes' : 'No',
                    $participant->epic_voter_id_no,
                    $participant->voter_member_count,
                    $participant->permanent_address,
                    $participant->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export Pending to Excel
    public function exportPending()
    {
        $participants = Participant::where('status', 'pending')->orderBy('created_at', 'asc')->get();

        $filename = 'pending_participants_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($participants) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper Excel display of UTF-8 characters
            fputs($file, "\xEF\xBB\xBF");

            // Add CSV headers
            fputcsv($file, [
                'Full Name',
                'Age',
                'Mobile Number',
                'Adhar Card No',
                'Voter ID',
                'Epic Voter ID No',
                'Voter Member Count',
                'Permanent Address',
                'Applied At'
            ]);

            // Add data rows
            foreach ($participants as $participant) {
                fputcsv($file, [
                    $participant->full_name,
                    $participant->age,
                    $participant->mobile_number . "\t", // Force text format
                    $participant->adharcard_no . "\t", // Force text format
                    $participant->voter_id ? 'Yes' : 'No',
                    $participant->epic_voter_id_no,
                    $participant->voter_member_count,
                    $participant->permanent_address,
                    $participant->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

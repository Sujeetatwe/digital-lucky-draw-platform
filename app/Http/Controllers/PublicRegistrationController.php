<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Participant;

class PublicRegistrationController extends Controller
{
    public function create()
    {
        if (!session()->has('locale')) {
            app()->setLocale('mr');
        }
        return view('public.register');
    }

    public function store(Request $request)
    {
        // 1. Validate Input
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:75',
            'mobile_number' => 'required|regex:/^[0-9]{10}$/',
            'voter_id' => 'boolean',
            'voter_member_count' => 'nullable|integer|min:0',
            'epic_voter_id_no' => 'nullable|string|size:10',
            'adharcard_no' => 'nullable|regex:/^[0-9]{12}$/',
            'permanent_address' => 'required|string',
        ], [
            'age.max' => 'Invalid age — maximum allowed age is 75.',
            'epic_voter_id_no.size' => 'Voter ID must be exactly 10 characters.',
            'adharcard_no.regex' => 'Adhar Card number must be exactly 12 digits.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Check Logic (Mobile Existence)
        $existing = Participant::withTrashed()->where('mobile_number', $request->mobile_number)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'User already registered' // Specific requirement string
            ], 422);
        }

        // Check Logic (Aadhaar Existence)
        if ($request->adharcard_no) {
            $existingAdhar = Participant::withTrashed()->where('adharcard_no', $request->adharcard_no)->first();
            if ($existingAdhar) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already registered'
                ], 422);
            }
        }

        // 3. Create Pending Participant
        try {
            Participant::create([
                'full_name' => $request->full_name,
                'age' => $request->age,
                'mobile_number' => $request->mobile_number,
                'voter_id' => $request->has('voter_id') ? true : false,
                'voter_member_count' => $request->voter_member_count ?? 0,
                'epic_voter_id_no' => $request->epic_voter_id_no,
                'adharcard_no' => $request->adharcard_no,
                'permanent_address' => $request->permanent_address,
                'token' => null, // No token yet
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Form submitted for approval'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration. Please try again.'
            ], 500);
        }
    }
}

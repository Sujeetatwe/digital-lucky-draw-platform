<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Winner;
use App\Models\WinningGift;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WinnerController extends Controller
{
    private function ensureAdmin()
    {
        if (auth()->check() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Only Admin can manage winners.');
        }
    }

    public function search(Request $request)
    {
        $this->ensureAdmin();
        $query = $request->get('query');

        $participants = Participant::where('status', 'approved')
            ->where(function ($q) use ($query) {
                $q->where('full_name', 'like', "%{$query}%")
                    ->orWhere('token', $query) // Exact match for token
                    ->orWhere('token', 'like', "%{$query}%") // Partial match
                    ->orWhere('mobile_number', 'like', "%{$query}%");
            })
            // Exclude already won participants
            ->whereDoesntHave('winner')
            ->limit(10)
            ->get();

        return response()->json($participants);
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'winning_gift_id' => 'required|exists:winning_gifts,id',
        ]);

        // Double check duplication
        if (Winner::where('participant_id', $request->participant_id)->exists()) {
            return response()->json(['message' => 'This participant has already won a gift.'], 422);
        }

        $gift = WinningGift::find($request->winning_gift_id);

        if ($gift->quantity <= 0) {
            return response()->json(['message' => 'This gift is out of stock.'], 422);
        }

        // Decrement quantity
        $gift->decrement('quantity');

        Winner::create([
            'participant_id' => $request->participant_id,
            'winning_gift_id' => $request->winning_gift_id,
            'quantity' => 1, // Usually 1 gift per winner
            'assigned_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Gift assigned successfully.']);
    }

    public function index()
    {
        $this->ensureAdmin();
        // This method might be used if we load winners via AJAX or separate page.
        // For now, let's return JSON for the frontend table if needed, or view.
        // Given the requirement "Show all assigned winners in a Tailwind table", 
        // this might be called by the main index view or an ajax call.
        // Let's assume AJAX for "Searching must be real-time".

        $winners = Winner::with(['participant', 'gift', 'assigner'])
            ->latest()
            ->when(request('search'), function ($q) {
                $search = request('search');
                $q->whereHas('participant', function ($subQ) use ($search) {
                    $subQ->where('full_name', 'like', "%{$search}%")
                        ->orWhere('token', 'like', "%{$search}%")
                        ->orWhere('mobile_number', 'like', "%{$search}%");
                });
            })
            ->get();

        return response()->json($winners);
    }

    public function export()
    {
        $this->ensureAdmin();
        $fileName = 'winners_list.csv';
        $winners = Winner::with(['participant', 'gift'])->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Candidate Name', 'Token', 'Mobile', 'Gift Won', 'Quantity', 'Assigned Date');

        $callback = function () use ($winners, $columns) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper Excel display of UTF-8 characters
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, $columns);

            foreach ($winners as $winner) {
                $row['Candidate Name'] = $winner->participant->full_name;
                $row['Token'] = $winner->participant->token;
                $row['Mobile'] = $winner->participant->mobile_number;
                $row['Gift Won'] = $winner->gift->gift_name;
                $row['Quantity'] = $winner->quantity;
                $row['Assigned Date'] = $winner->created_at->format('Y-m-d H:i:s');

                fputcsv($file, array($row['Candidate Name'], $row['Token'], $row['Mobile'], $row['Gift Won'], $row['Quantity'], $row['Assigned Date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

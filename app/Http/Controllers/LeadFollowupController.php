<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadFollowup;
use Illuminate\Http\Request;

class LeadFollowupController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'outcome' => 'required|string',
            'notes' => 'nullable|string',
            'next_followup_date' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();
        
        LeadFollowup::create($validated);
        
        return back()->with('success', 'Follow-up saved successfully.');
    }
}

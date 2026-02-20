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
        
        // Update Lead
        $lead = Lead::find($validated['lead_id']);
        
        // Update next follow-up date
        if ($request->filled('next_followup_date')) {
            $lead->next_followup_date = $request->next_followup_date;
        }

        // Update status based on outcome logic (similar to LeadController)
        $outcome = $request->outcome;
        // Map obvious outcomes to statuses. 
        // Note: Ensure these statuses exist in LeadStatus table/seeder.
        if ($outcome == 'Interested') $lead->status = 'Interested'; // Capitalized to match seeder? Or Lowercase?
        // Seeder had Capitalized 'Interested'. LeadController update used 'interested' (lowercase).
        // Kanban view uses strtolower for grouping.
        // Lead table status default 'new'.
        // I'll use capitalized to match Seeder if possible, or consistent with Controller.
        // LeadController line 163 used 'interested'. I'll stick to that convention if current DB uses it.
        // But Seeder likely populated title Case.
        // User screenshot shows "Status: Walk-in" (Capitalized).
        // "Status: Converted" (Capitalized).
        // So I should use Capitalized.
        if (strtolower($outcome) == 'interested') $lead->status = 'Interested';
        if (strtolower($outcome) == 'not interested') $lead->status = 'Lost';
        if (strtolower($outcome) == 'need time') $lead->status = 'Contacted'; // or Counselling Done?
        if (strtolower($outcome) == 'visited') $lead->status = 'Walk-in';
        
        // If status was New, and now we followed up, maybe change to Contacted?
        if (strtolower($lead->status) == 'new') {
             $lead->status = 'Contacted';
        }

        $lead->save();
        
        return back()->with('success', 'Follow-up saved successfully.');
    }
}

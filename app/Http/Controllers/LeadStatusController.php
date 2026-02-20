<?php

namespace App\Http\Controllers;

use App\Models\LeadStatus;
use Illuminate\Http\Request;

class LeadStatusController extends Controller
{
    public function index()
    {
        $statuses = LeadStatus::orderBy('order')->get();
        return view('lead_statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('lead_statuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:lead_statuses,name|max:255',
            'color' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        LeadStatus::create($validated);

        return redirect()->route('lead_statuses.index')->with('success', 'Lead Status created successfully.');
    }

    public function edit(LeadStatus $leadStatus)
    {
        return view('lead_statuses.edit', compact('leadStatus'));
    }

    public function update(Request $request, LeadStatus $leadStatus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:lead_statuses,name,' . $leadStatus->id,
            'color' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $leadStatus->update($validated);

        return redirect()->route('lead_statuses.index')->with('success', 'Lead Status updated successfully.');
    }

    public function destroy(LeadStatus $leadStatus)
    {
        $leadStatus->delete();
        return redirect()->route('lead_statuses.index')->with('success', 'Lead Status deleted successfully.');
    }
}

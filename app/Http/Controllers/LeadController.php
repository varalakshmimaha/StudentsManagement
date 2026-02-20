<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\Student;
use App\Models\Branch;
use App\Models\Course;
use App\Models\User;
use App\Models\LeadStatus;
use App\Models\Batch;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['branch', 'counsellor']);

        // Filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('branch') && $request->branch != '') { // Corrected from branch_id to check logic
             $query->where('preferred_branch_id', $request->branch);
        }
        if ($request->has('counsellor') && $request->counsellor != '') {
            $query->where('assigned_counsellor_id', $request->counsellor);
        }
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('overdue') && $request->overdue == 'yes') {
             $query->whereDate('next_followup_date', '<', now());
        }

        $leads = $query->latest()->paginate(10);
        
        $branches = Branch::where('status', 'active')->get();
        $counsellors = User::whereHas('role', function($q) { // Adjusted to singular 'role'
             $q->where('name', 'Counselor')->orWhere('name', 'Admin');
        })->get();
        
        // If roles not set up, just fetch all active users
        if($counsellors->isEmpty()) {
             $counsellors = User::where('status', 'active')->get();
        }

        return view('leads.index', compact('leads', 'branches', 'counsellors'));
    }

    public function create()
    {
        $branches = Branch::where('status', 'active')->get();
        $courses = Course::where('status', 'active')->get();
        $counsellors = User::where('status', 'active')->get(); // Ideally filter by role
        $statuses = LeadStatus::where('status', 'active')->orderBy('order')->get();
        return view('leads.create', compact('branches', 'courses', 'counsellors', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:leads,phone',
            'email' => 'nullable|email|max:255',
            'source' => 'nullable|string',
            'preferred_branch_id' => 'nullable|exists:branches,id',
            'interested_courses' => 'nullable|array',
            'assigned_counsellor_id' => 'nullable|exists:users,id',
            'status' => 'required|string',
            // New Fields
            'current_address' => 'nullable|string',
            'current_city' => 'nullable|string',
            'current_state' => 'nullable|string',
            'current_pincode' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'permanent_city' => 'nullable|string',
            'permanent_state' => 'nullable|string',
            'permanent_pincode' => 'nullable|string',
            'highest_qualification' => 'nullable|string',
            'college_name' => 'nullable|string',
            'percentage' => 'nullable|numeric|between:0,100',
            'parent_type' => 'nullable|string',
            'parent_name' => 'nullable|string',
            'parent_mobile' => 'nullable|string',
            'parent_email' => 'nullable|string|email',
        ]);

        Lead::create($validated);

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['branch', 'counsellor', 'followups.createdBy']);
        $branches = Branch::where('status', 'active')->get();
        $courses = Course::where('status', 'active')->get();
        $counsellors = User::where('status', 'active')->get();
        
        return view('leads.show', compact('lead', 'branches', 'courses', 'counsellors'));
    }

    public function edit(Lead $lead)
    {
        $branches = Branch::where('status', 'active')->get();
        $courses = Course::where('status', 'active')->get();
        $counsellors = User::where('status', 'active')->get();
        $statuses = LeadStatus::where('status', 'active')->orderBy('order')->get();
        
        return view('leads.edit', compact('lead', 'branches', 'courses', 'counsellors', 'statuses'));
    }

    public function update(Request $request, Lead $lead)
    {
        // Handle Tab 1 (Info) & Tab 2 (Counselling) updates differently based on input?
        // Or just validate everything.

        $validated = $request->validate([
            // Tab 1
            'name' => 'sometimes|required|string|max:255',
            // New Fields for Tab 1
            'current_address' => 'nullable|string',
            'current_city' => 'nullable|string',
            'current_state' => 'nullable|string',
            'current_pincode' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'permanent_city' => 'nullable|string',
            'permanent_state' => 'nullable|string',
            'permanent_pincode' => 'nullable|string',
            'highest_qualification' => 'nullable|string',
            'college_name' => 'nullable|string',
            'percentage' => 'nullable|numeric|between:0,100',
            'parent_type' => 'nullable|string',
            'parent_name' => 'nullable|string',
            'parent_mobile' => 'nullable|string',
            'parent_email' => 'nullable|string|email',
            'phone' => 'sometimes|required|string|max:15|unique:leads,phone,' . $lead->id,
            'email' => 'nullable|email|max:255',
            'source' => 'nullable|string',
            'preferred_branch_id' => 'nullable|exists:branches,id',
            'interested_courses' => 'nullable|array',
            'assigned_counsellor_id' => 'nullable|exists:users,id',
            'status' => 'sometimes|required|string',
            
            // Tab 2
            'counselling_date' => 'nullable|date',
            'counselling_notes' => 'nullable|string',
            'counselling_outcome' => 'nullable|string',
            'estimated_joining_date' => 'nullable|date',
        ]);
        
        // Status Logic from Tab 2
        if ($request->has('counselling_outcome')) {
            $outcome = $request->counselling_outcome;
            if ($outcome == 'Interested') $validated['status'] = 'interested';
            if ($outcome == 'Not Interested') $validated['status'] = 'lost'; // or not_interested
            if ($outcome == 'Need Time') $validated['status'] = 'counselling_done';
        }

        $lead->update($validated);

        return back()->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }

    /**
     * Follow-ups Board
     */
    public function followupsBoard(Request $request)
    {
        $today = now()->toDateString();
        
        $overdue = Lead::whereDate('next_followup_date', '<', $today)
            ->whereNotIn('status', ['converted', 'lost'])
            ->with(['branch', 'counsellor'])
            ->get();
            
        $todayFollowups = Lead::whereDate('next_followup_date', $today)
            ->whereNotIn('status', ['converted', 'lost'])
            ->with(['branch', 'counsellor'])
            ->get();
            
        $upcoming = Lead::whereDate('next_followup_date', '>', $today)
            ->whereNotIn('status', ['converted', 'lost'])
            ->orderBy('next_followup_date', 'asc')
            ->limit(20)
            ->with(['branch', 'counsellor'])
            ->get();
            
        return view('leads.followups-board', compact('overdue', 'todayFollowups', 'upcoming'));
    }

    public function kanban(Request $request)
    {
        $statuses = LeadStatus::orderBy('order')->get();
        // Fetch all leads with relationships
        $query = Lead::with(['branch', 'counsellor']);

        // Filter by assigned user if 'assigned=me'
        if ($request->has('assigned') && $request->assigned == 'me') {
            $query->where('assigned_counsellor_id', auth()->id());
        }

        $leads = $query->latest()->get();
        
        // Group leads by status name (lowercase or as stored in DB)
        // Ensure status mapping aligns with LeadStatus names
        $leadsByStatus = $leads->groupBy(function($item) {
            return strtolower($item->status);
        });

        $branches = Branch::where('status', 'active')->get();
        $batches = Batch::where('status', 'active')->with('course')->get();

        return view('leads.kanban', compact('statuses', 'leadsByStatus', 'branches', 'batches'));
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|string',
            'batch_id' => 'nullable|exists:batches,id',
            'joining_date' => 'nullable|date',
            'lost_reason' => 'nullable|string',
            'lost_reason_notes' => 'nullable|string',
        ]);

        $lead->status = $request->status;
        
        if ($request->has('batch_id')) {
            $lead->batch_id = $request->batch_id;
        }
        if ($request->has('joining_date')) {
            $lead->joining_date = $request->joining_date;
        }
        if ($request->has('lost_reason')) {
            $lead->lost_reason = $request->lost_reason;
        }
        if ($request->has('lost_reason_notes')) {
            $lead->lost_reason_notes = $request->lost_reason_notes;
        }

        $lead->save();

        // Optional: Create Activity Log or History (LeadFollowup?)
        // $lead->followups()->create([...]);

        return response()->json(['success' => true, 'message' => 'Lead status updated successfully.']);
    }
}

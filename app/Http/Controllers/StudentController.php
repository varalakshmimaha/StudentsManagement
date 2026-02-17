<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Student::with(['branch', 'course', 'batch']);

        if ($request->has('branch') && $request->branch != '') {
            $query->where('branch_id', $request->branch);
        }
        if ($request->has('course') && $request->course != '') {
            $query->where('course_id', $request->course);
        }
        if ($request->has('batch') && $request->batch != '') {
            $query->where('batch_id', $request->batch);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('roll_number', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->latest()->paginate(10);

        $branches = \App\Models\Branch::all();
        $courses = \App\Models\Course::all();
        $batches = \App\Models\Batch::all(); // Could be optimized to only show relevent batches if course selected

        return view('students.index', compact('students', 'branches', 'courses', 'batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $branches = \App\Models\Branch::where('status', 'active')->get();
        $courses = \App\Models\Course::where('status', 'active')->get();
        $batches = \App\Models\Batch::whereIn('status', ['upcoming', 'ongoing'])->get(); 
        
        $lead = null;
        if ($request->has('lead_id')) {
            $lead = \App\Models\Lead::find($request->lead_id);
        }
        
        return view('students.create', compact('branches', 'courses', 'batches', 'lead'));
    }

    public function store(Request $request)
    {
        // Auto-generate Roll Number if not provided
        if (!$request->filled('roll_number')) {
            $year = date('Y');
            // Find last roll number of current year
            $lastStudent = \App\Models\Student::where('roll_number', 'LIKE', "STU-$year-%")
                ->orderBy('roll_number', 'desc')
                ->first();
            
            $sequence = 1;
            if ($lastStudent) {
                // Extract last 4 digits
                $lastRoll = $lastStudent->roll_number;
                $parts = explode('-', $lastRoll);
                $lastSequence = (int) end($parts);
                $sequence = $lastSequence + 1;
            }
            
            $request->merge(['roll_number' => 'STU-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT)]);
        }

        $validated = $request->validate([
            // Personal
            'name' => 'required|string|max:255',
            'roll_number' => 'required|string|unique:students,roll_number|max:50',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:20',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',

            // Academic
            'branch_id' => 'required|exists:branches,id',
            'course_id' => 'required|exists:courses,id',
            'batch_id' => 'required|exists:batches,id',
            'highest_qualification' => 'nullable|string|max:255',
            'college_name' => 'nullable|string|max:255',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:admission_done,ongoing,placed,dropped',
            
            // Parent
            'parent_type' => 'required|string|in:Father,Mother,Guardian',
            'parent_name' => 'required|string|max:255',
            'parent_mobile' => 'required|string|max:20',
            'parent_email' => 'nullable|email|max:255',

            // Fee
            'total_fee' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_fee' => 'required|numeric|min:0',
            'payment_type' => 'required|in:full,installments',
            'notes' => 'nullable|string',

            // Lead
            'lead_id' => 'nullable|exists:leads,id',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students', 'public');
            $validated['photo'] = $path;
        }

        // Set default fee status
        $validated['fee_status'] = 'unpaid';

        $student = \App\Models\Student::create($validated);
        
        // Handle Lead Conversion
        if ($request->filled('lead_id')) {
            $lead = \App\Models\Lead::find($request->lead_id);
            if ($lead) {
                $lead->update(['status' => 'converted']);
                // Can also link student_id back to lead if lead table has it, but usually student has lead_id.
            }
        }

        if ($request->has('save_and_new')) {
            return redirect()->route('students.create')->with('success', 'Student created successfully.');
        }

        return redirect()->route('students.index')->with('success', 'Student created converted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = \App\Models\Student::with(['branch', 'course', 'batch', 'payments', 'attendances'])->findOrFail($id);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $branches = \App\Models\Branch::all();
        $courses = \App\Models\Course::all();
        $batches = \App\Models\Batch::all();

        return view('students.edit', compact('student', 'branches', 'courses', 'batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = \App\Models\Student::findOrFail($id);

        $validated = $request->validate([
             // Personal
            'name' => 'required|string|max:255',
            'roll_number' => 'required|string|max:50|unique:students,roll_number,' . $id,
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:20',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',

            // Academic
            'branch_id' => 'required|exists:branches,id',
            'course_id' => 'required|exists:courses,id',
            'batch_id' => 'required|exists:batches,id',
            'highest_qualification' => 'nullable|string|max:255',
            'college_name' => 'nullable|string|max:255',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:admission_done,ongoing,placed,dropped',
            
            // Parent
            'parent_type' => 'required|string|in:Father,Mother,Guardian',
            'parent_name' => 'required|string|max:255',
            'parent_mobile' => 'required|string|max:20',
            'parent_email' => 'nullable|email|max:255',

            // Fee
            'total_fee' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_fee' => 'required|numeric|min:0',
            'payment_type' => 'required|in:full,installments',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo logic if needed
            $path = $request->file('photo')->store('students', 'public');
            $validated['photo'] = $path;
        }

        $student->update($validated);
        
        // Recalculate fee status in case fee changed
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
        $feeStatus = 'unpaid';
        if ($totalPaid >= $student->final_fee) {
            $feeStatus = 'fully_paid';
        } elseif ($totalPaid > 0) {
            $feeStatus = 'partial';
        }
        $student->update(['fee_status' => $feeStatus]);
        
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}

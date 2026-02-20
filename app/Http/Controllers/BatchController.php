<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Batch::with(['branch', 'course', 'students', 'teachers']);

        if ($request->has('branch') && $request->branch != '') {
            $query->where('branch_id', $request->branch);
        }

        // Support branch_id param for AJAX dependent dropdown
        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->has('course') && $request->course != '') {
            $query->where('course_id', $request->course);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date_from') && $request->start_date_from != '') {
            $query->where('start_date', '>=', $request->start_date_from);
        }

        if ($request->has('start_date_to') && $request->start_date_to != '') {
            $query->where('start_date', '<=', $request->start_date_to);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Return JSON if AJAX request (for dependent dropdown)
        if ($request->ajax()) {
            return response()->json($query->get(['id', 'name']));
        }

        $batches = $query->latest()->paginate(10);
        
        $branches = \App\Models\Branch::all();
        $courses = \App\Models\Course::all();

        return view('batches.index', compact('batches', 'branches', 'courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = \App\Models\Branch::where('status', 'active')->get();
        $courses = \App\Models\Course::where('status', 'active')->get();
        $teachers = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'Teacher');
        })->where('status', 'active')->get();
        return view('batches.create', compact('branches', 'courses', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'course_id' => 'required|exists:courses,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'total_fee' => 'required|numeric|min:0',
            'capacity' => 'nullable|integer|min:0',
            'status' => 'required|in:upcoming,ongoing,completed',
            'teachers' => 'array',
            'teachers.*' => 'exists:users,id',
        ]);

        $batch = \App\Models\Batch::create($validated);

        if ($request->has('teachers')) {
            $batch->teachers()->sync($request->teachers);
        }

        if ($request->has('save_and_new')) {
            return redirect()->route('batches.create')->with('success', 'Batch created successfully.');
        }

        return redirect()->route('batches.index')->with('success', 'Batch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $batch = \App\Models\Batch::with(['branch', 'course', 'students.payments', 'teachers'])->findOrFail($id);
        
        $totalExpected = $batch->students->sum('final_fee');
        // Calculate total collected from payments of students in this batch
        // Note: A student might have payments. simplified:
        $totalCollected = 0;
        foreach($batch->students as $student) {
             $totalCollected += $student->payments->sum('amount');
        }
        $totalDue = $totalExpected - $totalCollected;
        
        return view('batches.show', compact('batch', 'totalExpected', 'totalCollected', 'totalDue'));
    }

    public function export(string $id)
    {
        $batch = \App\Models\Batch::with(['students'])->findOrFail($id);
        
        $filename = "batch_{$batch->id}_students.csv";
        $handle = fopen('php://output', 'w');
        
        ob_start();
        fputcsv($handle, ['Name', 'Roll Number', 'Mobile', 'Email', 'Fee Status', 'Final Fee']);
        
        foreach ($batch->students as $student) {
            fputcsv($handle, [
                $student->name,
                $student->roll_number,
                $student->mobile,
                $student->email,
                $student->fee_status,
                $student->final_fee
            ]);
        }
        
        fclose($handle);
        $content = ob_get_clean();
        
        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $batch = \App\Models\Batch::with('teachers')->findOrFail($id);
        $branches = \App\Models\Branch::where('status', 'active')->orWhere('id', $batch->branch_id)->get();
        $courses = \App\Models\Course::where('status', 'active')->orWhere('id', $batch->course_id)->get();
        $teachers = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'Teacher');
        })->where('status', 'active')->get();
        
        return view('batches.edit', compact('batch', 'branches', 'courses', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $batch = \App\Models\Batch::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'course_id' => 'required|exists:courses,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'total_fee' => 'required|numeric|min:0',
            'capacity' => 'nullable|integer|min:0',
            'status' => 'required|in:upcoming,ongoing,completed',
            'teachers' => 'array',
            'teachers.*' => 'exists:users,id',
        ]);

        $batch->update($validated);

        if ($request->has('teachers')) {
            $batch->teachers()->sync($request->teachers);
        } else {
            $batch->teachers()->detach();
        }

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $batch = \App\Models\Batch::findOrFail($id);
        // Check for students?
        if ($batch->students()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete batch with enrolled students.']);
        }
        $batch->delete();

        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }
}

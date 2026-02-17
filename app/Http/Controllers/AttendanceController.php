<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $batches = \App\Models\Batch::whereIn('status', ['ongoing', 'completed'])->get();
        
        $selectedBatchId = $request->input('batch_id');
        $selectedMonth = $request->input('month', date('Y-m'));
        
        $attendanceData = [];
        $daysInMonth = 0;
        $students = [];

        if ($selectedBatchId) {
            $year = date('Y', strtotime($selectedMonth));
            $month = date('m', strtotime($selectedMonth));
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            
            $students = \App\Models\Student::where('batch_id', $selectedBatchId)
                        ->orderBy('name')
                        ->get();
            
            // Eager load attendance for the whole month
            $attendances = \App\Models\Attendance::where('batch_id', $selectedBatchId)
                            ->whereYear('date', $year)
                            ->whereMonth('date', $month)
                            ->get()
                            ->groupBy('student_id');

            foreach ($students as $student) {
                $studentAttendance = $attendances->get($student->id, collect());
                $attendanceData[$student->id] = $studentAttendance->keyBy(function($item) {
                     return $item->date->format('j'); // Day number without leading zeros
                });
            }
        }

        return view('attendances.index', compact('batches', 'selectedBatchId', 'selectedMonth', 'attendanceData', 'daysInMonth', 'students'));
    }

    /**
     * Show the form for creating a new resource (Mark Attendance).
     */
    public function create(Request $request)
    {
        $batches = \App\Models\Batch::where('status', 'ongoing')->get();
        
        $selectedBatchId = $request->input('batch_id');
        $selectedDate = $request->input('date', date('Y-m-d'));
        
        $students = [];
        $existingAttendance = collect();

        if ($selectedBatchId) {
            $students = \App\Models\Student::where('batch_id', $selectedBatchId)
                        ->whereIn('status', ['ongoing', 'admission_done'])
                        ->orderBy('name')
                        ->get();
            
            $existingAttendance = \App\Models\Attendance::where('batch_id', $selectedBatchId)
                                    ->whereDate('date', $selectedDate)
                                    ->get()
                                    ->keyBy('student_id');
        }

        return view('attendances.create', compact('batches', 'selectedBatchId', 'selectedDate', 'students', 'existingAttendance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,absent,late,excused',
            'remarks' => 'nullable|array',
        ]);

        $batchId = $validated['batch_id'];
        $date = $validated['date'];
        $markedBy = auth()->id();

        foreach ($validated['attendance'] as $studentId => $status) {
            \App\Models\Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'batch_id' => $batchId,
                    'date' => $date,
                ],
                [
                    'status' => $status,
                    'remarks' => $validated['remarks'][$studentId] ?? null,
                    'marked_by' => $markedBy,
                ]
            );
        }

        return redirect()->route('attendances.create', ['batch_id' => $batchId, 'date' => $date])->with('success', 'Attendance saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not implemented
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         // Not necessary, handled in create/store via updateOrCreate
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

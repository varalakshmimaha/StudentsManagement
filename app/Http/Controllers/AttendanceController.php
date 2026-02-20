<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Branch;
use App\Models\Holiday;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::all();
        $batches = [];
        $students = [];
        $holiday = null;
        $isSunday = false;
        $markedAttendance = [];
        
        $selectedBranch = $request->branch_id;
        $selectedBatch = $request->batch_id;
        $selectedDate = $request->date ?? date('Y-m-d');

        if ($selectedBranch) {
            $batches = Batch::where('branch_id', $selectedBranch)->get();
        }

        if ($selectedBranch && $selectedBatch && $selectedDate) {
            $date = Carbon::parse($selectedDate);
            $isSunday = $date->isSunday();
            
            $holiday = Holiday::where('date', $selectedDate)
                ->where(function ($q) use ($selectedBranch) {
                    $q->whereNull('branch_id')->orWhere('branch_id', $selectedBranch);
                })->first();

            $students = Student::where('batch_id', $selectedBatch)
                ->where('status', '!=', 'dropped')
                ->orderBy('name')
                ->get();

            $markedAttendance = Attendance::where('batch_id', $selectedBatch)
                ->where('date', $selectedDate)
                ->pluck('status', 'student_id')
                ->toArray();
        }

        return view('attendances.index', compact(
            'branches', 
            'batches', 
            'students', 
            'holiday', 
            'isSunday', 
            'markedAttendance',
            'selectedBranch',
            'selectedBatch',
            'selectedDate'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent',
        ]);

        $batchId = $request->batch_id;
        $date = $request->date;
        $markedBy = auth()->id();

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                    'batch_id' => $batchId,
                ],
                [
                    'status' => $status,
                    'marked_by' => $markedBy,
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance saved successfully for ' . Carbon::parse($date)->format('d M Y'));
    }
}

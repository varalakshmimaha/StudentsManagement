<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Branch;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $branchId = $request->get('branch_id');
        $branches = \App\Models\Branch::all();

        // Base queries with optional branch filter
        $studentQuery = Student::query();
        $batchQuery = Batch::query();
        $paymentQuery = Payment::query();
        $attendanceQuery = Attendance::query();

        if ($branchId) {
            $studentQuery->where('branch_id', $branchId);
            $batchQuery->where('branch_id', $branchId);
            $paymentQuery->whereHas('student', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
            $attendanceQuery->whereHas('student', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        // 1. KPI Cards
        
        // Students
        $studentsCount = (clone $studentQuery)->count();
        $lastMonthStudents = (clone $studentQuery)->where('created_at', '<', now()->startOfMonth())->count();
        // Avoid division by zero
        $studentTrend = $lastMonthStudents > 0 ? (($studentsCount - $lastMonthStudents) / $lastMonthStudents) * 100 : 0;
        
        // Batches
        $activeBatches = (clone $batchQuery)->where('status', 'ongoing')->count();
        $lastMonthBatches = (clone $batchQuery)->where('status', 'ongoing')->where('created_at', '<', now()->startOfMonth())->count();
        $batchesTrend = $lastMonthBatches > 0 ? (($activeBatches - $lastMonthBatches) / $lastMonthBatches) * 100 : 0;
        
        // Collection
        $collectedThisMonth = (clone $paymentQuery)->whereYear('payment_date', now()->year)
                                     ->whereMonth('payment_date', now()->month)
                                     ->sum('amount');
        $collectedLastMonth = (clone $paymentQuery)->whereYear('payment_date', now()->subMonth()->year)
                                     ->whereMonth('payment_date', now()->subMonth()->month)
                                     ->sum('amount');
        $collectionTrend = $collectedLastMonth > 0 ? (($collectedThisMonth - $collectedLastMonth) / $collectedLastMonth) * 100 : 0;
        
        // Total Due
        $totalFeesExpected = (clone $studentQuery)->sum('final_fee');
        $totalFeesCollected = (clone $paymentQuery)->sum('amount');
        $totalDue = $totalFeesExpected - $totalFeesCollected;
        
        // 2. Charts Data
        
        // Monthly Collection (Last 6 Months)
        $monthlyCollection = (clone $paymentQuery)->select(
            DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'),
            DB::raw('SUM(amount) as total')
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->limit(6)
        ->get()
        ->reverse()
        ->values();
        
        // Attendance Trend (Last 6 Months)
        $attendanceTrend = (clone $attendanceQuery)->select(
            DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total_records'),
            DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count')
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->limit(6)
        ->get()
        ->reverse()
        ->map(function($item) {
            $item->percentage = $item->total_records > 0 ? round(($item->present_count / $item->total_records) * 100, 2) : 0;
            return $item;
        })
        ->values();

        // 5. Lead Conversion (Last 6 Months)
        $leadConversion = Lead::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total_leads'),
            DB::raw('SUM(CASE WHEN status = "converted" THEN 1 ELSE 0 END) as converted_count')
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->limit(6)
        ->get()
        ->reverse()
        ->values();

        // 3. Financial Summary
        $todayCollection = (clone $paymentQuery)->whereDate('payment_date', today())->sum('amount');
        $weekCollection = (clone $paymentQuery)->whereBetween('payment_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount');
        
        // 4. Top Tables
        
        // Top 5 Due
        $topDueStudents = (clone $studentQuery)->select('students.*')
            ->selectRaw('(final_fee - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id)) as due_amount')
            ->having('due_amount', '>', 0)
            ->with(['batch', 'branch'])
            ->orderBy('due_amount', 'desc')
            ->limit(5)
            ->get();
            
        // Top 5 Batches by Collection
        $topBatches = DB::table('payments')
            ->join('students', 'payments.student_id', '=', 'students.id')
            ->join('batches', 'students.batch_id', '=', 'batches.id')
            ->select('batches.name', DB::raw('SUM(payments.amount) as collected'))
            ->when($branchId, function($q) use ($branchId) {
                return $q->where('batches.branch_id', $branchId);
            })
            ->groupBy('batches.id', 'batches.name')
            ->orderBy('collected', 'desc')
            ->limit(5)
            ->get();

        return view('reports.index', compact(
            'studentsCount', 'studentTrend',
            'activeBatches', 'batchesTrend',
            'collectedThisMonth', 'collectionTrend',
            'totalDue',
            'monthlyCollection', 'attendanceTrend',
            'todayCollection', 'weekCollection',
            'topDueStudents', 'topBatches',
            'branches', 'branchId', 'leadConversion'
        ));
    }

    /**
     * Fee Collection Report
     */
    public function feeCollection(Request $request)
    {
        $query = Payment::with(['student.batch', 'collectedBy']);

        // Filters
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        if ($request->has('branch') && $request->branch != '') {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        }

        if ($request->has('batch') && $request->batch != '') {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('batch_id', $request->batch);
            });
        }

        $payments = $query->latest('payment_date')->paginate(50);
        $total = $query->sum('amount');

        $branches = Branch::all();
        $batches = Batch::all();

        return view('reports.fee-collection', compact('payments', 'total', 'branches', 'batches'));
    }

    /**
     * Due Report
     */
    public function dueReport(Request $request)
    {
        $query = Student::with(['batch', 'branch'])
            ->select('students.*')
            ->selectRaw('(final_fee - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id)) as due_amount')
            ->having('due_amount', '>', 0);

        // Filters
        if ($request->has('branch') && $request->branch != '') {
            $query->where('branch_id', $request->branch);
        }

        if ($request->has('batch') && $request->batch != '') {
            $query->where('batch_id', $request->batch);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('due_amount', 'desc')->paginate(50);
        $totalDue = Student::selectRaw('SUM(final_fee - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id)) as total_due')
            ->value('total_due') ?? 0;

        $branches = Branch::all();
        $batches = Batch::all();

        return view('reports.due-report', compact('students', 'totalDue', 'branches', 'batches'));
    }

    /**
     * Attendance Report
     */
    public function attendanceReport(Request $request)
    {
        $reportType = $request->input('report_type', 'daily'); // daily or monthly
        $reportDate = $request->input('report_date', now()->format('Y-m-d'));
        $reportMonth = $request->input('report_month', now()->format('Y-m'));
        $batchId = $request->input('batch_id');

        $branches = Branch::all();
        $allBatches = Batch::all();
        $batch = null;
        $summary = [];
        $studentAttendance = [];
        $workingDays = 0;
        $sessionsTaken = 0;

        if ($batchId) {
            $batch = Batch::with(['course', 'branch'])->findOrFail($batchId);
            $students = Student::where('batch_id', $batchId)->where('status', '!=', 'dropped')->get();
            $studentIds = $students->pluck('id');

            if ($reportType == 'daily') {
                $date = $reportDate;
                $isWorkingDay = true;
                $holidayName = null;

                if (Carbon::parse($date)->isSunday()) {
                    $isWorkingDay = false;
                    $holidayName = 'Sunday';
                } else {
                    $holiday = Holiday::where('date', $date)
                        ->where(function($q) use ($batch) {
                            $q->whereNull('branch_id')->orWhere('branch_id', $batch->branch_id);
                        })->first();
                    
                    if (!$holiday) {
                        $holiday = Holiday::where('is_recurring', true)
                            ->where('month_day', Carbon::parse($date)->format('m-d'))
                            ->where(function($q) use ($batch) {
                                $q->whereNull('branch_id')->orWhere('branch_id', $batch->branch_id);
                            })->first();
                    }

                    if ($holiday) {
                        $isWorkingDay = false;
                        $holidayName = $holiday->name;
                    }
                }

                $workingDays = $isWorkingDay ? 1 : 0;
                
                $attendances = Attendance::where('batch_id', $batchId)
                    ->whereDate('date', $date)
                    ->get();
                
                $sessionsTaken = $attendances->isNotEmpty() ? 1 : 0;

                $presentCount = $attendances->where('status', 'present')->count();
                $absentCount = $attendances->where('status', 'absent')->count();
                $notMarkedCount = $isWorkingDay ? ($students->count() - $attendances->count()) : 0;

                $summary = [
                    'batch_name' => $batch->name,
                    'course' => $batch->course->name ?? '-',
                    'branch' => $batch->branch->name ?? '-',
                    'total_students' => $students->count(),
                    'total_days' => $workingDays,
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                    'not_marked_count' => $notMarkedCount,
                    'percentage' => ($isWorkingDay && $students->count() > 0) ? round(($presentCount / $students->count()) * 100, 2) : 0,
                    'holiday_name' => $holidayName,
                ];

                $studentAttendance = $students->map(function($student) use ($attendances, $isWorkingDay, $holidayName) {
                    $record = $attendances->where('student_id', $student->id)->first();
                    $status = 'Not Marked';
                    if (!$isWorkingDay) {
                        $status = 'Holiday (' . $holidayName . ')';
                    } elseif ($record) {
                        $status = ucfirst($record->status);
                    }
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'roll_number' => $student->roll_number,
                        'status' => $status,
                    ];
                });

            } else {
                // Monthly Report
                $startOfMonth = Carbon::parse($reportMonth)->startOfMonth();
                $endOfMonth = Carbon::parse($reportMonth)->endOfMonth();
                
                // Calculate working days in month
                $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
                $branchId = $batch->branch_id;
                
                $holidays = Holiday::whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->where(function($q) use ($branchId) {
                        $q->whereNull('branch_id')->orWhere('branch_id', $branchId);
                    })->pluck('date')->map(fn($d) => $d->format('Y-m-d'))->toArray();

                $recurringHolidays = Holiday::where('is_recurring', true)
                    ->where(function($q) use ($branchId) {
                        $q->whereNull('branch_id')->orWhere('branch_id', $branchId);
                    })->get();

                foreach ($period as $date) {
                    if ($date->isSunday()) continue;
                    
                    $isHoliday = in_array($date->format('Y-m-d'), $holidays);
                    if (!$isHoliday) {
                        $mDay = $date->format('m-d');
                        if ($recurringHolidays->where('month_day', $mDay)->isNotEmpty()) {
                            $isHoliday = true;
                        }
                    }

                    if (!$isHoliday) {
                        $workingDays++;
                    }
                }

                $attendances = Attendance::where('batch_id', $batchId)
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->get();
                
                $sessionsTaken = $attendances->pluck('date')->unique()->count();
                $presentCount = $attendances->where('status', 'present')->count();
                $absentCount = $attendances->where('status', 'absent')->count();

                $summary = [
                    'batch_name' => $batch->name,
                    'course' => $batch->course->name ?? '-',
                    'branch' => $batch->branch->name ?? '-',
                    'total_students' => $students->count(),
                    'working_days' => $workingDays,
                    'sessions_taken' => $sessionsTaken,
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                    'percentage' => ($workingDays > 0 && $students->count() > 0) ? round(($presentCount / ($sessionsTaken * $students->count() ?: 1)) * 100, 2) : 0,
                ];

                $studentAttendance = $students->map(function($student) use ($attendances, $workingDays, $sessionsTaken) {
                    $studentRecords = $attendances->where('student_id', $student->id);
                    $presentDays = $studentRecords->where('status', 'present')->count();
                    $absentDays = $studentRecords->where('status', 'absent')->count();
                    
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'roll_number' => $student->roll_number,
                        'working_days' => $workingDays,
                        'present_days' => $presentDays,
                        'absent_days' => $absentDays,
                        'percentage' => $sessionsTaken > 0 ? round(($presentDays / $sessionsTaken) * 100, 2) : 0,
                    ];
                });
            }
        }

        if ($request->has('export') && $batchId) {
            return $this->exportAttendanceReportData($summary, $studentAttendance, $reportType, $reportDate ?: $reportMonth);
        }

        return view('reports.attendance-report', compact(
            'allBatches', 'batchId', 'batch', 'reportType', 'reportDate', 'reportMonth', 
            'summary', 'studentAttendance'
        ));
    }

    /**
     * Student Attendance Detail (Modal/Detail View)
     */
    public function studentAttendanceDetail(Request $request, Student $student)
    {
        $reportType = $request->input('report_type', 'daily');
        $date = $request->input('date');
        $month = $request->input('month', now()->format('Y-m'));
        
        $batch = $student->batch;
        
        if ($reportType == 'daily') {
            $attendance = Attendance::where('student_id', $student->id)
                ->whereDate('date', $date)
                ->with('markedBy')
                ->first();
            
            return response()->json([
                'student' => $student,
                'date' => $date,
                'status' => $attendance ? ucfirst($attendance->status) : 'Not Marked',
                'marked_by' => $attendance->markedBy->name ?? 'System',
                'marked_at' => $attendance ? $attendance->created_at->format('d M Y H:i') : '-',
            ]);
        } else {
            // Monthly Summary & Calendar
            $startOfMonth = Carbon::parse($month)->startOfMonth();
            $endOfMonth = Carbon::parse($month)->endOfMonth();
            
            $attendances = Attendance::where('student_id', $student->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();
            
            $branchId = $student->branch_id;
            $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
            
            $calendarData = [];
            $presentDates = [];
            $absentDates = [];
            
            $workingDays = 0;
            $presentDays = 0;
            $absentDays = 0;

            $holidays = Holiday::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where(function($q) use ($branchId) {
                    $q->whereNull('branch_id')->orWhere('branch_id', $branchId);
                })->get();
            
            $recurringHolidays = Holiday::where('is_recurring', true)
                ->where(function($q) use ($branchId) {
                    $q->whereNull('branch_id')->orWhere('branch_id', $branchId);
                })->get();

            foreach ($period as $day) {
                $dStr = $day->format('Y-m-d');
                $status = 'not_marked';
                $title = null;

                if ($day->isSunday()) {
                    $status = 'sunday';
                    $title = 'Sunday';
                } else {
                    $holiday = $holidays->where('date', $day)->first();
                    if (!$holiday) {
                        $mDay = $day->format('m-d');
                        $holiday = $recurringHolidays->where('month_day', $mDay)->first();
                    }

                    if ($holiday) {
                        $status = 'holiday';
                        $title = $holiday->name;
                    } else {
                        $workingDays++;
                        $record = $attendances->where('date', $day)->first();
                        if ($record) {
                            $status = $record->status;
                            if ($status == 'present') {
                                $presentDays++;
                                $presentDates[] = $day->format('d M');
                            } else {
                                $absentDays++;
                                $absentDates[] = $day->format('d M');
                            }
                        }
                    }
                }

                $calendarData[] = [
                    'date' => $dStr,
                    'day' => $day->day,
                    'status' => $status,
                    'title' => $title,
                ];
            }

            return view('reports.student-attendance-detail', compact(
                'student', 'month', 'calendarData', 'presentDays', 'absentDays', 
                'workingDays', 'presentDates', 'absentDates'
            ));
        }
    }

    private function exportAttendanceReportData($summary, $students, $type, $date)
    {
        $filename = 'attendance_report_' . $date . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($summary, $students, $type, $date) {
            $file = fopen('php://output', 'w');

            // --- Summary Header Section ---
            fputcsv($file, ['Attendance Report']);
            fputcsv($file, ['Batch', $summary['batch_name']]);
            fputcsv($file, ['Course', $summary['course']]);
            fputcsv($file, ['Branch', $summary['branch']]);
            fputcsv($file, ['Report Type', ucfirst($type)]);
            fputcsv($file, ['Date / Period', $date]);
            fputcsv($file, ['Total Students', $summary['total_students']]);
            fputcsv($file, ['Present', $summary['present_count']]);
            fputcsv($file, ['Absent', $summary['absent_count']]);
            if ($type == 'daily') {
                fputcsv($file, ['Attendance %', $summary['percentage'] . '%']);
            } else {
                fputcsv($file, ['Working Days', $summary['working_days'] ?? '-']);
                fputcsv($file, ['Attendance Avg', $summary['percentage'] . '%']);
            }
            // Blank separator row
            fputcsv($file, []);

            // --- Student Data Table ---
            if ($type == 'daily') {
                fputcsv($file, ['Student Name', 'Roll No', 'Status', 'Date']);
                foreach ($students as $s) {
                    fputcsv($file, [$s['name'], $s['roll_number'], $s['status'], $date]);
                }
            } else {
                fputcsv($file, ['Student Name', 'Roll No', 'Working Days', 'Present Days', 'Absent Days', 'Attendance %']);
                foreach ($students as $s) {
                    fputcsv($file, [$s['name'], $s['roll_number'], $s['working_days'], $s['present_days'], $s['absent_days'], $s['percentage'] . '%']);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

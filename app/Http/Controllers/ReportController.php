<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Branch;
use App\Models\Attendance;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

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
        $month = $request->input('month', now()->format('Y-m'));
        $batchId = $request->input('batch');

        $query = Batch::with(['branch', 'course', 'students']);

        if ($batchId) {
            $query->where('id', $batchId);
        }

        $batches = $query->get();

        // Calculate attendance percentage for each batch
        $reportData = $batches->map(function($batch) use ($month) {
            $studentCount = $batch->students()->count();
            
            if ($studentCount == 0) {
                return null;
            }

            // Get attendance records for this batch in the selected month
            $attendances = Attendance::where('batch_id', $batch->id)
                ->whereYear('date', substr($month, 0, 4))
                ->whereMonth('date', substr($month, 5, 2))
                ->get();

            $totalDays = $attendances->pluck('date')->unique()->count();
            $presentCount = $attendances->where('status', 'present')->count();
            $totalPossible = $totalDays * $studentCount;
            
            $attendancePercentage = $totalPossible > 0 ? round(($presentCount / $totalPossible) * 100, 2) : 0;

            return [
                'batch' => $batch,
                'total_students' => $studentCount,
                'total_days' => $totalDays,
                'present_count' => $presentCount,
                'attendance_percentage' => $attendancePercentage,
            ];
        })->filter();

        $allBatches = Batch::all();

        return view('reports.attendance-report', compact('reportData', 'month', 'allBatches', 'batchId'));
    }

    /**
     * Export Due Report to CSV
     */
    public function exportDueReport(Request $request)
    {
        $query = Student::with(['batch', 'branch'])
            ->select('students.*')
            ->selectRaw('(final_fee - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id)) as due_amount')
            ->having('due_amount', '>', 0);

        // Apply same filters
        if ($request->has('branch') && $request->branch != '') {
            $query->where('branch_id', $request->branch);
        }

        if ($request->has('batch') && $request->batch != '') {
            $query->where('batch_id', $request->batch);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('due_amount', 'desc')->get();

        $filename = 'due_report_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Roll No', 'Student Name', 'Batch', 'Branch', 'Due Amount', 'Mobile', 'Status']);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->roll_number,
                    $student->name,
                    $student->batch->name ?? '-',
                    $student->branch->name ?? '-',
                    number_format($student->due_amount, 2),
                    $student->mobile,
                    ucfirst(str_replace('_', ' ', $student->status)),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    /**
     * Counsellor Performance Report
     */
    public function counsellorReport()
    {
        $stats = Lead::select('assigned_counsellor_id', 
            DB::raw('count(*) as total_leads'),
            DB::raw('sum(case when status="converted" then 1 else 0 end) as converted_leads'),
            DB::raw('sum(case when status in ("lost", "not_interested") then 1 else 0 end) as lost_leads'),
            DB::raw('sum(case when status="interested" then 1 else 0 end) as interested_leads')
        )
        ->whereNotNull('assigned_counsellor_id')
        ->groupBy('assigned_counsellor_id')
        ->with('counsellor')
        ->get();

        return view('reports.counsellor-performance', compact('stats'));
    }

    /**
     * Lead Source Report
     */
    public function sourceReport()
    {
        $stats = Lead::select('source', 
            DB::raw('count(*) as total_leads'),
            DB::raw('sum(case when status="converted" then 1 else 0 end) as converted_leads'),
            DB::raw('sum(case when status="interested" then 1 else 0 end) as interested_leads')
        )
        ->groupBy('source')
        ->get();

        return view('reports.source-report', compact('stats'));
    }
}

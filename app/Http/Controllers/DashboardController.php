<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Lead;
use App\Models\LeadFollowup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $thisMonth = now()->month;
        $thisYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastMonthYear = now()->subMonth()->year;

        // ============================================
        // TOP ROW - 6 SUMMARY CARDS
        // ============================================

        // 1. Total Students
        $totalStudents = Student::count();
        $newStudentsThisWeek = Student::where('created_at', '>=', now()->startOfWeek())->count();

        // 2. Active Batches
        $activeBatches = Batch::where('status', 'ongoing')->count();
        $upcomingBatches = Batch::where('status', 'upcoming')->count();
        $completedBatches = Batch::where('status', 'completed')->count();

        // 3. Fees Collected (This Month)
        $feesCollectedThisMonth = Payment::whereMonth('payment_date', $thisMonth)
            ->whereYear('payment_date', $thisYear)
            ->sum('amount');
        
        $feesCollectedLastMonth = Payment::whereMonth('payment_date', $lastMonth)
            ->whereYear('payment_date', $lastMonthYear)
            ->sum('amount');

        // 4. Total Due
        $totalDue = Student::selectRaw('SUM(final_fee - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id)) as total_due')
            ->value('total_due') ?? 0;
        
        $studentsWithDue = Student::selectRaw('students.*, (final_fee - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id)) as due_amount')
            ->having('due_amount', '>', 0)
            ->count();

        // 5. Today Attendance
        $totalActiveStudents = Student::whereHas('batch', function($q) use ($today) {
            $q->where('status', 'ongoing')
              ->whereDate('start_date', '<=', $today)
              ->where(function($q2) use ($today) {
                  $q2->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
              });
        })->count();

        $presentToday = Attendance::whereDate('date', $today)->where('status', 'present')->count();
        $absentToday = Attendance::whereDate('date', $today)->where('status', 'absent')->count();
        $attendancePercentage = $totalActiveStudents > 0 ? round(($presentToday / $totalActiveStudents) * 100, 1) : 0;

        // 6. Leads Pipeline
        $followupsToday = LeadFollowup::whereDate('next_followup_date', $today)->count();
        $overdueFollowups = LeadFollowup::whereDate('next_followup_date', '<', $today)
            ->whereHas('lead', function($q) {
                $q->where('status', '!=', 'converted');
            })
            ->count();
        $convertedThisMonth = Lead::where('status', 'converted')
            ->whereMonth('updated_at', $thisMonth)
            ->whereYear('updated_at', $thisYear)
            ->count();

        // ============================================
        // CHART DATA
        // ============================================

        // 1. Monthly Collection (Last 6 Months)
        $monthlyCollection = Payment::select(
            DB::raw('DATE_FORMAT(payment_date, "%M %Y") as month_label'),
            DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month_key'),
            DB::raw('SUM(amount) as total')
        )
        ->groupBy('month_key', 'month_label')
        ->orderBy('month_key', 'desc')
        ->limit(6)
        ->get()
        ->reverse()
        ->values();

        // 2. Attendance Trend (Last 6 Months)
        $attendanceTrend = Attendance::select(
            DB::raw('DATE_FORMAT(date, "%M %Y") as month_label'),
            DB::raw('DATE_FORMAT(date, "%Y-%m") as month_key'),
            DB::raw('COUNT(*) as total_records'),
            DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count')
        )
        ->groupBy('month_key', 'month_label')
        ->orderBy('month_key', 'desc')
        ->limit(6)
        ->get()
        ->reverse()
        ->map(function($item) {
            $item->percentage = $item->total_records > 0 ? round(($item->present_count / $item->total_records) * 100, 1) : 0;
            return $item;
        })
        ->values();

        // 3. Lead Conversion Trend (Last 6 Months)
        $leadConversionTrend = Lead::select(
            DB::raw('DATE_FORMAT(created_at, "%M %Y") as month_label'),
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_key'),
            DB::raw('COUNT(*) as total_leads'),
            DB::raw('SUM(CASE WHEN status = "converted" THEN 1 ELSE 0 END) as converted_count')
        )
        ->groupBy('month_key', 'month_label')
        ->orderBy('month_key', 'desc')
        ->limit(6)
        ->get()
        ->reverse()
        ->values();

        // ============================================
        // ACTIONABLE PANELS
        // ============================================

        // Panel A: Follow-ups Today & Overdue
        $todayFollowups = LeadFollowup::with(['lead'])
            ->whereDate('next_followup_date', '<=', $today)
            ->whereHas('lead', function($q) {
                $q->where('status', '!=', 'converted');
            })
            ->latest('next_followup_date')
            ->take(10)
            ->get()
            ->map(function($followup) use ($today) {
                $followup->is_overdue = $followup->next_followup_date < $today;
                return $followup;
            });

        // Panel B: Top 10 Due Students
        $topDueStudents = Student::with(['batch', 'branch'])
            ->selectRaw('students.*, (final_fee - (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.student_id = students.id)) as due_amount')
            ->having('due_amount', '>', 0)
            ->orderBy('due_amount', 'desc')
            ->take(10)
            ->get();

        // Panel C: Recent Payments
        $recentPayments = Payment::with(['student.batch'])
            ->latest('payment_date')
            ->take(10)
            ->get();

        // ============================================
        // NOTIFICATIONS COUNT
        // ============================================
        $notificationsCount = $overdueFollowups + ($studentsWithDue > 0 ? 1 : 0);

        return view('dashboard', compact(
            // Summary Cards
            'totalStudents', 'newStudentsThisWeek',
            'activeBatches', 'upcomingBatches', 'completedBatches',
            'feesCollectedThisMonth', 'feesCollectedLastMonth',
            'totalDue', 'studentsWithDue',
            'attendancePercentage', 'presentToday', 'absentToday',
            'followupsToday', 'overdueFollowups', 'convertedThisMonth',
            
            // Chart Data
            'monthlyCollection', 
            'attendanceTrend',
            'leadConversionTrend',
            
            // Actionable Panels
            'todayFollowups',
            'topDueStudents',
            'recentPayments',
            
            // Notifications
            'notificationsCount'
        ));
    }
}

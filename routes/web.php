<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BranchController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('branches', BranchController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class)->only(['index', 'edit', 'update']);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('courses', \App\Http\Controllers\CourseController::class);
    Route::get('batches/{batch}/export', [\App\Http\Controllers\BatchController::class, 'export'])->name('batches.export');
    Route::resource('batches', \App\Http\Controllers\BatchController::class);
    Route::resource('students', \App\Http\Controllers\StudentController::class);
    Route::resource('payments', \App\Http\Controllers\PaymentController::class);
    // Leads Management
    Route::resource('leads', \App\Http\Controllers\LeadController::class);
    Route::get('leads-kanban', [\App\Http\Controllers\LeadController::class, 'kanban'])->name('leads.kanban');
    Route::patch('leads/{lead}/status', [\App\Http\Controllers\LeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::resource('lead_statuses', \App\Http\Controllers\LeadStatusController::class);
    Route::get('leads-followups-board', [\App\Http\Controllers\LeadController::class, 'followupsBoard'])->name('leads.followups-board');
    Route::resource('lead_followups', \App\Http\Controllers\LeadFollowupController::class)->only(['store']);
    Route::post('leads/{lead}/followups', [\App\Http\Controllers\LeadFollowupController::class, 'store'])->name('leads.followups.store');

    // Attendance & Reports
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
    Route::get('reports/attendance-report', [\App\Http\Controllers\ReportController::class, 'attendanceReport'])->name('reports.attendance-report');
    Route::get('reports/student-attendance/{student}', [\App\Http\Controllers\ReportController::class, 'studentAttendanceDetail'])->name('reports.student-attendance');
    
    // Holiday Management
    Route::post('holidays/import', [\App\Http\Controllers\HolidayController::class, 'import'])->name('holidays.import');
    Route::resource('holidays', \App\Http\Controllers\HolidayController::class);

    // Other Reports
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/fee-collection', [\App\Http\Controllers\ReportController::class, 'feeCollection'])->name('reports.fee-collection');
    Route::get('reports/due-report', [\App\Http\Controllers\ReportController::class, 'dueReport'])->name('reports.due-report');
    Route::get('reports/export-fee-collection', [\App\Http\Controllers\ReportController::class, 'exportFeeCollection'])->name('reports.export-fee-collection');
    Route::get('reports/export-attendance-report', [\App\Http\Controllers\ReportController::class, 'exportAttendanceReport'])->name('reports.export-attendance-report');
    Route::get('reports/export-counsellor-report', [\App\Http\Controllers\ReportController::class, 'exportCounsellorReport'])->name('reports.export-counsellor-report');
    Route::get('reports/export-source-report', [\App\Http\Controllers\ReportController::class, 'exportSourceReport'])->name('reports.export-source-report');
    Route::get('reports/export-due-report', [\App\Http\Controllers\ReportController::class, 'exportDueReport'])->name('reports.export-due-report');
    Route::get('reports/counsellor-performance', [\App\Http\Controllers\ReportController::class, 'counsellorReport'])->name('reports.counsellor-performance');
    Route::get('reports/source-report', [\App\Http\Controllers\ReportController::class, 'sourceReport'])->name('reports.source-report');
});

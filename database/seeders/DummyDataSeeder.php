<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing branches and users
        $branches = Branch::all();
        $users = User::where('role_id', '!=', 1)->get(); // Get non-super admin users

        if ($branches->isEmpty()) {
            $this->command->error('No branches found. Please run BranchSeeder first.');
            return;
        }

        // Create Courses
        $this->command->info('Creating courses...');
        $courses = [
            ['name' => 'Full Stack Web Development', 'code' => 'FSWD', 'duration_value' => 6, 'duration_unit' => 'months', 'default_total_fee' => 50000, 'description' => 'Complete web development course covering frontend and backend', 'status' => 'active'],
            ['name' => 'Data Science & Machine Learning', 'code' => 'DSML', 'duration_value' => 8, 'duration_unit' => 'months', 'default_total_fee' => 75000, 'description' => 'Comprehensive data science and ML course', 'status' => 'active'],
            ['name' => 'Digital Marketing', 'code' => 'DM', 'duration_value' => 4, 'duration_unit' => 'months', 'default_total_fee' => 30000, 'description' => 'Complete digital marketing certification', 'status' => 'active'],
            ['name' => 'Python Programming', 'code' => 'PY', 'duration_value' => 3, 'duration_unit' => 'months', 'default_total_fee' => 25000, 'description' => 'Python programming from basics to advanced', 'status' => 'active'],
            ['name' => 'Mobile App Development', 'code' => 'MAD', 'duration_value' => 5, 'duration_unit' => 'months', 'default_total_fee' => 45000, 'description' => 'Android and iOS app development', 'status' => 'active'],
        ];

        foreach ($courses as $courseData) {
            Course::firstOrCreate(['code' => $courseData['code']], $courseData);
        }

        $allCourses = Course::all();
        $this->command->info('Courses created: ' . $allCourses->count());

        // Create Batches
        $this->command->info('Creating batches...');
        $batches = [];
        $statuses = ['upcoming', 'ongoing', 'completed'];
        
        foreach ($allCourses as $index => $course) {
            for ($i = 1; $i <= 3; $i++) {
                $startDate = Carbon::now()->subMonths(rand(1, 6))->addDays(rand(-15, 15));
                $endDate = (clone $startDate)->addMonths($course->duration_value);
                
                $status = $statuses[$i - 1];
                if ($status === 'upcoming') {
                    $startDate = Carbon::now()->addDays(rand(5, 30));
                    $endDate = (clone $startDate)->addMonths($course->duration_value);
                } elseif ($status === 'completed') {
                    $startDate = Carbon::now()->subMonths($course->duration_value + 2);
                    $endDate = Carbon::now()->subMonths(1);
                }

                $batch = Batch::create([
                    'name' => $course->code . ' Batch ' . $i,
                    'branch_id' => $branches->random()->id,
                    'course_id' => $course->id,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'total_fee' => $course->default_total_fee,
                    'capacity' => rand(20, 40),
                    'status' => $status,
                ]);

                // Assign teachers to batches
                if ($users->isNotEmpty()) {
                    $batch->teachers()->attach($users->random(rand(1, min(2, $users->count())))->pluck('id'));
                }

                $batches[] = $batch;
            }
        }

        $this->command->info('Batches created: ' . count($batches));

        // Create Students
        $this->command->info('Creating students...');
        $firstNames = ['Rahul', 'Priya', 'Amit', 'Sneha', 'Vikram', 'Anjali', 'Rohan', 'Kavya', 'Arjun', 'Divya', 'Karan', 'Pooja', 'Sanjay', 'Meera', 'Aditya', 'Riya', 'Nikhil', 'Shreya', 'Varun', 'Ananya'];
        $lastNames = ['Sharma', 'Patel', 'Kumar', 'Singh', 'Reddy', 'Gupta', 'Verma', 'Rao', 'Nair', 'Iyer', 'Joshi', 'Mehta', 'Shah', 'Desai', 'Pillai'];
        
        $studentCount = 0;
        foreach ($batches as $batch) {
            if ($batch->status === 'upcoming') {
                $numStudents = rand(5, 10);
            } else {
                $numStudents = rand(15, min(25, $batch->capacity));
            }

            for ($i = 0; $i < $numStudents; $i++) {
                $studentCount++;
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $name = $firstName . ' ' . $lastName;
                
                $totalFee = $batch->total_fee;
                $discount = rand(0, 1) ? rand(0, 5000) : 0;
                $finalFee = $totalFee - $discount;
                
                $student = Student::create([
                    'roll_number' => 'STU' . str_pad($studentCount, 4, '0', STR_PAD_LEFT),
                    'name' => $name,
                    'mobile' => '9' . rand(100000000, 999999999),
                    'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                    'branch_id' => $batch->branch_id,
                    'course_id' => $batch->course_id,
                    'batch_id' => $batch->id,
                    'status' => $batch->status === 'completed' ? 'placed' : ($batch->status === 'upcoming' ? 'admission_done' : 'ongoing'),
                    'fee_status' => 'unpaid', // Will update after creating payments
                    'current_address' => rand(1, 999) . ', MG Road, Bangalore',
                    'permanent_address' => rand(1, 999) . ', Main Street, Bangalore',
                    'highest_qualification' => ['B.Tech', 'B.Sc', 'BCA', 'M.Tech', 'MCA'][array_rand(['B.Tech', 'B.Sc', 'BCA', 'M.Tech', 'MCA'])],
                    'college_name' => ['ABC College', 'XYZ University', 'PQR Institute'][array_rand(['ABC College', 'XYZ University', 'PQR Institute'])],
                    'percentage' => rand(60, 95),
                    'parent_type' => ['Father', 'Mother', 'Guardian'][array_rand(['Father', 'Mother', 'Guardian'])],
                    'parent_name' => $lastNames[array_rand($lastNames)] . ' ' . $firstName,
                    'parent_mobile' => '9' . rand(100000000, 999999999),
                    'parent_email' => null,
                    'total_fee' => $totalFee,
                    'discount' => $discount,
                    'final_fee' => $finalFee,
                    'payment_type' => rand(0, 1) ? 'full' : 'installments',
                    'notes' => null,
                ]);

                // Create Payments for students
                $paidPercentage = rand(0, 100);
                if ($paidPercentage > 20) { // 80% students have made some payment
                    $numPayments = rand(1, 3);
                    $totalPaid = 0;
                    
                    for ($p = 0; $p < $numPayments; $p++) {
                        $remainingAmount = $finalFee - $totalPaid;
                        if ($remainingAmount <= 0) break;
                        
                        if ($p === $numPayments - 1 && $paidPercentage > 70) {
                            // Last payment - pay remaining
                            $amount = $remainingAmount;
                        } else {
                            $amount = rand(5000, min($remainingAmount, $finalFee / 2));
                        }
                        
                        $paymentDate = Carbon::parse($batch->start_date)->addDays(rand(-30, 60));
                        
                        Payment::create([
                            'student_id' => $student->id,
                            'amount' => $amount,
                            'payment_date' => $paymentDate->format('Y-m-d'),
                            'payment_mode' => ['cash', 'upi', 'card', 'bank_transfer'][array_rand(['cash', 'upi', 'card', 'bank_transfer'])],
                            'receipt_no' => 'RCP' . str_pad(($studentCount * 10 + $p), 6, '0', STR_PAD_LEFT),
                            'notes' => null,
                            'collected_by' => $users->isNotEmpty() ? $users->random()->id : null,
                        ]);
                        
                        $totalPaid += $amount;
                    }
                    
                    // Update fee status
                    if ($totalPaid >= $finalFee) {
                        $student->update(['fee_status' => 'fully_paid']);
                    } elseif ($totalPaid > 0) {
                        $student->update(['fee_status' => 'partial']);
                    }
                }

                // Create Attendance for ongoing batches
                if ($batch->status === 'ongoing') {
                    $daysToMark = rand(10, 30);
                    for ($d = 0; $d < $daysToMark; $d++) {
                        $attendanceDate = Carbon::parse($batch->start_date)->addDays($d);
                        if ($attendanceDate->isFuture()) break;
                        
                        Attendance::create([
                            'student_id' => $student->id,
                            'batch_id' => $batch->id,
                            'date' => $attendanceDate->format('Y-m-d'),
                            'status' => rand(0, 100) > 15 ? 'present' : 'absent', // 85% attendance
                            'marked_by' => $users->isNotEmpty() ? $users->random()->id : null,
                        ]);
                    }
                }
            }
        }

        $this->command->info('Students created: ' . $studentCount);

        // Create Leads
        $this->command->info('Creating leads...');
        $leadStatuses = ['new', 'contacted', 'counselling_done', 'converted', 'lost'];
        $sources = ['website', 'referral', 'walk_in', 'social_media', 'advertisement'];
        
        for ($i = 1; $i <= 50; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            $status = $leadStatuses[array_rand($leadStatuses)];
            
            // Select 1-3 random courses for interested_courses
            $interestedCourses = $allCourses->random(rand(1, 3))->pluck('id')->toArray();
            
            $lead = Lead::create([
                'name' => $name,
                'phone' => '9' . rand(100000000, 999999999),
                'email' => strtolower(str_replace(' ', '.', $name)) . rand(1, 999) . '@example.com',
                'interested_courses' => json_encode($interestedCourses),
                'preferred_branch_id' => $branches->random()->id,
                'source' => $sources[array_rand($sources)],
                'status' => $status,
                'assigned_counsellor_id' => $users->isNotEmpty() ? $users->random()->id : null,
                'next_followup_date' => $status !== 'converted' && $status !== 'lost' ? Carbon::now()->addDays(rand(-5, 10))->format('Y-m-d') : null,
            ]);

            // Create Follow-ups for non-converted leads
            if ($status !== 'converted' && $status !== 'lost') {
                $numFollowups = rand(1, 2);
                for ($f = 0; $f < $numFollowups; $f++) {
                    $followupDate = Carbon::now()->addDays(rand(-10, -1));
                    
                    LeadFollowup::create([
                        'lead_id' => $lead->id,
                        'outcome' => ['Connected', 'No Answer', 'Busy', 'Interested'][array_rand(['Connected', 'No Answer', 'Busy', 'Interested'])],
                        'notes' => 'Follow-up call completed on ' . $followupDate->format('Y-m-d'),
                        'next_followup_date' => $followupDate->addDays(rand(3, 7))->format('Y-m-d'),
                        'created_by' => $users->isNotEmpty() ? $users->random()->id : null,
                    ]);
                }
            }
        }

        $this->command->info('Leads created: 50');
        $this->command->info('✅ Dummy data seeding completed successfully!');
    }
}

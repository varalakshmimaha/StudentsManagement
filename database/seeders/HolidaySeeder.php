<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = date('Y');

        $holidays = [
            ['name' => 'New Year Day', 'date' => "$currentYear-01-01", 'type' => 'General', 'is_recurring' => true],
            ['name' => 'Republic Day', 'date' => "$currentYear-01-26", 'type' => 'General', 'is_recurring' => true],
            ['name' => 'Independence Day', 'date' => "$currentYear-08-15", 'type' => 'General', 'is_recurring' => true],
            ['name' => 'Gandhi Jayanti', 'date' => "$currentYear-10-02", 'type' => 'General', 'is_recurring' => true],
            ['name' => 'Christmas', 'date' => "$currentYear-12-25", 'type' => 'General', 'is_recurring' => true],
            ['name' => 'Annual Day', 'date' => Carbon::now()->addDays(5)->format('Y-m-d'), 'type' => 'General', 'is_recurring' => false],
        ];

        foreach ($holidays as $h) {
            Holiday::updateOrCreate(
                ['name' => $h['name'], 'date' => $h['date']],
                [
                    'type' => $h['type'],
                    'is_recurring' => $h['is_recurring'],
                    'month_day' => $h['is_recurring'] ? Carbon::parse($h['date'])->format('m-d') : null,
                    'is_active' => true,
                ]
            );
        }
    }
}

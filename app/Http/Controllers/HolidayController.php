<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $query = Holiday::with('branch');

        if ($request->year) {
            $query->whereYear('date', $request->year);
        }
        if ($request->month) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }

        $holidays = $query->orderBy('date', 'desc')->paginate(20);
        $branches = Branch::all();
        
        return view('holidays.index', compact('holidays', 'branches'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('holidays.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'branch_id' => 'nullable|exists:branches,id',
            'type' => 'required|in:General,Branch Specific',
            'is_recurring' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->has('is_recurring') && $request->is_recurring) {
            $date = Carbon::parse($validated['date']);
            $validated['month_day'] = $date->format('m-d');
        }

        Holiday::create($validated);

        return redirect()->route('holidays.index')->with('success', 'Holiday created successfully.');
    }

    public function edit(Holiday $holiday)
    {
        $branches = Branch::all();
        return view('holidays.edit', compact('holiday', 'branches'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'branch_id' => 'nullable|exists:branches,id',
            'type' => 'required|in:General,Branch Specific',
            'is_recurring' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->has('is_recurring') && $request->is_recurring) {
            $date = Carbon::parse($validated['date']);
            $validated['month_day'] = $date->format('m-d');
        } else {
            $validated['month_day'] = null;
        }

        $holiday->update($validated);

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        fgetcsv($handle);

        $count = 0;
        while (($data = fgetcsv($handle)) !== FALSE) {
            // date,title,type,branch(optional),is_recurring(optional)
            if (count($data) >= 2) {
                $date = Carbon::parse($data[0]);
                Holiday::create([
                    'date' => $data[0],
                    'name' => $data[1],
                    'type' => $data[2] ?? 'General',
                    'branch_id' => !empty($data[3]) ? $data[3] : null,
                    'is_recurring' => isset($data[4]) ? (bool)$data[4] : false,
                    'month_day' => (isset($data[4]) && $data[4]) ? $date->format('m-d') : null,
                ]);
                $count++;
            }
        }
        fclose($handle);

        return redirect()->route('holidays.index')->with('success', "$count holidays imported successfully.");
    }
}

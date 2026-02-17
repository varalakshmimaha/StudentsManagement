<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Payment::with(['student', 'collectedBy']);

        if ($request->has('search') && $request->search != '') {
             $query->where('receipt_no', 'like', '%' . $request->search . '%')
                   ->orWhereHas('student', function($q) use ($request) {
                       $q->where('name', 'like', '%' . $request->search . '%')
                         ->orWhere('roll_number', 'like', '%' . $request->search . '%');
                   });
        }
        
        if ($request->has('payment_mode') && $request->payment_mode != '') {
            $query->where('payment_mode', $request->payment_mode);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest('payment_date')->paginate(10);
        
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // For larger datasets, this should be AJAX. Here we load all active or pending students.
        $students = \App\Models\Student::whereIn('status', ['admission_done', 'ongoing'])
            ->orderBy('name')
            ->get();
            
        return view('payments.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string',
            'transaction_ref' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $student = \App\Models\Student::findOrFail($validated['student_id']);
        
        // Generate Receipt No
        $receiptNo = 'REC-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        $payment = new \App\Models\Payment($validated);
        $payment->receipt_no = $receiptNo;
        $payment->collected_by = auth()->id();
        $payment->status = 'completed'; 
        
        $payment->save();

        // Update Student Paid Amount and Fee Status
        // We assume 'received_amount' or similar field tracks total paid on student, 
        // OR we sum up payments. Let's sum up payments for accuracy.
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
        
        // Calculate status
        $feeStatus = 'unpaid';
        if ($totalPaid >= $student->final_fee) {
            $feeStatus = 'fully_paid';
        } elseif ($totalPaid > 0) {
            $feeStatus = 'partial';
        }
        
        $student->update([
            'fee_status' => $feeStatus
        ]);

        return redirect()->route('payments.show', $payment->id)->with('success', 'Payment recorded successfully.');
    }

    // ...

    public function update(Request $request, string $id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        
         $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string',
            'transaction_ref' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);
        
        $payment->update($validated);
        
        // Recalculate fee status
        $student = $payment->student;
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
        
        $feeStatus = 'unpaid';
        if ($totalPaid >= $student->final_fee) {
            $feeStatus = 'fully_paid';
        } elseif ($totalPaid > 0) {
            $feeStatus = 'partial';
        }
        
        $student->update([
            'fee_status' => $feeStatus
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        $student = $payment->student;
        
        $payment->delete();

        // Recalculate fee status
        $totalPaid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
        
        $feeStatus = 'unpaid';
        if ($totalPaid >= $student->final_fee) {
            $feeStatus = 'fully_paid';
        } elseif ($totalPaid > 0) {
            $feeStatus = 'partial';
        }
        
        $student->update([
            'fee_status' => $feeStatus
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment deleted and status updated.');
    }
}

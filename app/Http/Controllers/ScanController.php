<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index() {
        $scans = Scan::with('user')->get();
        return view('scans.index', compact('scans'));
    }

    public function show(Scan $scan) {
        $scan->load('user');
        return view('scans.show', compact('scan'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'type' => 'required|in:pay,order,product',
            'payload' => 'required|array',
            'generated_by' => 'required|exists:users,id',
            'expires_at' => 'nullable|date',
        ]);
        Scan::create($validated);
        return redirect()->route('scans.index')->with('success', 'Scan created successfully.');
    }

    public function destroy(Scan $scan) {
        $scan->delete();
        return redirect()->route('scans.index')->with('success', 'Scan deleted successfully.');
    }
}

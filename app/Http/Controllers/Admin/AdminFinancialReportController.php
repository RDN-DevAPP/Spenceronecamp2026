<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminFinancialReportController extends Controller
{
    public function index(): View
    {
        $reports = FinancialReport::latest()->get();
        return view('admin.financial-reports.index', compact('reports'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:5120',
            'is_active' => 'nullable'
        ]);

        $data = [
            'title' => $request->title,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('financial_reports', 'public');
        }

        FinancialReport::create($data);

        return redirect()->route('admin.financial-reports.index')
            ->with('success', 'Laporan keuangan berhasil diunggah.');
    }

    public function update(Request $request, FinancialReport $financialReport): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:5120',
            'is_active' => 'nullable'
        ]);

        $financialReport->title = $request->title;
        $financialReport->is_active = $request->has('is_active');

        if ($request->hasFile('file')) {
            if ($financialReport->file_path) {
                Storage::disk('public')->delete($financialReport->file_path);
            }
            $financialReport->file_path = $request->file('file')->store('financial_reports', 'public');
        }

        $financialReport->save();

        return redirect()->route('admin.financial-reports.index')
            ->with('success', 'Laporan keuangan berhasil diperbarui.');
    }

    public function destroy(FinancialReport $financialReport): RedirectResponse
    {
        if ($financialReport->file_path) {
            Storage::disk('public')->delete($financialReport->file_path);
        }
        $financialReport->delete();

        return redirect()->route('admin.financial-reports.index')
            ->with('success', 'Laporan keuangan berhasil dihapus.');
    }
}

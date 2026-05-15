<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\FuzzyIndicator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminFuzzyIndicatorController extends Controller
{
    public function index(Request $request): View
    {
        $query = FuzzyIndicator::query();
        if ($q = $request->get('q')) {
            $query->where(function ($qq) use ($q) {
                $qq->where('normal_indicator', 'like', "%$q%")
                   ->orWhere('keyword_variation', 'like', "%$q%");
            });
        }

        return view('admin.indicators.index', [
            'indicators' => $query->orderBy('normal_indicator')->paginate(20)->withQueryString(),
            'q' => $q,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }

    public function create(): View
    {
        return view('admin.indicators.form', [
            'indicator' => new FuzzyIndicator(['risk_weight' => 10, 'is_active' => true]),
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        FuzzyIndicator::create($data);
        return redirect()->route('admin.indicators.index')->with('status', 'Indikator berhasil ditambah.');
    }

    public function edit(FuzzyIndicator $indicator): View
    {
        return view('admin.indicators.form', [
            'indicator' => $indicator,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }

    public function update(Request $request, FuzzyIndicator $indicator): RedirectResponse
    {
        $data = $this->validateData($request);
        $indicator->update($data);
        return redirect()->route('admin.indicators.index')->with('status', 'Indikator berhasil diperbarui.');
    }

    public function destroy(FuzzyIndicator $indicator): RedirectResponse
    {
        $indicator->delete();
        return back()->with('status', 'Indikator berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'normal_indicator' => ['required', 'string', 'max:120'],
            'keyword_variation' => ['required', 'string', 'max:180'],
            'relevant_category' => ['nullable', 'string', 'max:120'],
            'risk_weight' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => (bool) $request->boolean('is_active', true)];
    }
}

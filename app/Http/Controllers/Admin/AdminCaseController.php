<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCaseController extends Controller
{
    public function index(Request $request): View
    {
        $query = CyberCase::query();
        if ($cat = $request->get('category')) $query->where('category', $cat);
        if ($q = $request->get('q')) {
            $query->where(function ($qq) use ($q) {
                $qq->where('scenario_text', 'like', "%$q%")
                   ->orWhere('case_code', 'like', "%$q%");
            });
        }

        return view('admin.cases.index', [
            'cases' => $query->orderBy('case_code')->paginate(15)->withQueryString(),
            'categoryMap' => CyberCase::categoryMap(),
            'activeCategory' => $cat,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        return view('admin.cases.form', [
            'case' => new CyberCase(['ideal_indicators' => []]),
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $this->saveCase(new CyberCase(), $data);

        return redirect()->route('admin.cases.index')->with('status', 'Kasus berhasil ditambahkan.');
    }

    public function edit(CyberCase $case): View
    {
        $case->load('options');
        return view('admin.cases.form', [
            'case' => $case,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }

    public function update(Request $request, CyberCase $case): RedirectResponse
    {
        $data = $this->validateData($request, $case->id);
        $this->saveCase($case, $data);

        return redirect()->route('admin.cases.index')->with('status', 'Kasus berhasil diperbarui.');
    }

    public function destroy(CyberCase $case): RedirectResponse
    {
        $case->is_active = ! $case->is_active;
        $case->save();

        return back()->with('status', 'Status kasus diubah menjadi '.($case->is_active ? 'aktif' : 'nonaktif').'.');
    }

    protected function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'case_code' => ['required', 'string', 'max:50', 'unique:cyber_cases,case_code'.($id ? ",$id" : '')],
            'channel' => ['required', 'string', 'max:50'],
            'category' => ['required', 'string', 'max:50'],
            'category_name' => ['required', 'string', 'max:100'],
            'scenario_text' => ['required', 'string'],
            'risk_label' => ['required', 'in:aman,mencurigakan,berbahaya'],
            'difficulty_level' => ['required', 'in:mudah,sedang,sulit'],
            'risk_score_rule' => ['nullable', 'integer'],
            'correct_action' => ['required', 'string'],
            'tutor_feedback' => ['required', 'string'],
            'source_basis' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'indicators' => ['nullable', 'array'],
            'indicators.*.name' => ['required_with:indicators', 'string'],
            'indicators.*.weight' => ['required_with:indicators', 'integer', 'min:1'],
            'options' => ['required', 'array', 'min:2'],
            'options.*.option_text' => ['required', 'string'],
            'options.*.is_correct' => ['nullable', 'boolean'],
            'correct_option' => ['required', 'integer'],
        ]);
    }

    protected function saveCase(CyberCase $case, array $data): void
    {
        DB::transaction(function () use ($case, $data) {
            $indicators = collect($data['indicators'] ?? [])
                ->map(fn($i) => ['name' => $i['name'], 'weight' => (int) $i['weight']])
                ->values()->all();

            $case->fill([
                'case_code' => $data['case_code'],
                'channel' => $data['channel'],
                'category' => $data['category'],
                'category_name' => $data['category_name'],
                'scenario_text' => $data['scenario_text'],
                'risk_label' => $data['risk_label'],
                'difficulty_level' => $data['difficulty_level'],
                'risk_score_rule' => (int) ($data['risk_score_rule'] ?? 0),
                'ideal_indicators' => $indicators,
                'correct_action' => $data['correct_action'],
                'tutor_feedback' => $data['tutor_feedback'],
                'source_basis' => $data['source_basis'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? true),
            ])->save();

            $case->options()->delete();
            foreach ($data['options'] as $idx => $opt) {
                $case->options()->create([
                    'option_text' => $opt['option_text'],
                    'is_correct' => ((int) $data['correct_option']) === $idx,
                ]);
            }
        });
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\LearningMaterial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminMaterialController extends Controller
{
    public function index(Request $request): View
    {
        $query = LearningMaterial::query();
        if ($cat = $request->get('category')) $query->where('category', $cat);

        return view('admin.materials.index', [
            'materials' => $query->orderBy('category')->orderBy('title')->paginate(15)->withQueryString(),
            'categoryMap' => CyberCase::categoryMap(),
            'activeCategory' => $cat,
        ]);
    }

    public function create(): View
    {
        return view('admin.materials.form', [
            'material' => new LearningMaterial(['target_level' => 'all', 'is_active' => true]),
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = Str::slug($data['title']).'-'.Str::random(5);
        LearningMaterial::create($data);
        return redirect()->route('admin.materials.index')->with('status', 'Materi berhasil ditambah.');
    }

    public function edit(LearningMaterial $material): View
    {
        return view('admin.materials.form', [
            'material' => $material,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }

    public function update(Request $request, LearningMaterial $material): RedirectResponse
    {
        $data = $this->validateData($request);
        $material->update($data);
        return redirect()->route('admin.materials.index')->with('status', 'Materi berhasil diperbarui.');
    }

    public function destroy(LearningMaterial $material): RedirectResponse
    {
        $material->delete();
        return back()->with('status', 'Materi dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'category' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:180'],
            'summary' => ['required', 'string'],
            'content' => ['required', 'string'],
            'target_level' => ['required', 'in:beginner,intermediate,advanced,all'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => (bool) $request->boolean('is_active', true)];
    }
}

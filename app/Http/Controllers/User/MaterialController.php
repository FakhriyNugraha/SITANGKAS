<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\LearningMaterial;
use App\Models\MaterialView;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->get('category');
        $query = LearningMaterial::where('is_active', true);
        if ($category) $query->where('category', $category);

        return view('user.materials.index', [
            'materials' => $query->orderBy('category')->orderBy('title')->paginate(12)->withQueryString(),
            'categoryMap' => CyberCase::categoryMap(),
            'activeCategory' => $category,
        ]);
    }

    public function show(Request $request, LearningMaterial $material): View
    {
        abort_unless($material->is_active, 404);

        MaterialView::create([
            'user_id' => $request->user()->id,
            'learning_material_id' => $material->id,
            'viewed_at' => now(),
        ]);

        return view('user.materials.show', [
            'material' => $material,
            'categoryMap' => CyberCase::categoryMap(),
        ]);
    }
}

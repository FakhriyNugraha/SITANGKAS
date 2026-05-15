<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnnTrainingProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminKnnTrainingController extends Controller
{
    public function index(Request $request): View
    {
        $query = KnnTrainingProfile::query();
        if ($level = $request->get('level')) $query->where('awareness_level', $level);

        $distribution = KnnTrainingProfile::selectRaw('awareness_level, COUNT(*) as total')
            ->groupBy('awareness_level')
            ->pluck('total', 'awareness_level')->toArray();

        return view('admin.knn.index', [
            'profiles' => $query->orderBy('profile_code')->paginate(20)->withQueryString(),
            'distribution' => $distribution,
            'activeLevel' => $level,
        ]);
    }

    public function create(): View
    {
        return view('admin.knn.form', [
            'profile' => new KnnTrainingProfile(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        KnnTrainingProfile::create($data);
        return redirect()->route('admin.knn.index')->with('status', 'Data training KNN ditambah.');
    }

    public function edit(KnnTrainingProfile $knn): View
    {
        return view('admin.knn.form', ['profile' => $knn]);
    }

    public function update(Request $request, KnnTrainingProfile $knn): RedirectResponse
    {
        $data = $this->validateData($request, $knn->id);
        $knn->update($data);
        return redirect()->route('admin.knn.index')->with('status', 'Data training diperbarui.');
    }

    public function destroy(KnnTrainingProfile $knn): RedirectResponse
    {
        $knn->delete();
        return back()->with('status', 'Data training dihapus.');
    }

    protected function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'profile_code' => ['required', 'string', 'max:50', 'unique:knn_training_profiles,profile_code'.($id ? ",$id" : '')],
            'phishing_score' => ['required', 'integer', 'min:0', 'max:100'],
            'otp_score' => ['required', 'integer', 'min:0', 'max:100'],
            'password_score' => ['required', 'integer', 'min:0', 'max:100'],
            'marketplace_score' => ['required', 'integer', 'min:0', 'max:100'],
            'pinjol_score' => ['required', 'integer', 'min:0', 'max:100'],
            'wrong_count' => ['required', 'integer', 'min:0'],
            'avg_time_seconds' => ['required', 'integer', 'min:0'],
            'help_opened_count' => ['required', 'integer', 'min:0'],
            'awareness_level' => ['required', 'in:beginner,intermediate,advanced'],
        ]);
    }
}

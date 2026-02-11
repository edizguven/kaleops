<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('jobFiles')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('job_no', 'like', '%' . $q . '%')
                    ->orWhere('title', 'like', '%' . $q . '%');
            });
        }

        $jobs = $query->get();
        $showAssignStations = Schema::hasColumn('jobs', 'assign_cam');
        return view('admin.jobs.index', compact('jobs', 'showAssignStations'));
    }

    /**
     * Yeni iş emri: iki alan (Teknik Dosya + Teknik Çizim, Excel vb.), her birinde Ekle ile çoklu dosya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:999999',
            'teknik_dosya' => 'nullable|array',
            'teknik_dosya.*' => 'file|max:51200',
            'teknik_cizim' => 'nullable|array',
            'teknik_cizim.*' => 'file|max:51200',
        ]);

        $hasAssignColumns = Schema::hasColumn('jobs', 'assign_cam');
        $hasTorna = Schema::hasColumn('jobs', 'assign_torna');
        $assignCam = $request->boolean('assign_cam');
        $assignLazer = $request->boolean('assign_lazer');
        $assignCmm = $request->boolean('assign_cmm');
        $assignTesviye = $request->boolean('assign_tesviye');
        $assignTorna = $request->boolean('assign_torna');
        $anyStation = $assignCam || $assignLazer || $assignCmm || $assignTesviye || ($hasTorna && $assignTorna);
        if ($hasAssignColumns && !$anyStation) {
            return back()->withErrors(['assign_stations' => 'En az bir istasyon seçmelisiniz (CAM, Lazer, CMM, Tesviye veya Torna).'])->withInput();
        }

        $teknikDosya = $request->file('teknik_dosya') ?? [];
        $teknikCizim = $request->file('teknik_cizim') ?? [];
        $allFiles = array_merge($teknikDosya, $teknikCizim);

        if (empty($allFiles)) {
            return back()->withErrors(['teknik_dosya' => 'En az bir dosya yükleyin (Teknik Dosya veya Teknik Çizim alanından).'])->withInput();
        }

        try {
            $jobNo = 'JOB-' . strtoupper(Str::random(6));

            $createData = [
                'job_no' => $jobNo,
                'title' => $request->title,
                'quantity' => (int) $request->quantity,
                'image_path' => null,
                'pdf_path' => null,
                'current_stage' => 'multi',
                'is_completed' => false,
            ];
            if ($hasAssignColumns) {
                $createData['assign_cam'] = $assignCam;
                $createData['assign_lazer'] = $assignLazer;
                $createData['assign_cmm'] = $assignCmm;
                $createData['assign_tesviye'] = $assignTesviye;
            }
            if ($hasTorna) {
                $createData['assign_torna'] = $assignTorna;
            }
            $job = Job::create($createData);

            foreach ($allFiles as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                $path = $file->store('jobs/files', 'public');
                $job->jobFiles()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }

            return back()->with('success', "İş emri ($jobNo) başarıyla oluşturuldu.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Kayıt sırasında hata oluştu: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Job $job)
    {
        $job->load(['jobFiles', 'jobStationDetails']);
        return view('admin.jobs.show', compact('job'));
    }

    /**
     * İş emrine yeni dosya(lar) ekle
     */
    public function storeFiles(Request $request, Job $job)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:51200',
        ]);

        try {
            foreach ($request->file('files') as $file) {
                $path = $file->store('jobs/files', 'public');
                $job->jobFiles()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
            $count = count($request->file('files'));
            return back()->with('success', $count . ' dosya eklendi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Yükleme hatası: ' . $e->getMessage());
        }
    }

    /**
     * Tek dosyayı sil (admin)
     */
    public function destroyFile(Job $job, JobFile $file)
    {
        if ($file->job_id !== $job->id) {
            abort(404);
        }
        try {
            $file->deleteFileFromStorage();
            $file->delete();
            return back()->with('success', 'Dosya silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Silme hatası: ' . $e->getMessage());
        }
    }

    /**
     * Dosyayı indir (PC/telefona indirilebilir, tüm giriş yapmış kullanıcılar)
     */
    public function downloadFile(JobFile $file)
    {
        $path = $file->file_path;
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }
        $fullPath = Storage::disk('public')->path($path);
        return response()->download($fullPath, $file->original_name);
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        try {
            $job->update(['title' => $request->title]);
            return back()->with('success', 'İş emri başarıyla güncellendi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Güncelleme hatası: ' . $e->getMessage());
        }
    }

    public function destroy(Job $job)
    {
        try {
            foreach ($job->jobFiles as $f) {
                $f->deleteFileFromStorage();
            }
            $job->jobFiles()->delete();
            $job->delete();
            return back()->with('success', 'İş emri silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Silme işlemi başarısız: ' . $e->getMessage());
        }
    }
}

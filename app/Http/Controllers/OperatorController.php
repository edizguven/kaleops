<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Job;

class OperatorController extends Controller
{
    /**
     * Personelin Kendi İşlerini Listelemesi
     * Sıra yok: iş emri girildiğinde herkese aynı anda ulaşır. Her istasyon kendi alanı boş olan işleri görür.
     */
    public function index()
    {
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'manager'])) {
            return redirect()->route('admin.jobs.index');
        }

        if (empty($user->role) || !in_array($user->role, ['cam', 'lazer', 'cmm', 'tesviye', 'planning', 'packaging', 'logistics', 'accounting'])) {
            return redirect()->route('dashboard')->with('error', 'Geçerli bir operatör rolünüz bulunmamaktadır.');
        }

        $query = Job::where('is_completed', false)->orderBy('created_at', 'asc');
        $hasAssignColumns = Schema::hasColumn('jobs', 'assign_cam');

        switch ($user->role) {
            case 'cam':
                if ($hasAssignColumns) {
                    $query->where('assign_cam', true)->whereNull('cam_minutes');
                } else {
                    $query->whereNull('cam_minutes');
                }
                break;
            case 'lazer':
                if ($hasAssignColumns) {
                    $query->where('assign_lazer', true)->whereNull('lazer_minutes');
                } else {
                    $query->whereNull('lazer_minutes');
                }
                break;
            case 'cmm':
                if ($hasAssignColumns) {
                    $query->where('assign_cmm', true)->whereNull('cmm_minutes');
                } else {
                    $query->whereNull('cmm_minutes');
                }
                break;
            case 'tesviye':
                if ($hasAssignColumns) {
                    $query->where('assign_tesviye', true)->whereNull('tesviye_minutes');
                } else {
                    $query->whereNull('tesviye_minutes');
                }
                break;
            case 'planning':
                if ($hasAssignColumns) {
                    $query->whereNull('planning_date')
                        ->where(function ($q) {
                            $q->where('assign_cam', false)->orWhereNotNull('cam_minutes');
                        })
                        ->where(function ($q) {
                            $q->where('assign_lazer', false)->orWhereNotNull('lazer_minutes');
                        })
                        ->where(function ($q) {
                            $q->where('assign_cmm', false)->orWhereNotNull('cmm_minutes');
                        })
                        ->where(function ($q) {
                            $q->where('assign_tesviye', false)->orWhereNotNull('tesviye_minutes');
                        });
                } else {
                    $query->whereNotNull('cam_minutes')
                        ->whereNotNull('lazer_minutes')
                        ->whereNotNull('cmm_minutes')
                        ->whereNotNull('tesviye_minutes')
                        ->whereNull('planning_date');
                }
                break;
            case 'packaging':
                $query->whereNotNull('planning_date')->whereNull('packaging_type');
                break;
            case 'logistics':
                $query->whereNotNull('packaging_type')->whereNull('delivery_note_path');
                break;
            case 'accounting':
                $query->whereNotNull('delivery_note_path');
                break;
            default:
                $query->whereRaw('1 = 0');
        }

        $jobs = $query->with('jobFiles')->get();

        return view('operator.index', compact('jobs'));
    }

    /**
     * İşlemi Tamamla ve Bir Sonraki Aşamaya Gönder
     */
    public function update(Request $request, Job $job)
    {
        $user = auth()->user();

        // Güvenlik kontrolleri
        if (empty($user->role)) {
            return back()->with('error', 'Geçerli bir rolünüz bulunmamaktadır.');
        }

        if ($job->is_completed) {
            return back()->with('error', 'Bu iş emri zaten tamamlanmış ve kapatılmıştır.');
        }

        // Güvenlik: Bu istasyon bu işe atanmış mı ve verisi henüz girilmemiş mi?
        $canEdit = match ($user->role) {
            'cam' => ($job->getAttribute('assign_cam') ?? true) && is_null($job->cam_minutes),
            'lazer' => ($job->getAttribute('assign_lazer') ?? true) && is_null($job->lazer_minutes),
            'cmm' => ($job->getAttribute('assign_cmm') ?? true) && is_null($job->cmm_minutes),
            'tesviye' => ($job->getAttribute('assign_tesviye') ?? true) && is_null($job->tesviye_minutes),
            'planning' => is_null($job->planning_date) && $job->hasAllAssignedProductionStationsFilled(),
            'packaging' => is_null($job->packaging_type) && $job->planning_date !== null,
            'logistics' => is_null($job->delivery_note_path) && $job->packaging_type !== null,
            'accounting' => $job->delivery_note_path !== null,
            default => false,
        };
        if (!$canEdit) {
            return back()->with('error', 'Bu iş için sizin istasyon veriniz zaten girilmiş veya sıra sizde değil.');
        }

        // 1. CAM
        if ($user->role === 'cam') {
            $request->validate(['cam_minutes' => 'required|integer|min:1']);
            $job->update(['cam_minutes' => $request->cam_minutes]);
            return back()->with('success', 'CAM süresi kaydedildi.');
        }

        // 2. LAZER
        if ($user->role === 'lazer') {
            $request->validate(['lazer_minutes' => 'required|integer|min:1']);
            $job->update(['lazer_minutes' => $request->lazer_minutes]);
            return back()->with('success', 'Lazer süresi kaydedildi.');
        }

        // 3. CMM
        if ($user->role === 'cmm') {
            $request->validate(['cmm_minutes' => 'required|integer|min:1']);
            $job->update(['cmm_minutes' => $request->cmm_minutes]);
            return back()->with('success', 'CMM süresi kaydedildi.');
        }

        // 4. TESVİYE
        if ($user->role === 'tesviye') {
            $request->validate(['tesviye_minutes' => 'required|integer|min:1']);
            $job->update(['tesviye_minutes' => $request->tesviye_minutes]);
            return back()->with('success', 'Tesviye süresi kaydedildi.');
        }

        // 5. PLANLAMA (Planning): Tarih + opsiyonel iş günü (eklenecek gün sayısı)
        if ($user->role === 'planning') {
            $request->validate([
                'planning_date' => 'required|date',
                'extra_days' => 'nullable|integer|min:0|max:365',
            ]);
            $date = Carbon::parse($request->planning_date);
            $extraDays = (int) $request->get('extra_days', 0);
            if ($extraDays > 0) {
                $date->addDays($extraDays);
            }
            $job->update(['planning_date' => $date->format('Y-m-d')]);
            return back()->with('success', 'Termin tarihi kaydedildi.' . ($extraDays > 0 ? " (+{$extraDays} iş günü eklendi)" : ''));
        }

        // 6. PAKETLEME
        if ($user->role === 'packaging') {
            $request->validate(['packaging_type' => 'required|in:Kucuk,Orta,Buyuk']);
            $job->update(['packaging_type' => $request->packaging_type]);
            return back()->with('success', 'Paket seçimi kaydedildi.');
        }

        // 7. İRSALİYE / LOJİSTİK
        if ($user->role === 'logistics') {
            $request->validate(['delivery_note' => 'required|file|max:10240']);
            try {
                $path = $request->file('delivery_note')->store('jobs/delivery_notes', 'public');
                $job->update(['delivery_note_path' => $path]);
                return back()->with('success', 'İrsaliye yüklendi.');
            } catch (\Exception $e) {
                return back()->with('error', 'Dosya yükleme hatası: ' . $e->getMessage());
            }
        }

        // 8. MUHASEBE (Accounting) - SON DURAK
        if ($user->role === 'accounting') {
            $request->validate([
                'invoice' => 'required|file|max:10240',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'required|numeric|min:0',
                'payment_status' => 'required|in:paid,unpaid,partial'
            ]);

            // Ödenen tutar toplam tutardan fazla olamaz
            if ($request->paid_amount > $request->total_amount) {
                return back()->withErrors(['paid_amount' => 'Ödenen tutar toplam tutardan fazla olamaz.'])->withInput();
            }

            try {
                $path = $request->file('invoice')->store('jobs/invoices', 'public');

                $job->update([
                    'invoice_path' => $path,
                    'total_amount' => $request->total_amount,
                    'paid_amount' => $request->paid_amount,
                    'payment_status' => $request->payment_status,
                    'current_stage' => 'completed',
                    'is_completed' => true,
                ]);
                return back()->with('success', 'Muhasebe işlemleri tamamlandı. İŞ EMRİ KAPATILDI. 🚀');
            } catch (\Exception $e) {
                return back()->with('error', 'Dosya yükleme hatası: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'İşlem tanımlanamadı.');
    }
}
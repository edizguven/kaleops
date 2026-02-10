<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('İş Detay Raporu') }} : <span class="font-mono text-blue-600">{{ $job->job_no }}</span>
            </h2>
            <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm font-bold">
                &larr; Listeye Dön
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded-xl shadow-md border-l-8 {{ $job->is_completed ? 'border-green-500' : 'border-yellow-500' }}">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $job->title }}</h3>
                        <p class="text-gray-500 text-sm mt-1">Oluşturulma Tarihi: {{ $job->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs font-bold text-gray-400 uppercase">Durum</span>
                        @if($job->is_completed)
                            <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 font-bold text-sm inline-block mt-1">
                                ✅ TAMAMLANDI
                            </span>
                        @else
                            <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-bold text-sm inline-block mt-1">
                                ⏳ İŞLEMDE
                            </span>
                        @endif
                        <span class="block text-xs font-bold text-gray-500 mt-2">Adet: {{ $job->quantity ?? 1 }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">📂 Dosyalar</h4>
                <div class="space-y-3 mb-6">
                    @forelse($job->jobFiles as $f)
                        <div class="flex items-center justify-between gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <a href="{{ route('jobfile.download', $f) }}" class="flex items-center gap-2 text-blue-700 font-bold hover:underline" download>
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                {{ $f->original_name }}
                            </a>
                            <form action="{{ route('admin.jobs.files.destroy', [$job, $f]) }}" method="POST" class="inline" onsubmit="return confirm('Bu dosyayı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded text-sm font-bold hover:bg-red-200">Sil</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-500 italic">Henüz dosya yok.</p>
                    @endforelse
                </div>
                <form action="{{ route('admin.jobs.files.store', $job) }}" method="POST" enctype="multipart/form-data" class="border-t border-gray-200 pt-4">
                    @csrf
                    <label class="block text-gray-700 font-bold mb-2">Yeni dosya ekle (tür serbest)</label>
                    <div class="flex flex-wrap items-end gap-3">
                        <input type="file" name="files[]" multiple class="text-sm text-gray-600" accept="*">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700">Ekle</button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">⏱️ Üretim Süreleri</h4>
                    <ul class="space-y-4">
                        <li class="flex justify-between">
                            <span class="text-gray-600">CAM İstasyonu:</span>
                            <span class="font-bold">{{ $job->cam_minutes ?? '--' }} dk</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Lazer İstasyonu:</span>
                            <span class="font-bold">{{ $job->lazer_minutes ?? '--' }} dk</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">CMM Ölçüm:</span>
                            <span class="font-bold">{{ $job->cmm_minutes ?? '--' }} dk</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Tesviye:</span>
                            <span class="font-bold">{{ $job->tesviye_minutes ?? '--' }} dk</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Torna:</span>
                            <span class="font-bold">{{ $job->torna_minutes ?? '--' }} dk</span>
                        </li>
                        <li class="border-t pt-2 flex justify-between text-indigo-700">
                            <span class="font-bold">Toplam Süre:</span>
                            <span class="font-bold">
                                {{ $job->total_minutes }} dk
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">📦 Planlama & Lojistik</h4>
                    <ul class="space-y-4">
                        <li>
                            <span class="block text-xs text-gray-400">Planlanan Tarih</span>
                            <span class="font-bold text-gray-800">{{ $job->planning_date ? \Carbon\Carbon::parse($job->planning_date)->format('d.m.Y') : '--' }}</span>
                        </li>
                        <li>
                            <span class="block text-xs text-gray-400">Paket Tipi</span>
                            <span class="font-bold text-gray-800">{{ $job->packaging_type ?? '--' }}</span>
                        </li>
                        <li>
                            <span class="block text-xs text-gray-400 mb-1">İrsaliye Dosyası</span>
                            @if($job->delivery_note_path)
                                <a href="{{ asset('storage/' . $job->delivery_note_path) }}" target="_blank" class="text-blue-600 underline font-bold text-sm">Dosyayı Görüntüle</a>
                            @else
                                <span class="text-gray-400 italic text-sm">Henüz yüklenmedi</span>
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-green-100 bg-green-50">
                    <h4 class="font-bold text-green-800 border-b border-green-200 pb-2 mb-4">💰 Muhasebe & Finans</h4>
                    <ul class="space-y-4">
                        <li class="flex justify-between">
                            <span class="text-green-700 text-sm">Toplam Tutar:</span>
                            <span class="font-bold text-lg">${{ number_format($job->total_amount ?? 0, 2) }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-green-700 text-sm">Tahsil Edilen:</span>
                            <span class="font-bold text-lg text-green-600">${{ number_format($job->paid_amount ?? 0, 2) }}</span>
                        </li>
                        <li>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
                                @php
                                    $totalAmount = $job->total_amount ?? 0;
                                    $paidAmount = $job->paid_amount ?? 0;
                                    $percent = ($totalAmount > 0) ? ($paidAmount / $totalAmount) * 100 : 0;
                                @endphp
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 block">%{{ round($percent) }} Ödendi</span>
                        </li>
                        <li class="pt-2">
                             <span class="block text-xs text-green-700 mb-1">Fatura</span>
                             @if($job->invoice_path)
                                <a href="{{ asset('storage/' . $job->invoice_path) }}" target="_blank" class="text-green-700 underline font-bold text-sm">📄 Faturayı Görüntüle</a>
                            @else
                                <span class="text-gray-400 italic text-sm">Kesilmedi</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            @if($job->jobStationDetails && $job->jobStationDetails->isNotEmpty())
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-amber-400">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">📋 İstasyon Parça Detayları</h4>
                <p class="text-sm text-gray-500 mb-4">Operatörlerin girdiği parça bilgileri (Parça No, En, Boy, Yükseklik, Adet, Cinsi)</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase">
                                <th class="p-3">İstasyon</th>
                                <th class="p-3">Parça No</th>
                                <th class="p-3">En</th>
                                <th class="p-3">Boy</th>
                                <th class="p-3">Yükseklik</th>
                                <th class="p-3">Adet</th>
                                <th class="p-3">Cinsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($job->jobStationDetails as $d)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-bold">{{ \App\Models\JobStationDetail::stationLabel($d->station) }}</td>
                                <td class="p-3">{{ $d->parca_no ?? '--' }}</td>
                                <td class="p-3">{{ $d->en ?? '--' }}</td>
                                <td class="p-3">{{ $d->boy ?? '--' }}</td>
                                <td class="p-3">{{ $d->station === 'torna' ? '--' : ($d->yukseklik ?? '--') }}</td>
                                <td class="p-3">{{ $d->adet ?? '--' }}</td>
                                <td class="p-3">{{ $d->cinsi ?? '--' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Üretim Maliyetleri -->
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">💰 Üretim Maliyetleri (USD)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <ul class="space-y-4">
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">CAM İstasyonu:</span>
                            <div class="text-right">
                                <span class="font-bold text-green-600">${{ number_format($job->cam_cost, 2) }}</span>
                                <span class="text-xs text-gray-400 ml-2">({{ $job->cam_minutes ?? 0 }} dk)</span>
                            </div>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Lazer İstasyonu:</span>
                            <div class="text-right">
                                <span class="font-bold text-green-600">${{ number_format($job->lazer_cost, 2) }}</span>
                                <span class="text-xs text-gray-400 ml-2">({{ $job->lazer_minutes ?? 0 }} dk)</span>
                            </div>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">CMM Ölçüm:</span>
                            <div class="text-right">
                                <span class="font-bold text-green-600">${{ number_format($job->cmm_cost, 2) }}</span>
                                <span class="text-xs text-gray-400 ml-2">({{ $job->cmm_minutes ?? 0 }} dk)</span>
                            </div>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Tesviye:</span>
                            <div class="text-right">
                                <span class="font-bold text-green-600">${{ number_format($job->tesviye_cost, 2) }}</span>
                                <span class="text-xs text-gray-400 ml-2">({{ $job->tesviye_minutes ?? 0 }} dk)</span>
                            </div>
                        </li>
                        @if($job->planning_date)
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Planlama:</span>
                            <span class="font-bold text-green-600">${{ number_format($job->planning_cost, 2) }}</span>
                        </li>
                        @endif
                        @if($job->packaging_type)
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Paketleme ({{ $job->packaging_type }}):</span>
                            <span class="font-bold text-green-600">${{ number_format($job->packaging_cost, 2) }}</span>
                        </li>
                        @endif
                        @if($job->delivery_note_path)
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Lojistik:</span>
                            <span class="font-bold text-green-600">${{ number_format($job->logistics_cost, 2) }}</span>
                        </li>
                        @endif
                    </ul>
                    <div class="border-l-2 border-gray-200 pl-6 space-y-4">
                        <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-indigo-800 text-lg">Üretim Maliyeti:</span>
                                <span class="font-bold text-indigo-600 text-2xl">${{ number_format($job->unit_production_cost, 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">1 adet için tüm istasyonların toplam maliyeti</p>
                        </div>
                        @php $qty = (int) ($job->quantity ?? 1); @endphp
                        @if($qty > 0)
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-amber-800">1 Adet Maliyeti:</span>
                                    <span class="font-bold">${{ number_format($job->unit_production_cost, 2) }}</span>
                                </div>
                                <div class="flex justify-between font-bold text-amber-900">
                                    <span>{{ $qty }} Adet × ${{ number_format($job->unit_production_cost, 2) }} =</span>
                                    <span>${{ number_format($job->total_quantity_cost, 2) }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Toplam üretim maliyeti (adet × 1 adet maliyeti)</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kar/Zarar Analizi -->
            @if($job->total_amount)
            @php
                $isProfit = $job->profit_loss >= 0;
                $bgColor = $isProfit ? 'green' : 'red';
            @endphp
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 {{ $isProfit ? 'border-green-500' : 'border-red-500' }}">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">📊 Kar/Zarar Analizi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <ul class="space-y-4">
                        <li class="flex justify-between">
                            <span class="text-gray-600">Toplam Gelir (Muhasebe):</span>
                            <span class="font-bold">${{ number_format($job->total_amount, 2) }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Toplam Üretim Maliyeti ({{ $job->quantity ?? 1 }} adet):</span>
                            <span class="font-bold">${{ number_format($job->total_quantity_cost, 2) }}</span>
                        </li>
                    </ul>
                    <div class="p-4 rounded-lg border {{ $isProfit ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-lg {{ $isProfit ? 'text-green-800' : 'text-red-800' }}">
                                {{ $isProfit ? '✅ Kar' : '❌ Zarar' }}:
                            </span>
                            <span class="font-bold text-2xl {{ $isProfit ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format(abs($job->profit_loss), 2) }}
                            </span>
                        </div>
                        <div class="mt-2">
                            <span class="text-sm text-gray-600">Kar/Zarar Yüzdesi: </span>
                            <span class="font-bold {{ $isProfit ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($job->profit_loss_percentage, 2) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
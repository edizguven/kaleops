<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('İş Emri Yönetim Merkezi') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-600 text-white rounded-lg shadow-lg font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-md">
                    <p class="font-bold">Dikkat! Kayıt yapılamadı:</p>
                    <ul class="list-disc pl-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-xl p-8 mb-8 border border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">Yeni İş Emri Tanımla</h3>

                <form action="{{ route('admin.jobs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">İŞ BAŞLIĞI</label>
                        <input type="text" name="title" value="{{ old('title') }}" required 
                               class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Örn: Üretim Projesi A">
                        @error('title') <span class="text-red-500 text-sm font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">ADET</label>
                        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="999999" required
                               class="w-full max-w-xs p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Örn: 10, 35, 1000">
                        @error('quantity') <span class="text-red-500 text-sm font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">AÇIKLAMA (opsiyonel, en fazla 150 karakter)</label>
                        <textarea name="description" rows="3" maxlength="150" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="İş detayları. En fazla 150 karakter.">{{ old('description') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">En fazla 150 harf.</p>
                        @error('description') <span class="text-red-500 text-sm font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">ÖNCELİK</label>
                        <select name="priority" class="w-full max-w-xs p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">— Seçin —</option>
                            <option value="dusuk" {{ old('priority') === 'dusuk' ? 'selected' : '' }}>Düşük</option>
                            <option value="orta" {{ old('priority') === 'orta' ? 'selected' : '' }}>Orta</option>
                            <option value="yuksek" {{ old('priority') === 'yuksek' ? 'selected' : '' }}>Yüksek</option>
                            <option value="acil" {{ old('priority') === 'acil' ? 'selected' : '' }}>Acil</option>
                            <option value="cok_acil" {{ old('priority') === 'cok_acil' ? 'selected' : '' }}>Çok Acil</option>
                        </select>
                        @error('priority') <span class="text-red-500 text-sm font-bold">{{ $message }}</span> @enderror
                    </div>

                    @if($showAssignStations ?? false)
                    <div class="mb-6 p-4 bg-amber-50 border-2 border-amber-200 rounded-xl">
                        <label class="block text-gray-800 font-bold mb-3">BU İŞTE SÜRE GİRECEK İSTASYONLAR</label>
                        <p class="text-sm text-gray-600 mb-3">Sadece işaretlediğiniz istasyonlardaki operatörler bu işi görecek. En az bir istasyon seçin.</p>
                        <div class="flex flex-wrap gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="assign_cam" value="1" {{ old('assign_cam', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="font-medium text-gray-700">CAM</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="assign_lazer" value="1" {{ old('assign_lazer', true) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="font-medium text-gray-700">Lazer</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="assign_cmm" value="1" {{ old('assign_cmm', true) ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <span class="font-medium text-gray-700">CMM</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="assign_tesviye" value="1" {{ old('assign_tesviye', true) ? 'checked' : '' }} class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                <span class="font-medium text-gray-700">Tesviye</span>
                            </label>
                            @if(\Illuminate\Support\Facades\Schema::hasColumn('jobs', 'assign_torna'))
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="assign_torna" value="1" {{ old('assign_torna', true) ? 'checked' : '' }} class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                <span class="font-medium text-gray-700">Torna</span>
                            </label>
                            @endif
                        </div>
                        @error('assign_stations') <span class="text-red-500 text-sm font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="p-4 border-2 border-dashed border-blue-300 rounded-xl bg-blue-50 hover:bg-blue-100 transition">
                            <label class="block text-blue-800 font-bold mb-2">TEKNİK DOSYA</label>
                            <p class="text-xs text-gray-500 mb-2">Tür serbest (STL, GCODE, TXT, PDF, DWG vb.). Max 50MB/dosya.</p>
                            <div id="teknik-dosya-list" class="space-y-2">
                                <input type="file" name="teknik_dosya[]" class="w-full text-sm text-gray-600" accept="*">
                            </div>
                            <button type="button" onclick="ekleDosyaAlani('teknik-dosya-list', 'teknik_dosya[]')" class="mt-2 px-3 py-1.5 bg-blue-600 text-white rounded text-sm font-bold hover:bg-blue-700">+ Ekle</button>
                            @error('teknik_dosya') <span class="text-red-500 text-sm font-bold block mt-1">{{ $message }}</span> @enderror
                            @error('teknik_dosya.*') <span class="text-red-500 text-sm font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="p-4 border-2 border-dashed border-red-300 rounded-xl bg-red-50 hover:bg-red-100 transition">
                            <label class="block text-red-800 font-bold mb-2">TEKNİK ÇİZİM, EXCEL, VB.</label>
                            <p class="text-xs text-gray-500 mb-2">Tür serbest. Max 50MB/dosya.</p>
                            <div id="teknik-cizim-list" class="space-y-2">
                                <input type="file" name="teknik_cizim[]" class="w-full text-sm text-gray-600" accept="*">
                            </div>
                            <button type="button" onclick="ekleDosyaAlani('teknik-cizim-list', 'teknik_cizim[]')" class="mt-2 px-3 py-1.5 bg-red-600 text-white rounded text-sm font-bold hover:bg-red-700">+ Ekle</button>
                            @error('teknik_cizim') <span class="text-red-500 text-sm font-bold block mt-1">{{ $message }}</span> @enderror
                            @error('teknik_cizim.*') <span class="text-red-500 text-sm font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <script>
                        function ekleDosyaAlani(containerId, name) {
                            var div = document.getElementById(containerId);
                            var input = document.createElement('input');
                            input.type = 'file';
                            input.name = name;
                            input.className = 'w-full text-sm text-gray-600';
                            input.setAttribute('accept', '*');
                            div.appendChild(input);
                        }
                    </script>

                    <div class="bg-gray-50 border-2 border-indigo-200 rounded-xl p-6 shadow-inner">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-indigo-800 font-bold uppercase tracking-wider text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                İşlem Kontrol Paneli
                            </span>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-4 justify-end">
                            <button type="reset" class="px-6 py-4 bg-white text-gray-600 font-bold rounded-lg border border-gray-300 hover:bg-gray-100 hover:text-red-500 transition shadow-sm">
                                FORMU TEMİZLE
                            </button>

                            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white font-black rounded-lg shadow-lg hover:bg-indigo-700 hover:shadow-xl transform hover:-translate-y-1 transition duration-200 w-full md:w-auto text-center">
                                KAYDET VE BAŞLAT
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <div class="bg-white shadow-lg rounded-xl overflow-hidden p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 border-b pb-4">
                    <h3 class="font-bold text-gray-700">Mevcut İşler Listesi (toplam {{ $jobs->total() }} kayıt)</h3>
                    <div class="flex flex-wrap items-center gap-3 flex-1 sm:justify-end">
                        <form action="{{ route('admin.jobs.index') }}" method="get" class="flex flex-wrap items-center gap-2 flex-1">
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="İş No veya Başlık ile ara..." 
                                   class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm w-40">
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" title="Başlangıç tarihi">
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" title="Bitiş tarihi">
                            @if(request('per_page'))
                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                            @endif
                            <button type="submit" class="shrink-0 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-sm transition">Ara</button>
                        </form>
                        @if(request('q') || request('date_from') || request('date_to'))
                            <a href="{{ route('admin.jobs.index', ['per_page' => request('per_page', 25)]) }}" class="shrink-0 px-3 py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">Filtreleri temizle</a>
                        @endif
                        <form action="{{ route('admin.jobs.index') }}" method="get" class="flex items-center gap-2" id="per-page-form">
                            @if(request('q'))
                                <input type="hidden" name="q" value="{{ request('q') }}">
                            @endif
                            @if(request('date_from'))
                                <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                            @endif
                            @if(request('date_to'))
                                <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                            @endif
                            <label for="per_page" class="text-sm font-medium text-gray-700 whitespace-nowrap">Sayfa başına:</label>
                            <select name="per_page" id="per_page" class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" onchange="this.form.submit()">
                                <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                    </div>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm uppercase">
                            <th class="p-4">Öncelik</th>
                            <th class="p-4">İş No</th>
                            <th class="p-4">Başlık</th>
                            <th class="p-4">Oluşturulma / Açıklama</th>
                            <th class="p-4">Adet</th>
                            <th class="p-4">Dosyalar</th>
                            <th class="p-4">Durum</th>
                            <th class="p-4 text-right">İŞLEMLER</th> </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-4">
                                    @if($job->priority)
                                        <span class="{{ $job->priority_badge_class }} @if(in_array($job->priority, ['acil', 'cok_acil'])) priority-blink @endif">{{ $job->priority_label }}</span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="p-4 text-indigo-600 font-bold font-mono">{{ $job->job_no }}</td>
                                <td class="p-4 font-medium">{{ $job->title }}</td>
                                <td class="p-4 text-sm max-w-xs">
                                    <span class="block whitespace-nowrap text-gray-700">{{ $job->created_at ? $job->created_at->format('d.m.Y H:i') : '—' }}</span>
                                    @if($job->description)
                                        <span class="block text-gray-600 mt-1 truncate" title="{{ $job->description }}">{{ Str::limit($job->description, 45) }}</span>
                                    @endif
                                </td>
                                <td class="p-4 font-medium">{{ $job->quantity ?? 1 }}</td>
                                <td class="p-4">
                                    @forelse($job->jobFiles as $f)
                                        <a href="{{ route('jobfile.download', $f) }}" class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold mr-2 hover:bg-blue-200" download>{{ Str::limit($f->original_name, 12) }}</a>
                                    @empty
                                        <span class="text-gray-500 text-xs">Dosya yok</span>
                                    @endforelse
                                </td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded text-xs font-bold
                                        {{ $job->is_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $job->is_completed ? 'Tamamlandı' : 'İşlemde' }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('admin.jobs.show', $job->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-md transition transform hover:scale-105">
                                        DETAY / RAPOR 🔍
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-gray-500 bg-gray-50 rounded italic">Henüz aktif bir iş emri bulunmuyor.{{ request('q') || request('date_from') || request('date_to') ? ' Filtre kriterine uygun kayıt yok.' : '' }}</td>
                                </tr>
                            @endforelse
                    </tbody>
                </table>
                @if($jobs->hasPages())
                    <div class="mt-6 pt-4 border-t border-gray-200 flex flex-wrap items-center justify-between gap-4">
                        <p class="text-sm text-gray-600">
                            {{ $jobs->firstItem() ?? 0 }} - {{ $jobs->lastItem() ?? 0 }} / {{ $jobs->total() }} kayıt
                        </p>
                        <div class="flex flex-wrap justify-end">
                            {{ $jobs->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
    <style>
        @keyframes priority-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .priority-blink {
            animation: priority-blink 1s ease-in-out infinite;
        }
    </style>
</x-app-layout>
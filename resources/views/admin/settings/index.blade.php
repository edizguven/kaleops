<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Maliyet Ayarları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-500 text-white rounded-lg shadow font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-md">
                    <p class="font-bold">Hata:</p>
                    <ul class="list-disc pl-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- İstasyon Maliyetleri -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h3 class="text-xl font-bold mb-6 border-b pb-4">İstasyon Maliyetleri (Dakika Başına - USD)</h3>
                
                <form action="{{ route('admin.settings.update.stations') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        @foreach($stations as $station)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $station->station_name }}</label>
                                    <p class="text-xs text-gray-500 mt-1">Kod: {{ $station->station_code }}</p>
                                </div>
                                <div>
                                    <input type="hidden" name="stations[{{ $loop->index }}][id]" value="{{ $station->id }}">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               name="stations[{{ $loop->index }}][cost_per_minute]" 
                                               value="{{ $station->cost_per_minute }}" 
                                               required
                                               min="0"
                                               class="pl-7 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900">
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    USD/dakika
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                            💾 İstasyon Maliyetlerini Kaydet
                        </button>
                    </div>
                </form>
            </div>

            <!-- Paketleme Fiyatları -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h3 class="text-xl font-bold mb-6 border-b pb-4">Paketleme Fiyatları (Sabit - USD)</h3>
                
                <form action="{{ route('admin.settings.update.packages') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        @foreach($packages as $package)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $package->package_name }}</label>
                                    <p class="text-xs text-gray-500 mt-1">Tip: {{ $package->package_type }}</p>
                                </div>
                                <div>
                                    <input type="hidden" name="packages[{{ $loop->index }}][id]" value="{{ $package->id }}">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               name="packages[{{ $loop->index }}][price]" 
                                               value="{{ $package->price }}" 
                                               required
                                               min="0"
                                               class="pl-7 w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900">
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    USD (sabit fiyat)
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                            💾 Paketleme Fiyatlarını Kaydet
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>













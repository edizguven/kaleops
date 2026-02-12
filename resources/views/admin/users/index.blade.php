<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kullanıcı Yönetimi') }}
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
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-600 text-white rounded-lg shadow-lg font-bold">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-md">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-xl p-6 mb-6 border border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Yeni Kullanıcı Ekle
                    </a>
                    <form action="{{ route('admin.users.index') }}" method="get" class="flex flex-wrap items-center gap-2">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Ad veya e-posta ara..." class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <select name="role" class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">Tüm roller</option>
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg font-medium text-sm">Filtrele</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-posta</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Birim</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-gray-900">
                            @forelse($users as $u)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap font-medium">{{ $u->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $u->email }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">{{ $roles[$u->role] ?? $u->role }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ optional($u->department)->name ?? '—' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm space-x-2">
                                        <a href="{{ route('admin.users.edit', ['user' => $u->id]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Düzenle</a>
                                        <a href="{{ route('admin.users.edit', ['user' => $u->id]) }}#password" class="text-amber-600 hover:text-amber-800 font-medium">Şifre</a>
                                        @if($u->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', ['user' => $u->id]) }}" method="post" class="inline" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Sil</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">(siz)</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500 italic">Kayıt bulunamadı.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

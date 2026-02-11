<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Oturumlar / Son Aktivite') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl rounded-xl p-6 border border-gray-200">
                <p class="text-sm text-gray-600 mb-4">Giriş yapmış kullanıcıların oturumları. Son aktivite = o oturumda en son işlem yapılan an.</p>

                <form action="{{ route('admin.sessions.index') }}" method="get" class="flex flex-wrap items-center gap-2 mb-6">
                    <label class="text-sm font-medium text-gray-700">Kullanıcı:</label>
                    <select name="user_id" class="rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Tümü</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }}) — {{ $u->role }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium text-sm">Filtrele</button>
                    @if(request('user_id'))
                        <a href="{{ route('admin.sessions.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Temizle</a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Son aktivite</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP adresi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarayıcı / cihaz</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-gray-900">
                            @forelse($sessions as $session)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <span class="font-medium">{{ $session->user?->name ?? '—' }}</span>
                                        <span class="block text-xs text-gray-500">{{ $session->user?->email ?? '' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $session->user?->role ?? '—' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm whitespace-nowrap">{{ $session->last_activity_at ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm font-mono">{{ $session->ip_address ?? '—' }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-600 max-w-xs truncate" title="{{ $session->user_agent }}">{{ $session->short_user_agent }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500 italic">Kayıt bulunamadı. Oturumlar veritabanı (sessions) tablosundan okunur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sessions->hasPages())
                    <div class="mt-4">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Yeni Kullanıcı Ekle') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

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

            <div class="bg-white shadow-xl rounded-xl p-8 border border-gray-200">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Ad Soyad</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Örn: Ahmet Yılmaz">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">E-posta</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="ornek@firma.com">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Şifre</label>
                            <input type="password" name="password" required autocomplete="new-password"
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="En az 8 karakter">
                            <p class="mt-1 text-sm text-gray-500">En az 8 karakter kullanın.</p>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Şifre (Tekrar)</label>
                            <input type="password" name="password_confirmation" required autocomplete="new-password"
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Rol</label>
                            <select name="role" required class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($roles as $key => $label)
                                    <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($departments->isNotEmpty())
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Birim (isteğe bağlı)</label>
                            <select name="department_id" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">— Seçin —</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" {{ old('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition">
                            Kullanıcı Ekle
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">İptal</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>

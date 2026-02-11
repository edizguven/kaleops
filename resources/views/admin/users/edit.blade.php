<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kullanıcı Düzenle') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-600 text-white rounded-lg shadow-lg font-bold">
                    {{ session('success') }}
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

            <div class="bg-white shadow-xl rounded-xl p-8 border border-gray-200 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">Bilgileri Güncelle</h3>
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Ad Soyad</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">E-posta</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Rol</label>
                            <select name="role" required class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($roles as $key => $label)
                                    <option value="{{ $key }}" {{ old('role', $user->role) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($departments->isNotEmpty())
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Birim (isteğe bağlı)</label>
                            <select name="department_id" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">— Seçin —</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" {{ old('department_id', $user->department_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <label class="block text-gray-700 font-bold mb-2">Yeni şifre (değiştirmek istemiyorsanız boş bırakın)</label>
                            <input type="password" name="password" autocomplete="new-password"
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Boş bırakılırsa mevcut şifre kalır">
                            <input type="password" name="password_confirmation" autocomplete="new-password"
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-2"
                                   placeholder="Şifre tekrar">
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition">
                            Kaydet
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Listeye Dön</a>
                    </div>
                </form>
            </div>

            <div id="password" class="bg-white shadow-xl rounded-xl p-8 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Şifre Değiştir (Admin)</h3>
                <p class="text-sm text-gray-600 mb-4">Bu kullanıcının şifresini doğrudan değiştirir. Mevcut şifreyi bilmeniz gerekmez.</p>
                <form action="{{ route('admin.users.updatePassword', $user) }}" method="POST" class="max-w-md">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Yeni şifre</label>
                            <input type="password" name="password" required autocomplete="new-password"
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Yeni şifre (tekrar)</label>
                            <input type="password" name="password_confirmation" required autocomplete="new-password"
                                   class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-lg shadow transition">
                            Şifreyi Güncelle
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>

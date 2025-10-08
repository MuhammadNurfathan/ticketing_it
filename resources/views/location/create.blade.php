<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Department</label>
                        <select name="department_id" class="w-full border rounded p-2">
                            <option value="">-- Pilih Department --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block">Password</label>
                        <input type="password" name="password" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                    <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

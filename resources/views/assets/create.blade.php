<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Asset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Asset</label>
                            <input type="text" name="assets_code" value="{{ old('assets_code') }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: AST-001" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Asset</label>
                            <input type="text" name="assets_name" value="{{ old('assets_name') }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: Laptop Lenovo ThinkPad" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <input type="text" name="category" value="{{ old('category') }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2"
                                placeholder="Contoh: Laptop / PC" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2" required>
                                <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="Checked Out" {{ old('status') == 'Checked Out' ? 'selected' : '' }}>Checked Out</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2"
                                placeholder="Contoh: Ruang IT" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                            <input type="text" name="model" value="{{ old('model') }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2"
                                placeholder="Contoh: X1 Carbon Gen 9">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Check In</label>
                            <input type="text" name="check_in" value="{{ old('check_in') }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2"
                                placeholder="Contoh: Admin IT">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Check Out</label>
                            <input type="text" name="check_out" value="{{ old('check_out') }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2"
                                placeholder="Contoh: -">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Check Out To</label>
                            <input type="text" name="check_out_to" value="{{ old('check_out_to') }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2"
                                placeholder="Contoh: Divisi Keuangan">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="notes" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                            <input type="file" name="image" accept="image/*" class="w-full">
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                            <a href="{{ route('assets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Kembali
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

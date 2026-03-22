@extends('layouts.app')

@section('title', 'Tambah Akun Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center space-x-3 text-sm text-slate-500">
        <a href="{{ route('users.index') }}" class="hover:text-blue-600 transition">Manajemen Karyawan</a>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-slate-800 font-medium">Tambah Akun</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="p-8 space-y-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 border-b pb-2 mb-4">Informasi Profil</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap Petugas <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 bg-slate-50" placeholder="Misal: Budi Santoso">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 bg-slate-50" placeholder="Misal: budi@pad.com">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-800 border-b pb-2 mb-4">Keamanan Akun</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi (Minimum 8 karakter) <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 bg-slate-50">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Ulangi Kata Sandi <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-2.5 bg-slate-50">
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl font-bold transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition">Simpan Petugas Baru</button>
            </div>
        </form>
    </div>
</div>
@endsection

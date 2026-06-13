@extends('layouts.admin')

@section('title', 'Buat Room Baru')

@section('content')
<div class="p-6 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-sm">← Kembali</a>
        <h1 class="text-2xl font-black">Buat Room Baru</h1>
    </div>

    <div class="card" style="box-shadow:4px 4px 0 #1A1A1A;">
        <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold mb-2">Judul Room <span style="color:#FF7A6B;">*</span></label>
                    <input type="text" name="title" class="input" placeholder="e.g. Cerita Cinta, Pengalaman Hidup..." value="{{ old('title') }}" required maxlength="255">
                    @error('title')<p class="text-xs font-bold mt-1" style="color:#FF7A6B;">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold mb-2">Deskripsi</label>
                    <textarea name="description" class="input" rows="3" placeholder="Ceritakan tentang room ini..." maxlength="1000">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Kategori</label>
                    <select name="category" class="input">
                        <option value="">Pilih kategori...</option>
                        <option value="Cerita Cinta" {{ old('category') == 'Cerita Cinta' ? 'selected' : '' }}>💕 Cerita Cinta</option>
                        <option value="Cerita Sekolah" {{ old('category') == 'Cerita Sekolah' ? 'selected' : '' }}>🏫 Cerita Sekolah</option>
                        <option value="Pelajaran Hidup" {{ old('category') == 'Pelajaran Hidup' ? 'selected' : '' }}>✨ Pelajaran Hidup</option>
                        <option value="Persahabatan" {{ old('category') == 'Persahabatan' ? 'selected' : '' }}>🤝 Persahabatan</option>
                        <option value="Masalah Keluarga" {{ old('category') == 'Masalah Keluarga' ? 'selected' : '' }}>🏠 Masalah Keluarga</option>
                        <option value="Pengakuan Anonim" {{ old('category') == 'Pengakuan Anonim' ? 'selected' : '' }}>🎭 Pengakuan Anonim</option>
                        <option value="Motivasi" {{ old('category') == 'Motivasi' ? 'selected' : '' }}>🚀 Motivasi</option>
                        <option value="Curhat" {{ old('category') == 'Curhat' ? 'selected' : '' }}>💬 Curhat</option>
                        <option value="Umum" {{ old('category') == 'Umum' ? 'selected' : '' }}>📝 Umum</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Warna Badge</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color_badge" value="{{ old('color_badge', '#FF7A6B') }}" class="w-12 h-10 rounded border-2 border-primary cursor-pointer" id="colorPicker">
                        <span class="text-sm font-mono" id="colorHex">{{ old('color_badge', '#FF7A6B') }}</span>
                        <div class="flex gap-1 ml-2">
                            @foreach(['#FF7A6B','#FFE066','#A8D0F0','#FFB5D8','#25D366','#C084FC','#FBA94C'] as $color)
                            <button type="button" onclick="setColor('{{ $color }}')" class="w-6 h-6 rounded-full border-2 border-primary" style="background:{{ $color }};" title="{{ $color }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Tanggal Berakhir</label>
                    <input type="datetime-local" name="expires_at" class="input" value="{{ old('expires_at') }}">
                    <p class="text-xs mt-1" style="color:#6B6B6B;">Kosongkan jika tidak ada batas waktu</p>
                </div>

                <div class="flex flex-col gap-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_public" value="1" class="sr-only peer" {{ old('is_public') ? 'checked' : '' }}>
                            <div class="w-11 h-6 rounded-full border-2 border-primary peer-checked:bg-green-400 bg-gray-200 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full border-2 border-primary bg-white peer-checked:translate-x-5 transition-transform"></div>
                        </div>
                        <span class="font-bold text-sm">Room Publik</span>
                        <span class="text-xs" style="color:#6B6B6B;">(tampil di beranda)</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="allow_anonymous" value="1" class="sr-only peer" checked {{ old('allow_anonymous', true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 rounded-full border-2 border-primary peer-checked:bg-blue-300 bg-gray-200 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full border-2 border-primary bg-white peer-checked:translate-x-5 transition-transform"></div>
                        </div>
                        <span class="font-bold text-sm">Izinkan Anonim</span>
                        <span class="text-xs" style="color:#6B6B6B;">(nama opsional)</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t-2 border-primary">
                <button type="submit" class="btn btn-primary btn-lg flex-1">
                    ✨ Buat Room
                </button>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-lg">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function setColor(hex) {
    document.getElementById('colorPicker').value = hex;
    document.getElementById('colorHex').textContent = hex;
}
document.getElementById('colorPicker').addEventListener('input', function() {
    document.getElementById('colorHex').textContent = this.value;
});
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('title', 'Edit Room')

@section('content')
<div class="p-6 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.rooms.show', $room->room_code) }}" class="btn btn-sm">← Kembali</a>
        <h1 class="text-2xl font-black">Edit Room</h1>
    </div>

    <div class="card" style="box-shadow:4px 4px 0 #1A1A1A;">
        <form action="{{ route('admin.rooms.update', $room->room_code) }}" method="POST">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold mb-2">Judul Room <span style="color:#FF7A6B;">*</span></label>
                    <input type="text" name="title" class="input" value="{{ old('title', $room->title) }}" required maxlength="255">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold mb-2">Deskripsi</label>
                    <textarea name="description" class="input" rows="3" maxlength="1000">{{ old('description', $room->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Kategori</label>
                    <select name="category" class="input">
                        <option value="">Pilih kategori...</option>
                        @foreach(['Cerita Cinta','Cerita Sekolah','Pelajaran Hidup','Persahabatan','Masalah Keluarga','Pengakuan Anonim','Motivasi','Curhat','Umum'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $room->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Warna Badge</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color_badge" value="{{ old('color_badge', $room->color_badge) }}" class="w-12 h-10 rounded border-2 border-primary cursor-pointer" id="colorPicker">
                        <div class="flex gap-1 ml-1">
                            @foreach(['#FF7A6B','#FFE066','#A8D0F0','#FFB5D8','#25D366','#C084FC','#FBA94C'] as $color)
                            <button type="button" onclick="setColor('{{ $color }}')" class="w-6 h-6 rounded-full border-2 border-primary" style="background:{{ $color }};"></button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Tanggal Berakhir</label>
                    <input type="datetime-local" name="expires_at" class="input" value="{{ old('expires_at', $room->expires_at ? $room->expires_at->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="flex flex-col gap-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', $room->is_public) ? 'checked' : '' }}>
                        <span class="font-bold text-sm">Room Publik</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="allow_anonymous" value="1" {{ old('allow_anonymous', $room->allow_anonymous) ? 'checked' : '' }}>
                        <span class="font-bold text-sm">Izinkan Anonim</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t-2 border-primary">
                <button type="submit" class="btn btn-primary btn-lg flex-1">Simpan Perubahan</button>
                <a href="{{ route('admin.rooms.show', $room->room_code) }}" class="btn btn-lg">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function setColor(hex) { document.getElementById('colorPicker').value = hex; }
</script>
@endpush
@endsection

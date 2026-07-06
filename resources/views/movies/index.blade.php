<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineLog — Personal Movie Watchlist</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <div class="max-w-7xl mx-auto px-4 py-10">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200 pb-8 mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">🍿 CineLog</h1>
                <p class="text-sm text-slate-500 mt-1">Simpan, lacak, dan beri rating film favoritmu (Penyimpanan Session).</p>
            </div>
            <div class="flex gap-2 bg-slate-200/60 p-1 rounded-xl self-start md:self-center">
                <a href="{{ route('movies.index') }}" class="px-4 py-2 text-xs font-semibold rounded-lg transition {{ !request('status') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">Semua</a>
                <a href="{{ route('movies.index', ['status' => 'Belum Ditonton']) }}" class="px-4 py-2 text-xs font-semibold rounded-lg transition {{ request('status') == 'Belum Ditonton' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">Watchlist ⏳</a>
                <a href="{{ route('movies.index', ['status' => 'Sudah Ditonton']) }}" class="px-4 py-2 text-xs font-semibold rounded-lg transition {{ request('status') == 'Sudah Ditonton' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">History ✅</a>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm sticky top-6">
                <h2 class="text-lg font-bold text-slate-900 mb-5">New Film</h2>
                <form action="{{ route('movies.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Judul Film</label>
                        <input type="text" name="title" required placeholder="Contoh: Interstellar" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Genre</label>
                            <select name="genre" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                                <option value="Action">Action</option>
                                <option value="Sci-Fi">Sci-Fi</option>
                                <option value="Drama">Drama</option>
                                <option value="Horror">Horror</option>
                                <option value="Comedy">Comedy</option>
                                <option value="Romance">Romance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Tahun Rilis</label>
                            <input type="number" name="release_year" required min="1900" max="{{ date('Y') }}" value="{{ date('Y') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-3 px-4 rounded-xl shadow-sm transition">
                        + Masukkan ke List
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2">
                @if(empty($movies))
                    <div class="text-center py-20 bg-white border border-dashed border-slate-200 rounded-2xl">
                        <span class="text-4xl">🎬</span>
                        <p class="text-slate-500 text-sm mt-3 font-medium">Belum ada film di daftar session ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($movies as $movie)
                            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between relative overflow-hidden group">
                                
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-xs font-semibold bg-slate-100 px-2.5 py-1 rounded-md text-slate-600">{{ $movie['genre'] }}</span>
                                        @if($movie['status'] == 'Sudah Ditonton')
                                            <span class="text-xs font-semibold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-md border border-emerald-100">Sudah Ditonton</span>
                                        @else
                                            <span class="text-xs font-semibold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-md border border-amber-100">Watchlist</span>
                                        @endif
                                    </div>

                                    <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-snug group-hover:text-indigo-600 transition">{{ $movie['title'] }}</h3>
                                    <p class="text-xs font-medium text-slate-400 mt-0.5">Tahun: {{ $movie['release_year'] }}</p>

                                    @if($movie['status'] == 'Sudah Ditonton')
                                        <div class="mt-4 pt-4 border-t border-slate-100">
                                            <div class="flex items-center gap-1 text-amber-400 mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="text-lg">{{ $i <= $movie['rating'] ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                            <p class="text-xs italic text-slate-600">"{{ $movie['review'] ?? 'Tidak ada ulasan tertulis.' }}"</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                    @if($movie['status'] == 'Belum Ditonton')
                                        <button onclick="document.getElementById('modal-update-{{ $movie['id'] }}').showModal()" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-2 rounded-lg transition cursor-pointer">
                                            ✓ Tandai Selesai
                                        </button>
                                    @else
                                        <div class="text-xs text-slate-400 font-medium">Selesai dinilai</div>
                                    @endif

                                    <form action="{{ route('movies.destroy', $movie['id']) }}" method="POST" onsubmit="return confirm('Hapus film ini dari daftar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-3 py-2 rounded-lg transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                <dialog id="modal-update-{{ $movie['id'] }}" class="backdrop:bg-black/30 bg-white rounded-2xl p-6 max-w-md w-full border border-slate-200 shadow-xl mx-auto my-auto">
                                    <h4 class="text-lg font-bold text-slate-900 mb-2">Review Film: {{ $movie['title'] }}</h4>
                                    <p class="text-xs text-slate-500 mb-4">Berikan penilaianmu sebelum memindahkannya ke history.</p>
                                    
                                    <form action="{{ route('movies.update', $movie['id']) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Sudah Ditonton">
                                        
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-500 mb-2">Rating Kamu (1-5 Bintang)</label>
                                            <select name="rating" required class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                                                <option value="5">⭐⭐⭐⭐⭐ (Sempurna)</option>
                                                <option value="4">⭐⭐⭐⭐ (Bagus Banget)</option>
                                                <option value="3">⭐⭐⭐ (Biasa Aja)</option>
                                                <option value="2">⭐⭐ (Kurang Oke)</option>
                                                <option value="1">⭐ (Buruk)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-500 mb-2">Catatan/Review Singkat</label>
                                            <textarea name="review" rows="3" placeholder="Tulis plot twist terkeren atau alasan kamu suka..." class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-indigo-500"></textarea>
                                        </div>

                                        <div class="flex justify-end gap-2 pt-2">
                                            <button type="button" onclick="document.getElementById('modal-update-{{ $movie['id'] }}').close()" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-100 rounded-xl transition">Batal</button>
                                            <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">Simpan Review</button>
                                        </div>
                                    </form>
                                </dialog>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

</body>
</html>
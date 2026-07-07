<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinemaLog — Personal Movie Watchlist</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        /* Animasi smooth untuk dialog modal */
        dialog[open] {
            animation: zoomIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.98) translateY(4px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
</head>
<body class="bg-[#f4f7f5] text-emerald-950 antialiased min-h-screen selection:bg-emerald-600 selection:text-white">

    <div class="max-w-7xl mx-auto px-4 py-10">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-emerald-100 pb-6 mb-10 gap-6">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-emerald-900 flex items-center gap-2">
                    <span class="text-3xl">🍿</span> CinemaLog
                </h1>
                <p class="text-sm text-emerald-700/70 mt-1">Simpan, dan beri rating film favoritmu dengan nyaman.</p>
            </div>
            
            <div class="flex gap-1 bg-emerald-100/60 p-1.5 rounded-xl self-start md:self-center border border-emerald-200/50">
                <a href="{{ route('movies.index') }}" class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200 {{ !request('status') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-800 hover:bg-emerald-50' }}">
                    Semua
                </a>
                <a href="{{ route('movies.index', ['status' => 'Belum Ditonton']) }}" class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200 {{ request('status') == 'Belum Ditonton' ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-800 hover:bg-emerald-50' }}">
                    Belum Ditonton ⏳
                </a>
                <a href="{{ route('movies.index', ['status' => 'Sudah Ditonton']) }}" class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200 {{ request('status') == 'Sudah Ditonton' ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-800 hover:bg-emerald-50' }}">
                    Sudah Ditonton ✅
                </a>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-600 text-white text-sm font-medium rounded-xl flex items-center gap-2 shadow-sm">
                <span>✨</span> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="bg-white border border-emerald-100 rounded-2xl p-6 shadow-sm sticky top-6 transition-all duration-300 hover:shadow-md">
                <h2 class="text-lg font-bold text-emerald-900 mb-5 flex items-center gap-2">
                    <span class="p-1 bg-emerald-50 rounded-lg text-emerald-700 text-sm">➕</span> Tambah Film Baru
                </h2>
                
                <form action="{{ route('movies.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-emerald-800/70 mb-1.5">Judul Film</label>
                        <input type="text" name="title" required placeholder="Isi Judul" class="w-full px-4 py-2.5 rounded-xl bg-emerald-50/50 border border-emerald-200 text-emerald-950 text-sm focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 transition-all placeholder:text-emerald-700/40">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-800/70 mb-1.5">Genre</label>
                            <select name="genre" required class="w-full px-4 py-2.5 rounded-xl bg-emerald-50/50 border border-emerald-200 text-emerald-950 text-sm focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 transition-all">
                                <option value="Action">Action</option>
                                <option value="Sci-Fi">Fantasy</option>
                                <option value="Drama">Drama</option>
                                <option value="Horror">Horror</option>
                                <option value="Comedy">Comedy</option>
                                <option value="Romance">Romance</option>
                                <option value="Thriller">Thriller</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-800/70 mb-1.5">Tahun Rilis</label>
                            <input type="number" name="release_year" required min="1800" value="{{ date('Y') }}" class="w-full px-4 py-2.5 rounded-xl bg-emerald-50/50 border border-emerald-200 text-emerald-950 text-sm focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-emerald-800/70 mb-1.5">Negara Asal</label>
                        <input type="text" name="country" placeholder="Isi Negara" class="w-full px-4 py-2.5 rounded-xl bg-emerald-50/50 border border-emerald-200 text-emerald-950 text-sm focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 transition-all placeholder:text-emerald-700/40">
                    </div>
                    
                    <button type="submit" class="w-full mt-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm py-2.5 px-4 rounded-xl shadow-md shadow-emerald-700/10 transition-all duration-200 transform active:scale-[0.98] cursor-pointer">
                        Masukkan ke List
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2">
                @if(empty($movies))
                    <div class="flex flex-col items-center justify-center text-center p-10 md:p-16 bg-white border border-dashed border-emerald-200 rounded-2xl shadow-sm">
                        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-3xl mb-4 text-emerald-700">🎬</div>
                        <h3 class="text-base font-bold text-emerald-900">Belum ada film di daftar</h3>
                        <p class="text-emerald-700/60 text-xs max-w-sm mt-1 mb-6">Mulai isi CinemaLog pribadimu dengan mengetikkan judul film favorit di panel sebelah kiri.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($movies as $movie)
                            <div class="bg-white border border-emerald-100 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all duration-200 flex flex-col justify-between relative group hover:-translate-y-0.5">
                                
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[11px] font-bold bg-emerald-50 px-2.5 py-0.5 rounded-md text-emerald-700 border border-emerald-100">{{ $movie['genre'] }}</span>
                                            @if(!empty($movie['country']))
                                                <span class="text-[11px] font-medium bg-slate-100 px-2 py-0.5 rounded-md text-slate-600 border border-slate-200/60">
                                                    🌍 {{ $movie['country'] }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($movie['status'] == 'Sudah Ditonton')
                                            <span class="text-[11px] font-bold bg-teal-50 text-teal-700 px-2.5 py-0.5 rounded-md border border-teal-100 flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span> Sudah Ditonton
                                            </span>
                                        @else
                                            <span class="text-[11px] font-bold bg-amber-50 text-amber-700 px-2.5 py-0.5 rounded-md border border-amber-100 flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Belum Ditonton
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-lg font-bold text-emerald-950 tracking-tight leading-snug group-hover:text-emerald-700 transition-colors duration-200">{{ $movie['title'] }}</h3>
                                    <p class="text-xs text-emerald-700/60 mt-0.5">Tahun Rilis: <span class="font-medium text-emerald-900">{{ $movie['release_year'] }}</span></p>

                                    @if($movie['status'] == 'Sudah Ditonton')
                                        <div class="mt-3 pt-3 border-t border-emerald-50">
                                            <div class="flex items-center gap-0.5 text-amber-400 mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="text-sm">{{ $i <= $movie['rating'] ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                            <p class="text-xs italic text-emerald-800 bg-emerald-50/40 p-2 rounded-lg border border-emerald-100/50">
                                                "{{ $movie['review'] ?? 'Tidak ada ulasan tertulis.' }}"
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-5 pt-3 border-t border-emerald-50 flex items-center justify-between gap-2">
                                    @if($movie['status'] == 'Belum Ditonton')
                                        <button onclick="document.getElementById('modal-update-{{ $movie['id'] }}').showModal()" class="text-xs font-bold text-emerald-700 hover:text-white bg-emerald-50 hover:bg-emerald-700 px-3 py-1.5 rounded-lg transition-all duration-200 flex items-center gap-1 cursor-pointer active:scale-95">
                                            ✓ Selesai Nonton
                                        </button>
                                    @else
                                        <div class="text-xs text-emerald-600 font-semibold flex items-center gap-1 bg-emerald-50/80 px-2 py-1 rounded-md border border-emerald-100/60">
                                            ✅ Sudah Ditonton
                                        </div>
                                    @endif

                                    <form action="{{ route('movies.destroy', $movie['id']) }}" method="POST" onsubmit="return confirm('Hapus film ini dari daftar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:bg-rose-50 px-2.5 py-1.5 rounded-lg transition-all duration-200 cursor-pointer active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                <dialog id="modal-update-{{ $movie['id'] }}" class="backdrop:bg-emerald-950/40 bg-white border border-emerald-100 rounded-2xl p-6 max-w-md w-full shadow-xl mx-auto my-auto text-emerald-950">
                                    <h4 class="text-base font-bold text-emerald-900 mb-1">Review Film: <span class="text-emerald-700">{{ $movie['title'] }}</span></h4>
                                    <p class="text-xs text-emerald-700/60 mb-4">Berikan penilaianmu untuk menandai film ini sebagai "Sudah Ditonton".</p>
                                    
                                    <form action="{{ route('movies.update', $movie['id']) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Sudah Ditonton">
                                        
                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-800/70 mb-1.5">Rating Kamu (1-5 Bintang)</label>
                                            <select name="rating" required class="w-full px-3 py-2 rounded-xl bg-emerald-50/50 border border-emerald-200 text-emerald-950 text-sm focus:outline-none focus:border-emerald-600">
                                                <option value="5">⭐⭐⭐⭐⭐ (Sempurna)</option>
                                                <option value="4">⭐⭐⭐⭐ (Bagus Banget)</option>
                                                <option value="3">⭐⭐⭐ (Biasa Aja)</option>
                                                <option value="2">⭐⭐ (Kurang Oke)</option>
                                                <option value="1">⭐ (Buruk)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-800/70 mb-1.5">Catatan/Review Singkat</label>
                                            <textarea name="review" rows="3" placeholder="Tulis plot twist terkeren atau alasan kamu suka..." class="w-full px-3 py-2 rounded-xl bg-emerald-50/50 border border-emerald-200 text-emerald-950 text-sm focus:outline-none focus:border-emerald-600 placeholder:text-emerald-700/30"></textarea>
                                        </div>

                                        <div class="flex justify-end gap-2 pt-1">
                                            <button type="button" onclick="document.getElementById('modal-update-{{ $movie['id'] }}').close()" class="px-4 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 rounded-xl transition">Batal</button>
                                            <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl shadow-md transition transform active:scale-95">Simpan & Selesai</button>
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
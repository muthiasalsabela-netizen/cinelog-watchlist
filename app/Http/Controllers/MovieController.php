<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data film dari session (jika kosong, default array kosong)
        $movies = session()->get('movies', []);

        // Filter berdasarkan status tontonan jika ada request filter
        $statusFilter = $request->query('status');
        if ($statusFilter) {
            $movies = array_filter($movies, function($movie) use ($statusFilter) {
                return $movie['status'] === $statusFilter;
            });
        }

        // Urutkan dari yang terbaru dimasukkan
        $movies = array_reverse($movies);

        return view('movies.index', compact('movies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string',
            'release_year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $movies = session()->get('movies', []);

        // Buat data film baru dengan ID unik menggunakan string acak
        $newMovie = [
            'id' => Str::random(10),
            'title' => $request->title,
            'genre' => $request->genre,
            'release_year' => $request->release_year,
            'status' => 'Belum Ditonton',
            'rating' => null,
            'review' => null
        ];

        // Masukkan ke dalam array session
        $movies[] = $newMovie;
        session()->put('movies', $movies);

        return redirect()->route('movies.index')->with('success', 'Film berhasil ditambahkan ke list (Session)!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Belum Ditonton,Sudah Ditonton',
            'rating' => 'nullable|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $movies = session()->get('movies', []);

        // Cari film berdasarkan ID dan perbarui datanya
        foreach ($movies as &$movie) {
            if ($movie['id'] === $id) {
                $movie['status'] = $request->status;
                $movie['rating'] = $request->rating;
                $movie['review'] = $request->review;
                break;
            }
        }

        session()->put('movies', $movies);

        return redirect()->route('movies.index')->with('success', 'Status film berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $movies = session()->get('movies', []);

        // Filter array untuk menghapus film yang ID-nya cocok
        $movies = array_filter($movies, function($movie) use ($id) {
            return $movie['id'] !== $id;
        });

        session()->put('movies', array_values($movies));

        return redirect()->route('movies.index')->with('success', 'Film berhasil dihapus dari list.');
    }
}
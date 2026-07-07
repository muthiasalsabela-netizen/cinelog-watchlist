<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function index(Request $request)
    {
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
            'title'        => 'required|string|max:255',
            'genre'        => 'required|string',
            'release_year' => 'required|integer|min:1800',
            'country'      => 'nullable|string|max:100',
        ]);

        $movies = session()->get('movies', []);

        $newMovie = [
            'id'           => Str::random(10),
            'title'        => $request->title,
            'genre'        => $request->genre,
            'release_year' => $request->release_year,
            'country'      => $request->country ?? '-',
            'status'       => 'Belum Ditonton', // Default status awal saat film dibuat
            'rating'       => null,
            'review'       => null
        ];

        $movies[] = $newMovie;
        session()->put('movies', array_values($movies));

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

        foreach ($movies as &$movie) {
            if ($movie['id'] === $id) {
                $movie['status'] = $request->status; // Mengubah status menjadi 'Sudah Ditonton'
                $movie['rating'] = $request->rating;
                $movie['review'] = $request->review;
                break;
            }
        }

        session()->put('movies', $movies);

        return redirect()->route('movies.index')->with('success', 'Status tontonan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $movies = session()->get('movies', []);

        $movies = array_filter($movies, function($movie) use ($id) {
            return $movie['id'] !== $id;
        });

        session()->put('movies', array_values($movies));

        return redirect()->route('movies.index')->with('success', 'Film berhasil dihapus dari list.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjam;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function calender()
    {
        $events = [];
        $pinjams = Pinjam::with(['ruang', 'borrowed_by', 'processed_by', 'created_by'])->get();

        foreach($pinjams as $pinjam) {
            if (!$pinjam->time_start) {
                continue;
            }

            $events[] = [
                'title' => 'Peminjaman '. $pinjam->ruang->nama_lantai.' Oleh '. $pinjam->borrowed_by->name. ' ('. Pinjam::UNIT_PENGGUNA_SELECT[$pinjam->unit_pengguna]. ') digunakan untuk "'.$pinjam->penggunaan. '"',
                'start' => $pinjam->time_start,
                'end' => $pinjam->time_end,
                // 'url' => route('admin.process.show', $pinjam->id)
            ];
        }

        return view('landing.calender', compact('events'));
    }
}

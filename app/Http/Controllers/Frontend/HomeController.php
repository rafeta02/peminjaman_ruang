<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function index()
    {
        abort_if(Gate::denies('front_dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $events = [];
        $pinjams = Pinjam::with(['ruang', 'borrowed_by', 'processed_by', 'created_by'])->where('status', 'disetujui')->get();

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

        return view('frontend.home', compact('events'));
    }

    public function kalender()
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

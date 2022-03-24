<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Pinjam;

class SystemCalendarController extends Controller
{
    // public $sources = [
    //     [
    //         'model'      => '\App\Models\Pinjam',
    //         'date_field' => 'time_start',
    //         'field'      => 'time_start',
    //         'prefix'     => 'Peminjaman',
    //         'suffix'     => 'Sampai',
    //         'route'      => 'admin.pinjams.edit',
    //     ],
    // ];

    public function index()
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
                'url' => route('admin.process.show', $pinjam->id)
            ];
        }

        return view('admin.calendar.calendar', compact('events'));
    }
}

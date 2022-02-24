<?php

use Illuminate\Database\Seeder;
use App\Models\Lantai;
use App\Models\Ruang;
use Illuminate\Support\Str;

class RuangTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lantai = [
            [
                'name' => 'Lantai 1',
                'slug' => Str::slug('Lantai 1', '-')
            ],
            [
                'name' => 'Lantai 2',
                'slug' => Str::slug('Lantai 2', '-')
            ],
            [
                'name' => 'Lantai 3',
                'slug' => Str::slug('Lantai 3', '-')
            ],
            [
                'name' => 'Lantai 4',
                'slug' => Str::slug('Lantai 4', '-')
            ]
        ];

        Lantai::insert($lantai);

        $ruang = [
            [
                'name' => 'Ruang Sidang 1',
                'slug' => Str::slug('Ruang Sidang 1', '-'),
                'kapasitas' => 60,
                'lantai_id' => Lantai::where('slug', Str::slug('Lantai 1', '-'))->first()->id
            ],
            [
                'name' => 'Press Room',
                'slug' => Str::slug('Press Room', '-'),
                'kapasitas' => 20,
                'lantai_id' => Lantai::where('slug', Str::slug('Lantai 1', '-'))->first()->id
            ],
            [
                'name' => 'Ruang Transit',
                'slug' => Str::slug('Ruang Transit', '-'),
                'kapasitas' => 10,
                'lantai_id' => Lantai::where('slug', Str::slug('Lantai 1', '-'))->first()->id
            ],
            [
                'name' => 'Ruang Sidang 1',
                'slug' => Str::slug('Ruang Sidang 1', '-'),
                'kapasitas' => 20,
                'lantai_id' => Lantai::where('slug', Str::slug('Lantai 2', '-'))->first()->id
            ],
            [
                'name' => 'Ruang Sidang 2',
                'slug' => Str::slug('Ruang Sidang 2', '-'),
                'kapasitas' => 20,
                'lantai_id' => Lantai::where('slug', Str::slug('Lantai 2', '-'))->first()->id
            ],
            [
                'name' => 'Ruang Sidang 3',
                'slug' => Str::slug('Ruang Sidang 3', '-'),
                'kapasitas' => 20,
                'lantai_id' => Lantai::where('slug', Str::slug('Lantai 2', '-'))->first()->id
            ],
            [
                'name' => 'Ruang Sidang 4',
                'slug' => Str::slug('Ruang Sidang 4', '-'),
                'kapasitas' => 20,
                'lantai_id' => Lantai::where('slug', Str::slug('Lantai 2', '-'))->first()->id
            ],
            [
                'name' => 'Ruang Sidang 1',
                'slug' => Str::slug('Ruang Sidang 1', '-'),
                'kapasitas' => 20,
                'lantai_id' => Lantai::where('slug', Str::slug('Lantai 4', '-'))->first()->id
            ],
            [
                'name' => 'Ruang Sidang 2',
                'slug' => Str::slug('Ruang Sidang 2', '-'),
                'kapasitas' => 20,
                'lantai_id' => Lantai::where('slug', Str::slug('Lantai 4', '-'))->first()->id
            ],
        ];
        Ruang::insert($ruang);
    }
}

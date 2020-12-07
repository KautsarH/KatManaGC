<?php

use Illuminate\Database\Seeder;

class StationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Station Seed');
        
        $a11 = \App\Station::updateOrCreate([
            'code' => 'KJ1',
            'name' => 'Gombak',
            'lat' => 3.230957,
            'lng' => 101.724191,
            'status' => 'active'
        ]);
        
        $a10 = \App\Station::updateOrCreate([
            'code' => 'KJ2',
            'name' => 'Taman Melati',
            'lat' => 3.2195325,
            'lng' => 101.7219271,
            'status' => 'active'
        ]);

        $a9 = \App\Station::updateOrCreate([
            'code' => 'KJ3',
            'name' => 'Wangsa Maju',
            'lat' => 3.2057014,
            'lng' => 101.7318741,
            'status' => 'active'
        ]);

        $a8 = \App\Station::updateOrCreate([
            'code' => 'KJ4',
            'name' => 'Sri Rampai',
            'lat' => 3.1990786,
            'lng' => 101.7372777,
            'status' => 'active'
        ]);

        $a7 = \App\Station::updateOrCreate([
            'code' => 'KJ5',
            'name' => 'Setiawangsa',
            'lat' => 3.1756,
            'lng' => 101.735735,
            'status' => 'active'
        ]);

        $a6 = \App\Station::updateOrCreate([
            'code' => 'KJ6',
            'name' => 'Jelatek',
            'lat' => 3.16734,
            'lng' => 101.73535,
            'status' => 'active'
        ]);

        $a5 = \App\Station::updateOrCreate([
            'code' => 'KJ7',
            'name' => 'Dato Keramat',
            'lat' => 3.165294,
            'lng' => 101.731971,
            'status' => 'active'
        ]);

        $a4 = \App\Station::updateOrCreate([
            'code' => 'KJ8',
            'name' => 'Damai',
            'lat' => 3.1647132,
            'lng' => 101.7219989,
            'status' => 'active'
        ]);

        $a3 = \App\Station::updateOrCreate([
            'code' => 'KJ9',
            'name' => 'Ampang Park',
            'lat' => 3.1598727,
            'lng' => 101.7191001,
            'status' => 'active'
        ]);

        $a2 = \App\Station::updateOrCreate([
            'code' => 'KJ10',
            'name' => 'KLCC',
            'lat' => 3.1585181,
            'lng' => 101.7107294,
            'status' => 'active'
        ]);

        $a1 = \App\Station::updateOrCreate([
            'code' => 'KJ11',
            'name' => 'Kampung Baru',
            'lat' => 3.1613893,
            'lng' => 101.7066655,
            'status' => 'active'
        ]);

        $z = \App\Station::updateOrCreate([
            'code' => 'KJ12',
            'name' => 'Dang Wangi',
            'lat' => 3.15625,
            'lng' => 101.7030085,
            'status' => 'active'
        ]);

        $y = \App\Station::updateOrCreate([
            'code' => 'KJ13',
            'name' => 'Masjid Jamek',
            'lat' => 3.149429,
            'lng' => 101.696276,
            'status' => 'active'
        ]);

        $x = \App\Station::updateOrCreate([
            'code' => 'KJ14',
            'name' => 'Pasar Seni',
            'lat' => 3.142282,
            'lng' => 101.69556,
            'status' => 'active'
        ]);

        $w = \App\Station::updateOrCreate([
            'code' => 'KJ15',
            'name' => 'KL Sentral',
            'lat' => 3.1343385,
            'lng' => 101.6863371,
            'status' => 'active'
        ]);
        
        $v = \App\Station::updateOrCreate([
            'code' => 'KJ16',
            'name' => 'Bangsar',
            'lat' => 3.1276507,
            'lng' => 101.679149,
            'status' => 'active'
        ]);

        $u = \App\Station::updateOrCreate([
            'code' => 'KJ17',
            'name' => 'Abdullah Hukum',
            'lat' => 3.1186831,
            'lng' => 101.672874,
            'status' => 'active'
        ]);

        $t = \App\Station::updateOrCreate([
            'code' => 'KJ18',
            'name' => 'Kerinchi',
            'lat' => 3.114616,
            'lng' => 101.661639,
            'status' => 'active'
        ]);

        $s = \App\Station::updateOrCreate([
            'code' => 'KJ19',
            'name' => 'Universiti',
            'lat' => 3.023361,
            'lng' => 101.572111,
            'status' => 'active'
        ]);

        $r = \App\Station::updateOrCreate([
            'code' => 'KJ20',
            'name' => 'Taman Jaya',
            'lat' => 3.1040439,
            'lng' => 101.6452768,
            'status' => 'active'
        ]);

        $q = \App\Station::updateOrCreate([
            'code' => 'KJ21',
            'name' => 'Asia Jaya',
            'lat' => 3.1043534,
            'lng' => 101.6376961,
            'status' => 'active'
        ]);

        $p = \App\Station::updateOrCreate([
            'code' => 'KJ22',
            'name' => 'Taman Paramount',
            'lat' => 3.1046914,
            'lng' => 101.6231599,
            'status' => 'active'
        ]);

        $o = \App\Station::updateOrCreate([
            'code' => 'KJ23',
            'name' => 'Taman Bahagia',
            'lat' => 3.110708,
            'lng' => 101.6126893,
            'status' => 'active'
        ]);

        $n = \App\Station::updateOrCreate([
            'code' => 'KJ24',
            'name' => 'Kelana Jaya',
            'lat' => 3.1127393,
            'lng' => 101.6042029,
            'status' => 'active'
        ]);

        $m = \App\Station::updateOrCreate([
            'code' => 'KJ25',
            'name' => 'Lembah Subang',
            'lat' => 3.1120902,
            'lng' => 101.5912397,
            'status' => 'active'
        ]);

        $l = \App\Station::updateOrCreate([
            'code' => 'KJ26',
            'name' => 'Ara Damansara',
            'lat' => 3.108643,
            'lng' => 101.586372,
            'status' => 'active'
        ]);

        $k = \App\Station::updateOrCreate([
            'code' => 'KJ27',
            'name' => 'Glenmarie',
            'lat' => 3.0958391,
            'lng' => 101.5904702,
            'status' => 'active'
        ]);

        $j = \App\Station::updateOrCreate([
            'code' => 'KJ28',
            'name' => 'Subang Jaya',
            'lat' => 3.0848155,
            'lng' => 101.5877309,
            'status' => 'active'
        ]);

        $i = \App\Station::updateOrCreate([
            'code' => 'KJ29',
            'name' => 'SS 15',
            'lat' => 3.0757044,
            'lng' => 101.5861308,
            'status' => 'active'
        ]);

        $h = \App\Station::updateOrCreate([
            'code' => 'KJ30',
            'name' => 'SS 18',
            'lat' => 3.0672979,
            'lng' => 101.5863257,
            'status' => 'active'
        ]);

        $g = \App\Station::updateOrCreate([
            'code' => 'KJ31',
            'name' => 'USJ 7',
            'lat' => 3.0547847,
            'lng' => 101.5919635666,
            'status' => 'active'
        ]);

        $f = \App\Station::updateOrCreate([
            'code' => 'KJ32',
            'name' => 'Taipan',
            'lat' =>3.0478609,
            'lng' => 101.5901553,
            'status' => 'active'
        ]);

        $e = \App\Station::updateOrCreate([
            'code' => 'KJ33',
            'name' => 'Wawasan',
            'lat' => 3.0350487,
            'lng' => 101.588104,
            'status' => 'active'
        ]);

        $d = \App\Station::updateOrCreate([
            'code' => 'KJ34',
            'name' => 'USJ 21',
            'lat' => 3.029881,
            'lng' =>101.581711,
            'status' => 'active'
        ]);

        $c = \App\Station::updateOrCreate([
            'code' => 'KJ35',
            'name' => 'Alam Megah',
            'lat' => 3.023361,
            'lng' => 101.572111,
            'status' => 'active'
        ]);

        $b = \App\Station::updateOrCreate([
            'code' => 'KJ36',
            'name' => 'Subang Alam',
            'lat' => 3.009652,
            'lng' => 101.572238,
            'status' => 'active'
        ]);

        $a = \App\Station::updateOrCreate([
            'code' => 'KJ37',
            'name' => 'Putra Heights',
            'lat' => 2.996056,
            'lng' => 101.575556,
            'status' => 'active'            
        ]);
        
    }
}

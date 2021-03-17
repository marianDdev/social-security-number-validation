<?php

namespace Database\Seeders;

use App\Models\CountyCode;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountySeeder extends Seeder
{
    public function run()
    {
        $counties = [
            "Alba",
            "Arad",
            "arges",
            "Bacau",
            "Bihor",
            "Bistrita - Nasaud",
            "Botosani",
            "Braila",
            "Brasov",
            "Buzau",
            "Calarasi",
            "Caras - Severin",
            "Cluj",
            "Constanta",
            "Covasna",
            "Dambovita",
            "Dolj",
            "Galati",
            "Giurgiu",
            "Gorj",
            "Harghita",
            "Hunedoara",
            "Ialomita",
            "Iasi",
            "Ilfov",
            "Maramures",
            "Mehedinti",
            "mures",
            "Neamt",
            "Olt Prahova",
            "Salaj",
            "Satu - Mare",
            "Sibiu",
            "Suceava",
            "Teleorman",
            "Timis",
            "Tulcea",
            "Valcea",
            "Vaslui",
            "Vrancea",
        ];

        $countyCodes  = [];

        for($i=1; $i < 10; $i++) {
            $code = "0" . $i;
            $countyCodes[] = $code;
        }

        $lastCountyCodes = range(10, count($counties));

        foreach($lastCountyCodes as $code) {
            $countyCodes[] = $code;
        }

        foreach($counties as $index => $county) {
            DB::table('county_codes')->insert([
                'county_code' => $countyCodes[$index],
                'county_name' => $county,
                'created_at' => Carbon::now()]);
        }

    }

}

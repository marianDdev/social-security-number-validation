<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CountiesRepository
{

    public function getCountiesCodes(): array
    {
        return collect(DB::table("county_codes")->select("county_code")->get())->pluck("county_code")->toArray();
    }

    public function getCountyByCode($countyCode): array
    {
        return collect(DB::table("county_codes")->select("county_name")->where("county_code", $countyCode)->get())->pluck("county_name")->toArray();
    }
}

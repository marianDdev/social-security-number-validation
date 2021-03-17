<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CountiesRepository
{

    public function getCountiesCodes(): Collection
    {
        return DB::table("county_codes")->select("county_code")->get();
    }

    public function getCountyByCode($countyCode)
    {
        return DB::table("county_codes")->select("county_name")->where("county_code", $countyCode)->find(1);
    }
}

<?php

namespace App\Repositories;

use Database\Seeders\CountySeeder;
use Illuminate\Support\Facades\DB;

class CountiesRepository
{
    private $countySeeder;

    public function __construct(CountySeeder $countySeeder)
    {
        $this->countySeeder = $countySeeder;
    }

    public function getCountiesCodes(): array
    {
        return DB::table("county_codes")->select("county_code")->get();
    }

    public function getCountyByCode($countyCode): string
    {
        return DB::table("county_codes")->select("county_name")->where("county_code", $countyCode)->first();
    }
}

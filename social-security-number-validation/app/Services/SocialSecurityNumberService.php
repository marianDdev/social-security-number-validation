<?php

namespace App\Services;


use Illuminate\Http\Request;

class SocialSecurityNumberService
{
    public function getSocialSecurityNumber(Request $request): array
    {
        $socialSecurityNumber = $request->input("cnp");
        $ssnToArray = str_split($socialSecurityNumber);

        $dto = [];

        $sexCode = $ssnToArray[0];
        $yearCode = $ssnToArray[1] . $ssnToArray[2];
        $monthCode = $ssnToArray[3] . $ssnToArray[4];
        $dayCode = $ssnToArray[5] . $ssnToArray[6];
        $countyCode = $ssnToArray[7] . $ssnToArray[8];
        $uniqueCode = $ssnToArray[9] . $ssnToArray[10]. $ssnToArray[11];
        $controlCode = $ssnToArray[12];

        $dto["socialSecurityNumber"] = $socialSecurityNumber;
        $dto["ssnToArray"] = $ssnToArray;
        $dto["sexCode"] = $sexCode;
        $dto["yearCode"] = $yearCode;
        $dto["monthCode"] = $monthCode;
        $dto["dayCode"] = $dayCode;
        $dto["countyCode"] = $countyCode;
        $dto["uniqueCode"] = $uniqueCode;
        $dto["controlCode"] = $controlCode;

        return $dto;
    }

    public function getYearCodes(): array
    {
        $yearCodes  = [];

        for($i=0; $i < 10; $i++) {
            $code = 0 . $i;
            $yearCodes[] = $code;
        }

        $lastYearCodes = range(10, 99);

        foreach($lastYearCodes as $code) {
            $yearCodes[] = $code;
        }

        return $yearCodes;
    }

    public function getMonthCodes(): array
    {
        $monthCodes  = [10, 11, 12];
        $monthsWithThirtyOne = [01, 03, 05, 07, 10, 12];

        for($i=1; $i < 10; $i++) {
            $code = 0 . $i;
            $monthCodes[] = $code;
        }

        return $monthCodes;
    }

    public function getDayCodes(Request $request): array
    {
        $monthCode = $this->getSocialSecurityNumber($request)["monthCode"];
        $monthsWithThirtyOne = [01, 03, 05, 07, 10, 12];
        $lastDay = 30;

        if(in_array($monthCode, $monthsWithThirtyOne)) {
            $lastDay = 31;
        } elseif ($monthCode = "02") {
            $lastDay = 28;
        }

        $dayCodes  = range(10, $lastDay);

        for($i=1; $i < 10; $i++) {
            $code = 0 . $i;
            $dayCodes[] = $code;
        }

        return $dayCodes;
    }

    public function getUniqueNumbers(): array
    {
        $uniqueNumbers  = [];

        for($i=0; $i < 10; $i++) {
            $code = 00 . $i;
            $uniqueNumbers[] = $code;
        }

        for($i=10; $i < 100; $i++) {
            $code = 0 . $i;
            $uniqueNumbers[] = $code;
        }

        $lastUniqueNumbers = range(100, 999);

        foreach($lastUniqueNumbers as $code) {
            $uniqueNumbers[] = $code;
        }

        return $uniqueNumbers;
    }

    public function getControlCode(Request $request): int
    {
        $divisor= 279146358279;

        $divisorToArray = str_split($divisor);

        $multiplied = [];

        $ssnToArray = $this->getSocialSecurityNumber($request)["ssnToArray"];
        for($i=0; $i<count($ssnToArray) -1; $i++) {
            $multiplied[] = $ssnToArray[$i] * $divisorToArray[$i];
        }

        $modulo = array_sum($multiplied) % 11;

        $controlCode = $modulo = 10 ? 1 : $modulo;

        return $controlCode;
    }
}

<?php

namespace App\Services;

use App\Repositories\CountiesRepository;

class SocialSecurityNumberService
{
    private CountiesRepository $countiesRepository;

    public function __construct(CountiesRepository $countiesRepository)
    {
        $this->countiesRepository = $countiesRepository;
    }

    public function validateSocialSecurityNumber(): array
    {

    $inputToArray = str_split($input);
    $sexCode = $inputToArray[0];
    $yearCode = $inputToArray[1] . $inputToArray[2];
    $monthCode = $inputToArray[3] . $inputToArray[4];
    $dayCode = $inputToArray[5] . $inputToArray[6];
    $countyCode = $inputToArray[7] . $inputToArray[8];
    $uniqueCode = $inputToArray[9] . $inputToArray[10]. $inputToArray[11];
    $controlCode = $inputToArray[12];

    //sex codes
    $sexCodes = [1,2,3,4,5,6,7,8,9];

    //years codes
    $yearCodes  = [];

    for($i=0; $i < 10; $i++) {
        $code = 0 . $i;
        $yearCodes[] = $code;
    }

    $lastYearCodes = range(10, 99);

    foreach($lastYearCodes as $code) {
        $yearCodes[] = $code;
    }

    //months codes
    $monthCodes  = [10, 11, 12];
    $monthsWithThirtyOne = [01, 03, 05, 07, 10, 12];

    for($i=1; $i < 10; $i++) {
        $code = 0 . $i;
        $monthCodes[] = $code;
    }

    //days codes
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

    //counties codes
    $countyCodes = $this->countiesRepository->getCountiesCodes();


    //unique numbers NNN
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

    //control code

    $divisor= 279146358279;

    $divisorToArray = str_split($divisor);

    $multiplied = [];

    for($i=0; $i<count($inputToArray) -1; $i++) {
        $multiplied[] = $inputToArray[$i] * $divisorToArray[$i];
    }

    $modulo = array_sum($multiplied) % 11;

    $controlCode = $modulo = 10 ? 1 : $modulo;


//
//
//    $validated = $this->validator->make(
//        [$sexCode, $yearCode, $monthCode, $dayCode, $countyCode,$uniqueCode,$controlCode],
//        [
//            $sexCode => ["digits:1", Rule::in($sexCodes)],
//            $yearCode => ["digits:2", Rule::in($yearCodes)],
//            $monthCode => ["digits:2", Rule::in($monthCodes)],
//            $dayCode => ["digits:2", Rule::in($dayCodes)],
//            $countyCode => ["digits:2", Rule::in($countiesCodes)],
//            $uniqueCode => ["digits:3", Rule::in($uniqueNumbers)],
//            $controlCode => ["digits:1"],
//        ],
//        [
//            $sexCode."digits" => "sex code must be one digit",
//            $sexCode."in" => "sex code must be one of this values: :values",
//            $yearCode."digits" => "year code must be two digits",
//            $yearCode."in" => "year code must be one of this values: :values",
//            $monthCode."digits" => "month code must be two digits",
//            $monthCode."in" => "month code must be one of this values: :values",
//            $dayCode."digits" => "day code must be two digits",
//            $dayCode."in" => "day code must be one of this values: :values",
//            $countyCode."digits" => "county code must be two digits",
//            $countyCode."in" => "county code must be one of this values: :values",
//            $uniqueCode."digits" => "unique number must be three digits",
//            $uniqueCode."in" => "unique  number must be one of this values: :values",
//            $controlNumber."digits" => "contro number must be one digit",
//        ]
//    )->validate();
//

//        $this->validator->validate(
//            $input,
//            [
//                "cnp" => ["required", "digits:13"]
//            ],
//            [
//                "cnp.required" => "te rog introdu cnp",
//                "cnp.digits" => "cnp trebuie sa fie format din :digits cifre"
//            ]
//        );

        $errors = [];

        if(mb_strlen($sexCode) != 1 || !in_array($sexCode, $sexCodes)) {
            $errors[] = "Introdu un cod de sex valid";
        }

        if(mb_strlen($yearCode) != 2 || !in_array($yearCode, $yearCodes)) {
            $errors[] = "Introdu cod an nastere valid";
        }

        if(mb_strlen($monthCode) != 2 || !in_array($monthCode, $monthCodes)) {
            $errors[] = "Introdu cod luna nastere valid";
        }

        if(mb_strlen($dayCode) != 2 || !in_array($dayCode, $dayCodes)) {
            $errors[] = "Introdu cod zi nastere valid";
        }

        if(mb_strlen($countyCode) != 2 || !in_array($countyCode, $countyCodes)) {
            $errors[] = "Introdu cod judet valid";
        }

        if(mb_strlen($uniqueCode) != 3 || !in_array($uniqueCode, $uniqueNumbers)) {
            $errors[] = "Introdu cod unic valid";
        }

        if(mb_strlen($controlCode) != 1) {
            $errors[] = "Introdu cod control valid";
        }

    $dto = [];

    $county = $this->countiesRepository->getCountyByCode($countyCode);
    $sex = null;
    $maleCodes = [1, 3, 5, 7];
    $femaleCodes = [2, 4, 6, 8];

    if(in_array($sexCode, $maleCodes)) {
        $sex === "masculin";
    } elseif (in_array($sexCode, $femaleCodes)) {
        $sex === "feminin";
    } elseif ($sexCode === 9) {
        $sex === "strain";
    }

    $year = null;

    if($sexCode === 1 || $sexCode === 2) {
        $year = "19" . $yearCode;
    } elseif ($sexCode === 3 || $sexCode === 4) {
        $year = "18" . $yearCode;
    } elseif ($sexCode === 5 || $sexCode === 6) {
        $year = "20" . $yearCode;
    }

    $birthDay = $dayCode ."/". $monthCode . "/". $year;

    $dto["errors"] = $errors;
    $dto["birthDay"] = $birthDay;
    $dto["county"] = $county;
    $dto["sex"] = $sex;

    return $dto;

    }

}

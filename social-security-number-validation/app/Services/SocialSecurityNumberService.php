<?php

namespace App\Services;

use App\Repositories\CountiesRepository;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SocialSecurityNumberService
{
    private $validator;
    private $countiesRepository;

    public function __construct(Validator $validator, CountiesRepository $countiesRepository)
    {
        $this->validator = $validator;
        $this->countiesRepository = $countiesRepository;
    }

    public function validateSocialSecurityNumber(string $input): array
    {

    $inputToArray = str_split($input);

    //sex codes
    $sexCodes = [1,2,3,4,5,6,7,8,9];
    $sexCode = $inputToArray[0];


    //years codes
    $yearsCodes  = [];

    for($i=0; $i < 10; $i++) {
        $code = 0 . $i;
        $yearsCodes[] = $code;
    }

    $lastYearCodes = range(10, 99);

    foreach($lastYearCodes as $code) {
        $yearsCodes[] = $code;
    }

    $yearCode = $inputToArray[1] . $inputToArray[2];

    //months codes
    $monthCodes  = [10, 11, 12];
    $monthsWithThirtyOne = [01, 03, 05, 07, 10, 12];

    for($i=1; $i < 10; $i++) {
        $code = 0 . $i;
        $monthCodes[] = $code;
    }

    $monthCode = $inputToArray[3] . $inputToArray[4];

    //days codes
    $lastDay = 30;
    if(in_array($monthCode, $monthsWithThirtyOne)) {
        $lastDay = 31;
    } elseif ($monthCode = "02") {
        $lastDay = 28;
    }

    $daysCodes  = range(10, $lastDay);

    for($i=1; $i < 10; $i++) {
        $code = 0 . $i;
        $daysCodes[] = $code;
    }

    //counties codes
    $countiesCodes = $this->countiesRepository->getCountiesCodes();
    $countyCode = $inputToArray[9] . $inputToArray[10] . $inputToArray[11];

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

    $controlNumber = $modulo = 10 ? 1 : $modulo;



    $validated = $this->validator->make(
        $input,
        [
            "sexCode" => ["digits:1", Rule::in($sexCodes)],
            "yearCode" => ["digits:2", Rule::in($yearsCodes)],
            "monthCode" => ["digits:2", Rule::in($monthCodes)],
            "dayCode" => ["digits:2", Rule::in($daysCodes)],
            "countyCode" => ["digits:2", Rule::in($countiesCodes)],
            "uniqueNumber" => ["digits:3", Rule::in($uniqueNumbers)],
            "controlNumber" => ["digits:1"],
        ],
        [
            "sexCode.digits" => "sex code must be one digit",
            "sexCode.in" => "sex code must be one of this values: :values",

            "yearCode.digits" => "year code must be two digits",
            "yearCode.in" => "year code must be one of this values: :values",

            "monthCode.digits" => "month code must be two digits",
            "monthCode.in" => "month code must be one of this values: :values",

            "dayCode.digits" => "day code must be two digits",
            "dayCode.in" => "day code must be one of this values: :values",

            "countyCode.digits" => "county code must be two digits",
            "countyCode.in" => "county code must be one of this values: :values",

            "uniqueNumber.digits" => "unique number must be three digits",
            "uniqueNumber.in" => "unique  number must be one of this values: :values",

            "controlNumber.digits" => "contro number must be one digit",
        ]
    )->validate();

    [
    "sexCode" => $sexCode,
    "yearCode" => $yearCode,
    "monthCode" => $monthCode,
    "dayCode" => $dayCode,
    "countyCode" => $countyCode,
    "uniqueNumber" => $uniqueNumber,
    "controlNumber" => $controlNumber
    ] = $validated;

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

    $dto["birthDay"] = $birthDay;
    $dto["county"] = $county;
    $dto["sex"] = $sex;

    return $dto;



    }

}

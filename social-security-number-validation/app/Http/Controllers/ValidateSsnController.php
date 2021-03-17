<?php

namespace App\Http\Controllers;

use App\Repositories\CountiesRepository;
use App\Services\SocialSecurityNumberService;
use App\Services\SsnValidationErrorsService;
use Illuminate\Http\Request;

class ValidateSsnController extends Controller
{
    private CountiesRepository $countiesRepository;
    private SocialSecurityNumberService $socialSecurityNumberService;
    private SsnValidationErrorsService $ssnValidationErrorsService;

    public function __construct(
        CountiesRepository $countiesRepository,
        SocialSecurityNumberService $socialSecurityNumberService,
        SsnValidationErrorsService $ssnValidationErrorsService
    )
    {
        $this->countiesRepository = $countiesRepository;
        $this->socialSecurityNumberService = $socialSecurityNumberService;
        $this->ssnValidationErrorsService = $ssnValidationErrorsService;
    }
    public function getValidationView() {

        return view("validate_ssn");
    }


    public function validateSsn(Request $request) {
        $request->validate([
            "cnp" => ["required", "digits:13"]]);

    $ssnDto = $this->socialSecurityNumberService->getSocialSecurityNumber($request);

    //custom error messages
    $errorMessages = $this->ssnValidationErrorsService->getErrorMessages($request);


    $county = $this->countiesRepository->getCountyByCode($ssnDto["countyCode"]);
    $sex = null;
    $maleCodes = [1, 3, 5, 7];
    $femaleCodes = [2, 4, 6, 8];

    if(in_array($ssnDto["sexCode"], $maleCodes)) {
        $sex = "masculin";
    } elseif (in_array($ssnDto["sexCode"], $femaleCodes)) {
        $sex = "feminin";
    } elseif ($sexCode = 9) {
        $sex = "strain";
    }

    $year = null;

    if($sexCode = 1 || $sexCode = 2) {
        $year = "19" . $ssnDto["yearCode"];
    } elseif ($sexCode = 3 || $sexCode = 4) {
        $year = "18" . $yearCode;
    } elseif ($sexCode = 5 || $sexCode = 6) {
        $year = "20" . $yearCode;
    }

    $birthDay = $ssnDto["dayCode"] ."/". $ssnDto["monthCode"] . "/". $year;

        return view("validation_response")->with([
            "socialSecurityNumber" => $ssnDto["socialSecurityNumber"],
            "errorMessages" => $errorMessages,
            "birthDay" => $birthDay,
            "county" => $county,
            "sex" => $sex
            ]);
    }

}

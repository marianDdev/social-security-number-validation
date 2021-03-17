<?php

namespace App\Services;

use App\Repositories\CountiesRepository;
use Illuminate\Http\Request;

class SsnValidationErrorsService
{
    private SocialSecurityNumberService $socialSecurityNumberService;
    private CountiesRepository $countiesRepository;

    public function __construct(SocialSecurityNumberService $socialSecurityNumberService, CountiesRepository $countiesRepository)
    {
        $this->socialSecurityNumberService = $socialSecurityNumberService;
        $this->countiesRepository = $countiesRepository;
    }

    public function getErrorMessages(Request $request): array
    {
        $errorMessages = [];
        //sex codes
        $sexCodes = [1,2,3,4,5,6,7,8,9];

        //year codes
        $yearCodes = $this->socialSecurityNumberService->getYearCodes();

        //months codes
        $monthCodes = $this->socialSecurityNumberService->getMonthCodes();

        //days codes
        $dayCodes = $this->socialSecurityNumberService->getDayCodes($request);

        //counties codes
        $countyCodes = $this->countiesRepository->getCountiesCodes();


        //unique numbers NNN
        $uniqueNumbers = $this->socialSecurityNumberService->getUniqueNumbers();

        //control code
        $controlCode = $this->socialSecurityNumberService->getControlCode($request);

        $ssnCodesDto = $this->socialSecurityNumberService->getSocialSecurityNumber($request);

        if(mb_strlen($ssnCodesDto["sexCode"]) != 1 || !in_array($ssnCodesDto["sexCode"], $sexCodes)) {
            $errorMessages[] = "Introdu un cod de sex valid";
        }
        if(mb_strlen($ssnCodesDto["yearCode"]) != 2 || !in_array($ssnCodesDto["yearCode"], $yearCodes)) {
            $errorMessages[] = "Introdu cod an nastere valid";
        }
        if(mb_strlen($ssnCodesDto["monthCode"]) != 2 || !in_array($ssnCodesDto["monthCode"], $monthCodes)) {
            $errorMessages[] = "Introdu cod luna nastere valid";
        }
        if(mb_strlen($ssnCodesDto["dayCode"]) != 2 || !in_array($ssnCodesDto["dayCode"], $dayCodes)) {
            $errorMessages[] = "Introdu cod zi nastere valid";
        }
        if(mb_strlen($ssnCodesDto["countyCode"]) != 2 || !in_array($ssnCodesDto["countyCode"], $countyCodes)) {
            $errorMessages[] = "Introdu cod judet valid";
        }
        if(mb_strlen($ssnCodesDto["uniqueCode"]) != 3 || !in_array($ssnCodesDto["uniqueCode"], $uniqueNumbers)) {
            $errorMessages[] = "Introdu cod unic valid";
        }
        if(mb_strlen($controlCode) != 1 || $modulo = 10 && $controlCode != 1) {
            $errorMessages[] = "Introdu cod control valid";
        }

        return $errorMessages;
    }

}

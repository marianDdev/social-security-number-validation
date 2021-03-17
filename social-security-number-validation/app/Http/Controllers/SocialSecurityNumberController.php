<?php

namespace App\Http\Controllers;

use App\Services\SocialSecurityNumberService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialSecurityNumberController
{
    private $request;
    private $response;
    private $socialSecurityNumberService;

    public function __construct(Request $request, ResponseFactory $response, SocialSecurityNumberService $socialSecurityNumberService)
    {
        $this->request = $request;
        $this->response = $response;
        $this->socialSecurityNumberService = $socialSecurityNumberService;
    }

    public function __invoke(): JsonResponse
    {
      

       return $this->response->success($this->socialSecurityNumberService->validateSocialSecurityNumber($this->request->input()));
    }

}

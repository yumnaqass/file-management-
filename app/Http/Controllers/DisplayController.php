<?php

namespace App\Http\Controllers;

use App\Http\Repositories\DisplayRepository;
use App\Http\Repositories\FileRepository;
use App\Http\Repositories\GroupRepository;
use App\Http\Requests\deleteFileRequest;
use App\Http\Requests\DeleteGroupRequest;
use App\Http\Requests\GetAllUserInGroupRequest;
use App\Http\Services\DisplayService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class DisplayController extends Controller
{
    use GeneralTrait;

    private $service;

    public function __construct(DisplayService $displayService)
    {
        $this->service = $displayService;

    }

    public function getMyFile()
    {
        try {
            return $this->service->getMyFile();
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function getStateFile(deleteFileRequest $request)
    {
        try { 
            if ($this->service->checkStateFile($request)) {
                return $this->returnData('Data', $this->service->getUserCheck_In($request));
            }
            return $this->returnData('Data',null);
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }


    public function getMyGroup()
    {
        try {
            return $this->service->getMyGroup();

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function getGroupFile(DeleteGroupRequest $request){
        try {
            return  $this->service->getGroupFile($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }

    }

    public function getAllUserInSystem(){
        try {
            return $this->service->getAllUserInSystem();
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function getAllUserInGroup(DeleteGroupRequest $allUserInGroupRequest){
        try {
            return $this->service->getAllUserInGroup($allUserInGroupRequest);
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function getAllFileCheck_InGroupForUser(DeleteGroupRequest $request){
        try {
            return $this->service->getAllFileCheck_InGroupForUser($request);
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

}
  
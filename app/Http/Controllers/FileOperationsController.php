<?php

namespace App\Http\Controllers;

use App\Http\Requests\deleteFileRequest;
use App\Http\Requests\FileOperationRequest;
use App\Http\Requests\saveFileRequest;
use App\Http\Services\FileOperationServices;
use App\Traits\GeneralTrait;

use Illuminate\Http\Request;

class FileOperationsController extends Controller
{
    use GeneralTrait;

    private $service;

    public function __construct(FileOperationServices $fileOperationServices)
    {
       $this->service = $fileOperationServices;
    }

    public function check_in(FileOperationRequest $request)
    {
        try {
            return $this->service->check_in($request);
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function readFile(deleteFileRequest $request)
    {
        try {
            return $this->service->readFile($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function saveFile(saveFileRequest $request){
        try {
            return $this->service->saveFile($request);
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function check_outFile(deleteFileRequest $request){
        try {
            return $this->service->check_outFile($request);
        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function DownloadFile(deleteFileRequest $request){
        try{
            return $this->service->DownloadFile($request);
        } catch (\Exception $exp) {
            return $this->returnError(100 , 'try again');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Repositories\FileRepository; 
use App\Http\Repositories\HistoryRepository;
use App\Http\Requests\AddFileRequest;
use App\Http\Requests\deleteFileRequest;
use App\Http\Services\FileServices;
use App\Http\Utiles\UploadFile;
use App\Models\File;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class FileController extends Controller
{
    use GeneralTrait;

    private $fileService;

    public function __construct(FileServices $fileService)
    {

        $this->fileService = $fileService;
    }

    public function addFile(AddFileRequest $request)
    {
        try {
            return $this->fileService->addFile($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function deleteFile(deleteFileRequest $request)
    {
        try {
            return $this->fileService->deleteFile($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }


}

<?php

namespace App\Http\Services;

use App\Http\Repositories\FileOperationRepository;
use App\Http\Repositories\FileRepository;
use App\Http\Repositories\HistoryRepository;
use App\Http\Requests\deleteFileRequest;
use App\Http\Requests\FileOperationRequest;
use App\Http\Requests\saveFileRequest;
use App\Http\Utiles\GETFile;
use App\Http\Utiles\UploadFile;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;

class FileOperationServices
{
    use GeneralTrait ;

    private $repo, $repoF ,$history;

    public function __construct(FileOperationRepository $fileOperations, FileRepository $fileRepository,HistoryRepository $historyRepository)
    {
        $this->repo = $fileOperations;
        $this->repoF = $fileRepository;
        $this->history = $historyRepository;
    }

    public function check_in(FileOperationRequest $request)
    {
        $validated = $request->validated();

        if ($this->repo->checkStateFiles($validated['file_ids'])) {
            return $this->returnError(403, 'A reserved file cannot be reserved');
        }

        $user = JWTAuth::parseToken()->authenticate();

        $file = $this->repo->check_in($validated['file_ids'], $validated['group_id'], $user->id);

        for ($i = 0; $i < sizeof($validated['file_ids']); $i++) {
            $this->history->addOperation($file[$i], 'check_in', $user->id);
        }
        return $this->returnSuccessMessage('check_in successfully');
    }

    public function readFile(deleteFileRequest $request)
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();
        if (!$this->repoF->checkStateFile($validated['file_id'],$user->id)->isEmpty()) {
            return $this->returnError(403, 'can not be read now');
        }
 
        $user = JWTAuth::parseToken()->authenticate();

        if(Cache::has($validated['file_id'])){
            $file = Cache::get($validated['file_id']);
            $file =  asset('files'.'/' . $file);
        }else{
        $file = GETFile::get($validated['file_id'], 'files');
        Cache::put($validated['file_id'] , $this->repoF->getById($validated['file_id'])->path , now()->addDay());
    }
        $this->history->addOperation($validated['file_id'], 'read', $user->id);
        return $this->returnData('Data', $file);
    }

    public function saveFile(saveFileRequest $request)
    {
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();

        if ($this->repoF->checkStateFileUser($validated['file_id'],$user->id)->isEmpty()) {
            return $this->returnError(403, 'can not be save now');
        }

        $name = $request->file('path')->getClientOriginalName();

        UploadFile::delete($name, 'files');
        $newpath = UploadFile::upload($request->file('path'), 'files');

        if(Cache::has($validated['file_id'])){
            Cache::forget($validated['file_id']);
            Cache::put($validated['file_id'] , $newpath , now()->addDay());
        }
        $this->history->addOperation($validated['file_id'], 'save', $user->id);
        return $this->returnSuccessMessage('saved successfully');
    }

    public function check_outFile(deleteFileRequest $request){
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();

        if ($this->repoF->checkStateFileUser($validated['file_id'],$user->id)->isEmpty()) {
            return $this->returnError(403, 'can not check_out');
        }

        $this->repoF->check_outFile($validated['file_id'],$user->id);

        $this->history->addOperation($validated['file_id'], 'check_out', $user->id);
        return $this->returnSuccessMessage('check_out successfully');
    }

    public function DownloadFile(deleteFileRequest $request){
        $validated = $request->validated();
        $user = JWTAuth::parseToken()->authenticate();

        if ($this->repoF->checkStateFiledelete($validated['file_id'],$user->id)) {
            return $this->returnError(403, "the file in check_in status");
        }

        if(Cache::has($validated['file_id'])){
            $file = Cache::get($validated['file_id']);
            return response()->download(public_path("files/".$this->repoF->getById($validated['file_id'])->path));
        }
        Cache::put($validated['file_id'] , $this->repoF->getById($validated['file_id'])->path , now()->addDay());
        return response()->download(public_path("files/". $this->repoF->getById($validated['file_id'])->path));;
    }


        
    }

<?php


namespace App\Http\Services;


use App\Http\Repositories\FileRepository;
use App\Http\Repositories\HistoryRepository;
use App\Http\Requests\AddFileRequest;
use App\Http\Requests\deleteFileRequest;
use App\Http\Utiles\UploadFile;
use App\Traits\GeneralTrait;
use Exception;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;
  
class FileServices
{
    use GeneralTrait;
    private $repo, $log;

    public function __construct(FileRepository $fileRepository, HistoryRepository $historyRepository)
    {
        $this->repo = $fileRepository;
        $this->log = $historyRepository;
    }

      
    public function addFile(AddFileRequest $request)
    {
        $validated = $request->validated();

        $user = JWTAuth::parseToken()->authenticate();

        if($request->path->getSize() > 10485760) { // 10 MB  
            return $this->returnError(403, "File size exceeds the limit ");
        }

        if (!$this->repo->canAddNumber($user->id)) {
            return $this->returnError(403, "you are have max file number");
        }

        // the file already exists
        if ($this->repo->checkFileName($request->file('path')->getClientOriginalName())) {
            return $this->returnError(403, "the file name already existed");
        }

        $validated['user_id'] = $user->id;
        $validated['path'] = $request->file('path')->getClientOriginalName();

        $file = $this->repo->store($validated);
        $this->log->addOperation($file->id, 'create', $user->id);
 
        //move file to directory
        UploadFile::upload($request->file('path'), 'files');

        //add to cache 
        Cache::put($file->id, $file->path , now()->addDay());

        return  $this->returnSuccessMessage('added successfully');
  
    }

    public function deleteFile(deleteFileRequest $request){
        $validated = $request->validated();

        $user = JWTAuth::parseToken()->authenticate();

        // check owner file
        if (!$this->repo->checkOwnerFile($validated['file_id'],$user->id)) {
            return $this->returnError(403, "you not owner this file ,file didnt exists... ");
        }

          // check status file
        if ($this->repo->checkStateFiledelete($validated['file_id'],$user->id)) {
            return $this->returnError(403, "the file in check_in status");
        }
        $file = $this->repo->delete($validated['file_id']);
         //delete file
        UploadFile::delete($file->path, 'files');
        return  $this->returnSuccessMessage('delete successfully');
    }
}

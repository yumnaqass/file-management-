<?php

namespace App\Http\Controllers;

use App\Http\Repositories\HistoryRepository;
use App\Http\Requests\deleteFileRequest;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    use GeneralTrait;

    private $log;

    public function __construct(HistoryRepository $historyRepository)
    {
        $this->log = $historyRepository;
    }

    public function getHistory(deleteFileRequest $request){
        $validated = $request->validated();
        return $this->returnData('Data',$this->log->getHistory($validated['file_id']));

    }
    
}

<?php


namespace App\Http\Repositories;


use App\Models\Log;
use Carbon\Carbon;

class HistoryRepository
{
    private $log;

    public function __construct(Log $log)
    {
        return $this->log = $log;
    }


    public function getHistory($file_id)
    {
        return $this->log->where('file_id',$file_id)
            ->with('user')
            ->orderBy('created_at','desc')
            ->paginate(30);

    }
    
    public function addOperation($file_id, $operation, $user_id)
    {
        return $this->log->create([
            'file_id' => $file_id,
            'user_id' => $user_id,
            'date' => Carbon::now(),
            'operation' => $operation,
        ]);
    }

}

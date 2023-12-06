<?php

namespace App\Http\Repositories;

use App\Models\Reservation;

class FileOperationRepository
{

    private $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation =$reservation;
    }

    public function checkStateFiles(array $data){
        return $this->reservation->whereIn('file_id',$data)
            ->exists();
    }

    public function check_in(array $file_ids,$group_id,$user_id){

        for($i=0 ; $i<sizeof($file_ids) ;$i++)
        {
            $this->reservation = new Reservation();
            $this->reservation->file_id = $file_ids[$i];
            $this->reservation->user_id = $user_id;
            $this->reservation->group_id = $group_id;
            $this->reservation->save();
        }
        return $file_ids;

    }
}
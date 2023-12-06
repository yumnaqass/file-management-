<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File_Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'group_id',
    ];
    public function file()
    {
        return $this->belongsTo(File_Group::class, 'file_id','id');
    }

}

<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'path_to',
        'name_file',
        'type',
    ];


}
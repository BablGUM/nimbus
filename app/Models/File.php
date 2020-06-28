<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * @package App\Models
 * @property string name
 * @property string type
 * @property string extension
 * @property int user_id
 */
class File extends Model
{
    protected $fillable = [
        'name', 'type', 'extension', 'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['hash', 'name'];

    public function path()
    {
        return "files/$this->hash";
    }
}

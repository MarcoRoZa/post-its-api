<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['group_id', 'title', 'description'];

    public function files()
    {
        return $this->hasMany(File::class);
    }
}

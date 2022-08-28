<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['group_id', 'title', 'description'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}

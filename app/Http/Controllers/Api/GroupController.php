<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Models\Group;

class GroupController extends Controller
{
    public function index()
    {
        return GroupResource::collection(Group::all());
    }
}

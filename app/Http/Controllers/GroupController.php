<?php

namespace App\Http\Controllers;

use App\Group;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show', 'fetch');
        $this->middleware('permission:create groups')->except('show', 'fetch');
    }

    public function fetch(): array
    {
        return ['data' => Group::all()];
    }

    public function show(Group $group): \Illuminate\View\View
    {
        $group->load([
            'parent',
            'children',
            'languages'
        ]);
        return view('groups.show', ['group' => $group]);
    }

    public function create(): Group
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);
        return Group::create($data);
    }
}

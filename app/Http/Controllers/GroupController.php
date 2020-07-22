<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show', 'index');
        $this->middleware('permission:create groups')->except('show', 'index');
    }

    public function index()
    {
        return ['data' => Group::all()];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\View\View
     */
    public function show(Group $group)
    {
        $group->load([
            'parent',
            'children',
            'languages' => function ($query) {
                return $query->positioned();
            }
        ]);
        return view('groups.show', ['group' => $group]);
    }

    public function create()
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);
        return Group::create($data);
    }
}

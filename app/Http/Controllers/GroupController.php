<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $group->load(['languages' => function ($query) {
            return $query->positioned();
        }]);
        return view('groups.show', ['group' => $group]);
    }

    public function create()
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);
        $group = Group::create($data);
        return redirect($group->url);
    }
}

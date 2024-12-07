<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appeal;

class AppealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('appeal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.appealShow', ['appeal' => Appeal::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $appeal = Appeal::findOrFail($id);
        $status = $appeal->status;
        $appeal->status = !$status;
        $appeal->save();

        //get all the appeals of the user and if the status of all are true, set the ban tu false
        $ban = $appeal->ban;
        $user = $ban->user;
        if( $ban->appeals->where('status', false)->count() == 0){
            $ban->active = false;
            $ban->save();
        }else{
            $ban->active = true;
            $ban->save();
        }
        return redirect()->route('admin.bans')->with('success', 'Appeal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
    }
    
}

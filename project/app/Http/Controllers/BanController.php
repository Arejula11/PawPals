<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ban;

class BanController extends Controller
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
        return view('ban.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id'
        ]);


        $ban = new Ban($validated);
        $ban->save();

        return redirect()->route('admin.home')->with('success', 'Ban created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
        return view('admin.banShow', ['ban' => Ban::findOrFail($id)]);
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
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show all bans
     */
    public function showAll(){
        $loguser = auth()->user();
        $this->authorize('admin', $loguser);
        $bans = Ban::all();
        return view('admin.showAllBans', ['bans' => $bans]);
    }

    
}

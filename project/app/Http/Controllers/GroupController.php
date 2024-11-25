<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userGroups = auth()->user()->groups; // Assuming you have a relationship defined on the User model like `groups()`
        return view('groups.index', compact('userGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'is_public' => 'required|boolean',
            'img_id' => 'required|integer',
        ]);

        $group = new Group($validated);
        $group->owner_id = auth()->id();
        $group->save();

        $group->participants()->attach(auth()->id());

        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    public function storeMessage(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);
    
        $message = $group->messages()->create([
            'content' => $validated['content'],
            'sender_id' => auth()->id(),
            'date' => now(),
        ]);
    
        return response()->json($message);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $group = Group::with(['participants', 'messages'])->findOrFail($id);
        return view('groups.show', compact('group'));
    }

    public function join(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $group->participants()->attach(auth()->id());

        return redirect()->route('groups.show', $id)->with('success', 'You joined the group.');
    }

    public function search()
    {
        // Logic to retrieve groups, you can use Eloquent or DB queries here
        $groups = Group::where('is_public', true)->get(); // Example query to get public groups
        return view('groups.search', compact('groups')); // Create a new view for this search result page
    }

    public function messages($id)
    {
        $group = Group::with('messages')->findOrFail($id); // Fetch only group messages
        return view('groups.messages', compact('group'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);

        // Ensure only the owner can edit
        if (auth()->id() !== $group->owner_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('groups.edit', compact('group'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        // Ensure only the owner can update
        if (auth()->id() !== $group->owner_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $group->update($validated);

        return redirect()->route('groups.show', $id)->with('success', 'Group updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

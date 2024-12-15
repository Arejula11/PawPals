<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\GroupParticipant;
class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $userGroups = auth()->user()->groups;
        return view('groups.index', compact('userGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
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
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);
    
        $message = $group->messages()->create([
            'content' => $validated['content'],
            'sender_id' => auth()->id(),
            'date' => now(),
        ]);

        // Return the message data as a JSON response
        return redirect()->route('groups.messages', $group->id)->with('success', 'Message sent.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $group = Group::with(['participants', 'messages'])->findOrFail($id);
        return view('groups.show', compact('group'));
    }

    public function join(Request $request, $id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $group = Group::findOrFail($id);
        $group->participants()->attach(auth()->id());

        return redirect()->route('groups.show', $id)->with('success', 'You joined the group.');
    }

    
    public function removeParticipant($groupId, $userId) {
        GroupParticipant::where('group_id', $groupId)->where('user_id', $userId)->delete();
        return response()->json(['success' => true]);
    }

    public function search()
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        // Logic to retrieve groups, you can use Eloquent or DB queries here
        $groups = Group::where('is_public', true)->get();
        return view('groups.search', compact('groups'));
    }

    public function messages($id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $group = Group::with('messages')->findOrFail($id);
        return view('groups.messages', compact('group'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $group = Group::findOrFail($id);

        if(auth()->user()->admin){
            return view('admin.groupsEdit', compact('group'));
        }else{
            // Ensure only the owner can edit
            if (auth()->id() !== $group->owner_id ) {
                abort(403, 'Unauthorized action.');
            }
            return view('groups.edit', compact('group'));
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if(auth()->user()->admin){
            $group->update($validated);
            return redirect()->route('admin.home')->with('success', 'Group deleted successfully.');
        }else{
            if (auth()->id() !== $group->owner_id) {
                abort(403, 'Unauthorized action.');
            }
            $group->update($validated);
            return redirect()->route('groups.show', $id)->with('success', 'Group updated successfully.');

        }

        




        return redirect()->route('groups.show', $id)->with('success', 'Group updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $group = Group::findOrFail($id);
        if (auth()->user()->admin) {
            $group->delete();
            return redirect()->route('admin.home')->with('success', 'Group deleted successfully.');
        }else {
            if (auth()->id() !== $group->owner_id) {
                abort(403, 'Unauthorized action.');
            }
            return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
        }

        
    }
}

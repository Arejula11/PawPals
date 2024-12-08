<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function updateMessage(Request $request)
    {
        $loguser = auth()->user();
        $this->authorize('banned', $loguser);
        $validated = $request->validate([
            'id' => 'required|exists:messages,id',
            'content' => 'required|string|max:255',
            'sender_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        // Find the message by ID
        $message = Message::find($validated['id']);
        
        // Update the message with the new data
        $message->content = $validated['content'];
        $message->date = now();
        $message->sender_id = $validated['sender_id'];
        $message->group_id = $validated['group_id'];
        
        // Save the updated message
        $message->save();

        // Return a response (you can redirect or return a success message)
        return response()->json(['success' => true, 'message' => 'Message updated successfully!']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $loguser = auth()->user();
        // $this->authorize('banned', $loguser);

        $id = auth()->user()->id;

        $groupMemberNotifications = DB::table('notification') // Ensure the table name is correct here
        ->join('group_member_notification', 'notification.id', '=', 'group_member_notification.notification_id')
        ->where('notification.user_id', $id)
        ->get();

        $postNotifications = DB::table('notification') // Ensure the table name is correct here
        ->join('post_notification', 'notification.id', '=', 'post_notification.notification_id')
        ->where('notification.user_id', $id)
        ->get();

        $commentNotifications = DB::table('notification') // Ensure the table name is correct here
        ->join('comment_notification', 'notification.id', '=', 'comment_notification.notification_id')
        ->where('notification.user_id', $id)
        ->get();


        $groupOwnerNotifications = DB::table('notification') // Ensure the table name is correct here
        ->join('group_owner_notification', 'notification.id', '=', 'group_owner_notification.notification_id')
        ->where('notification.user_id', $id)
        ->get();

        return view('notification.index', compact('groupMemberNotifications', 'postNotifications', 'commentNotifications', 'groupOwnerNotifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // $loguser = auth()->user();
        // $this->authorize('banned', $loguser);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // $loguser = auth()->user();
        // $this->authorize('banned', $loguser);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        // $loguser = auth()->user();
        // $this->authorize('banned', $loguser);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        // $loguser = auth()->user();
        // $this->authorize('banned', $loguser);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // $loguser = auth()->user();
        // $this->authorize('banned', $loguser);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        // $loguser = auth()->user();
        // $this->authorize('banned', $loguser);
    }
}

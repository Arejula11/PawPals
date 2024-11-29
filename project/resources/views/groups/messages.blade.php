@extends('layouts.app')

@section('content')
<div class="group-messages">
    <h1>Messages in {{ $group->name }}</h1>
    <div class="messages-display">
        <ul id="message-list">
            @foreach($group->messages as $message)
                <li class="{{ $message->sender_id === auth()->user()->id ? 'user-message' : 'other-message' }}">
                    <p class="message-content">{{ $message->content }}</p>
                    <p class="message-date">{{ $message->date }}</p>
                </li>
            @endforeach
        </ul>
    </div>

    <form id="message-form" action="{{ route('groups.messages.store', $group->id) }}" method="POST">
        @csrf
        <input type="hidden" name="id" id="message-id" value="">
        <textarea name="content" id="message-description" required placeholder="Write a message..."></textarea> 
        <input type="hidden" name="sender_id" id="sender-id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="group_id" id="group-id" value="{{ $group->id }}">

        <button type="submit" id="button" class="send-butt">Send</button>
    </form>
    
    <div id="message-status"></div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function scrollToBottom() {
            const msgbox = document.querySelector('.messages-display');
            if (msgbox) {
                msgbox.scrollTop = msgbox.scrollHeight;
                console.log("scrollToBottom executed: ", msgbox.scrollTop, msgbox.scrollHeight);
            } else {
                console.error("messages-display element not found");
            }
        }

        // Scroll to the bottom when the page loads
        scrollToBottom();  
        
    });
</script>
@endsection

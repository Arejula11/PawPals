@extends('layouts.app')

@section('content')
<div class="group-messages">
    <h1>Messages in {{ $group->name }}</h1>

    <ul id="message-list">
        @foreach($group->messages as $message)
            <li>{{ $message->content }} - {{ $message->date }}</li>
        @endforeach
    </ul>

    <form id="message-form" action="{{ route('groups.messages.store', $group->id) }}" method="POST">
        @csrf
        <textarea name="content" id="message-content" rows="3" placeholder="Write a message..."></textarea>
        <button type="submit" class="button">Send</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const form = document.getElementById('message-form');
    const messageList = document.getElementById('message-list');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const content = document.getElementById('message-content').value;
        const url = form.action;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ content })
            });

            if (!response.ok) throw new Error('Failed to send message');

            const data = await response.json();

            // Append the new message to the list
            const newMessage = document.createElement('li');
            newMessage.textContent = `${data.content} - ${data.date}`;
            messageList.appendChild(newMessage);

            // Clear the textarea
            document.getElementById('message-content').value = '';
        } catch (error) {
            console.error('Error sending message:', error);
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Follow Requests</h1>
        @foreach ($pendingRequests as $request)
            <div class="request-item">
                <p class="request-phrase">{{ $request->follower->username }} wants to follow you</p>
                <form action="{{ route('follow.accept', ['user1_id' => $request->user1_id, 'user2_id' => $request->user2_id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="request-accept">
                        <img src="/images/accept.png" alt="Follow accepted">
                    </button>
                </form>
                <form action="{{ route('follow.reject', ['user1_id' => $request->user1_id, 'user2_id' => $request->user2_id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="request-decline">
                        <img src="/images/decline.png" alt="Follow declined">
                    </button>
                </form>
            </div>
        @endforeach

    </div>
@endsection

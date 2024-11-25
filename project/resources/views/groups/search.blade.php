@extends('layouts.app')

@section('content')
<div class="group-search">
    <h1>Search for Groups</h1>
    @if($groups->isEmpty())
        <p class="no-results">No groups found. Try adjusting your search criteria!</p>
    @else
        <ul>
            @foreach($groups as $group)
                <li>
                    <a href="{{ route('groups.show', $group->id) }}" class="group-link">
                        {{ $group->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection



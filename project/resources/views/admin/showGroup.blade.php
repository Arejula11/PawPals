@extends('layouts.admin')
@section('head')
    <!-- Include only the CSS for this view -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <a href="{{route('admin.home')}}" class="name">PetPawls</a>
    </div>

    <h1 style="font-size: 32px; margin-bottom: 10px; margin-top: -40px; text-align: center; color: #333; font-weight: semi-bold;">Manage group</h1>
    <div class="user-profile">
        <header class="profile-header" style="border: 1px solid #ccc; padding: 20px; border-radius: 10px">
                
                
                <div class="user-info">
                    <h2 class="username">{{ $group->name }}</h1>
                    <h3>{{ $group->description }}</h2>
                    @if(!$group->is_public)
                        <h3>Private</h2>
                    @else
                        <h3>Public</h2>
                    @endif

    
                </div>
        </header>
            <div class="action-bento">

                <a href="{{ route('admin.groups.edit', $group->id) }}" class="action-item edit">EDIT</a>


                <form action="{{ route('admin.groups.delete', $group->id) }}" method="POST" style="margin: 0; padding: 0; display: inline;">
                    @csrf
                    @method('DELETE')
                    <div class="action-item deleted">
                        <button type="submit">Delete</button>
                    </div>
                </form>

            </div>


   
    </div>
</div>
@endsection
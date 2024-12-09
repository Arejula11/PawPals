@extends('layouts.admin')

@section('content')
<div class="register-part">
    <div class="register-container">
        <h1 style="color: #9b4dca; font-size: 36px; font-weight: bold;">PetPawls</h1>
        <form method="POST" action="{{ route('admin.register') }}" class="register-form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <label for="username">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
            @if ($errors->has('username'))
                <span class="error">{{ $errors->first('username') }}</span>
            @endif

            <label for="firstname">First Name</label>
            <input id="firstname" type="text" name="firstname" value="{{ old('firstname') }}" required>
            @if ($errors->has('firstname'))
                <span class="error">{{ $errors->first('firstname') }}</span>
            @endif

            <label for="surname">Surname</label>
            <input id="surname" type="text" name="surname" value="{{ old('surname') }}" required>
            @if ($errors->has('surname'))
                <span class="error">{{ $errors->first('surname') }}</span>
            @endif

            <label for="email">E-Mail Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="error">{{ $errors->first('email') }}</span>
            @endif

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            @if ($errors->has('password'))
                <span class="error">{{ $errors->first('password') }}</span>
            @endif

            <label for="password-confirm">Confirm Password</label>
            <input id="password-confirm" type="password" name="password_confirmation" required>
            
            <button type="submit">Create</button>

        </form>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        

        <!-- Styles -->
        @yield('head')
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
        <style>
            .column {
                float: left;
                width: 50%;
                display: flex;
                flex-direction: column;
            }

            .column.scrollable {
                overflow-y: scroll;
            }

            .row:after {
                content: "";
                display: table;
                clear: both;
            }

            .column.logout-container {
                margin-top: auto;
            }

            .row {
                height: 100%;
                margin: 0 16px;
            }

            main > header {
                display: none;
            }

            .button.logout {
                margin-top: 1em;
            }

            footer {
                background-color: #f1f1f1;
                padding: 0px 0;
                text-align: center;
                line-height: 1.5;
                font-size: 0.9em;
            }

            footer .footer-link {
                margin: 0 15px;

            }

        </style>
        
    </head>

    <body>
        <main>
            <div class="row">
                <!-- Left Column -->
                <div class="column">
                    <h1><a href="{{ url('/home') }}">PetPawls</a></h1>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="{{ url('/home') }}">Home</a></li>
                            <li><a href="{{ route('search') }}">Search</a></li>
                            @if (Auth::check())
                            <li><a href="{{ route('notification.index') }}">Notifications</a></li>
                            <li><a href="{{ route('posts.create') }}">Create Post</a></li>
                            <li><a href="{{ route('groups.index') }}">Groups</a></li>
                            <li><a href="{{ route('settings.show') }}">Settings</a></li>
                            @endif
                        </ul>
                    </nav>
                    @if (Auth::check())
                        <a class="button logout" href="{{ url('/logout') }}"> Logout </a>
                    @elseif (url()->current() !== url('/login'))
                        <a class="button" href="{{ route('login') }}">Login</a>
                    @endif
                </div>
    
                <!-- Right Column (Content Area) -->
                <div class="column scrollable">
                    <section id="content">
                        @yield('content')
                    </section>
                </div>
            </div>
        </main>

        

        @yield('scripts')

    </body>
</html>

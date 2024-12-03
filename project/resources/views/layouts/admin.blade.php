<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        @yield('head')
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

        </style>
        
    </head>

    <body>
        <main>
            <div class="row">

    
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

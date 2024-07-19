<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aplikasi Komunikasi Manajemen dan Budaya Organisasi PLN">
        <meta name="author" content="PT PLN (Persero)">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/logo_pln.jpg')}}">

        <!-- App title -->
        <title>{{env('SITE_TITLE', 'PT PLN (Persero)')}}</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #FFFFFF;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
                background-color: #00A2B9;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                /*font-size: 14px;*/
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                {{--<div class="title">Be right back.</div>--}}
                {{--<img src="{{asset('assets/images/404.png')}}">--}}
                {{--<div class="row">--}}
                    <div>
                        <img src="{{asset('assets/images/503.png')}}">
                    </div>
                    <div class="title">
                        <h1>We're Sorry :(</h1>
			<h4>Komando cannot be access momentarily due to unusually high traffic. Please try again later.</h4>
                    </div>
                    {{--<h3 class="text-uppercase text-white font-600 m-t-30">500 - Something went wrong</h3>--}}
                    {{--<div>Error Code : {{@$error_code}}</div>--}}
{{--                    <div>{{@$error_message}}</div>--}}
                    {{--<p class="text-white m-t-30">--}}
                        {{--We're sorry, but the server was unable to complete your request. Please try again later.--}}
                        {{--If the problem persist, please report to your Administrator and mention this error message.--}}
                        {{--Here's a little tip that might help you get back on track.--}}
                    {{--</p>--}}
                    {{--<br>--}}
                    {{--<a class="btn btn-pink waves-effect waves-light" href="{{url('/')}}"> Return Home</a>--}}

                {{--</div>--}}
            </div>
        </div>
    </body>
</html>

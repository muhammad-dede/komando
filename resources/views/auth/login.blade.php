@php($backgroundImages = \App\Models\MediaKit::getBackgroundImageUrl())

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{--<meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
        <meta name="description" content="Aplikasi Komunikasi Manajemen dan Budaya Organisasi PLN">
        <meta name="author" content="PT PLN (Persero)">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    	<meta name="apple-mobile-web-app-capable" content="yes">
    	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="mobile-web-app-capable" content="yes">

        <link rel="manifest" href="{{asset('assets/js/manifest.json')}}">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/logo_pln.jpg')}}">

        <!-- App title -->
        <title>{{env('SITE_TITLE', 'PT PLN (Persero)')}}</title>

        <!-- App CSS -->
        <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert css -->
        <link href="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="{{asset('assets/js/modernizr.min.js')}}"></script>

        <style>
            .page-login:before {
                position: fixed;
                top: 0;
                left: 0;
                content: '';
                width: 100%;
                height: 100%;
		        background-image: url("{{ $backgroundImages->first() }}");
                background-position-y: top;
                -webkit-background-size: cover;
                background-size: cover;
                z-index: -1;
            }

            .page-login:after {
                position: fixed;
                top: 0;
                left: 0;
                content: '';
                width: 100%;
                height: 100%;
                /*background-color: rgba(38, 50, 56, 0.85);
                background-color: rgba(72, 80, 88, 0.75);*/
		        /*background-color: rgba(100, 176, 242, 0.3); <--*/
                /*background-color: rgba(100, 176, 242, 0.3);*/
                /*background-color: rgba(27, 185, 154, 0.6);*/
                z-index: -1;
            }
        </style>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117109636-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-117109636-1');
</script>

    </head>


    <body class="page-login login">

        <div class=""></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">

        	<div class="account-bg">
                <div class="card-box m-b-0">
                    <div class="text-xs-center m-t-20">
                        <a href="{{url('/')}}" class="logo">
                            <img src="{{asset('assets/images/logo_1.png')}}" height="60" class="img-fluid">
                        </a>
                    </div>
                    <div class="m-b-20">

                        <div class="form-group text-center m-t-30">
                            <div class="col-xs-12 text-center" align="center">
                                <img src="{{asset('assets/images/login_komando.png')}}" height="60" class="img-fluid">
                            </div>
                        </div>

                        <div class="form-group text-center m-t-30">
                            <div class="col-xs-12">
                                <a href="{{ url('/oauth/redirect/pln') }}" class="btn btn-info btn-block waves-effect waves-light" type="submit">
                                    <i class="fa fa-lock"></i>&nbsp;&nbsp;&nbsp;Sign in with IAM PLN
                                </a>
                            </div>
                        </div>

                        <div class="form-group m-t-50 m-b-0">
                            <div class="col-sm-12 text-xs-center ">
                                <div style="color: #8e8e93; font-size: smaller;">
                                    &copy; 2017 - {{date('Y')}} <span class=""> PT PLN (Persero)</span>.
                                    <span>All rights reserved</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!-- end wrapper page -->


        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/js/tether.min.js')}}"></script><!-- Tether for Bootstrap -->
        <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/js/waves.js')}}"></script>
        <script src="{{asset('assets/js/jquery.nicescroll.js')}}"></script>
        <script src="{{asset('assets/plugins/switchery/switchery.min.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('assets/js/jquery.core.js')}}"></script>
        <script src="{{asset('assets/js/jquery.app.js')}}"></script>

        <!-- Sweet Alert js -->
        <script src="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>

        <script src="{{asset('assets/js/konami.js')}}"></script>
        <script src="{{asset('assets/js/code.js')}}"></script>
        <script>
            $( window ).load(function() {
                @if(session('success'))
                swal({
                            title: "Success!",
                            text: "{{session('success')}}",
                            type: "success",
                            showCancelButton: false,
                            cancelButtonClass: 'btn-secondary waves-effect',
                            confirmButtonClass: 'btn-primary waves-effect waves-light',
                            confirmButtonText: 'OK',
//                closeOnConfirm: false,
                        });
                @endif
                @if(session('info'))
                    swal({
                            title: "Information",
                            text: "{{session('info')}}",
                            type: "info",
                            showCancelButton: false,
                            cancelButtonClass: 'btn-secondary waves-effect',
                            confirmButtonClass: 'btn-primary waves-effect waves-light',
                            confirmButtonText: 'OK',
//                closeOnConfirm: false,
                        });
                @endif
                @if(session('warning'))
                    swal({
                            title: "Warning!",
                            text: "{{session('warning')}}",
                            type: "warning",
                            showCancelButton: false,
                            cancelButtonClass: 'btn-secondary waves-effect',
                            confirmButtonClass: 'btn-primary waves-effect waves-light',
                            confirmButtonText: 'OK',
//                closeOnConfirm: false,
                        });
                @endif
                @if(session('error'))
                    swal({
                            title: "Error!",
                            text: "{{session('error')}}",
                            type: "error",
                            showCancelButton: false,
                            cancelButtonClass: 'btn-secondary waves-effect',
                            confirmButtonClass: 'btn-primary waves-effect waves-light',
                            confirmButtonText: 'OK',
//                closeOnConfirm: false,
                        });
                @endif

            });

            const BG_SLIDESHOW_DURATION = {{ config('komando.bg_slideshow_duration') }};
            var images = {!! json_encode($backgroundImages) !!};

            $(function () {
                var i = 0;
                setInterval(function () {
                    i++;
                    if (i == images.length) {
                        i = 0;
                    }
                    //TODO animate or use some smooth animation library to change background images
                    $('head').append('<style>.page-login:before{background-image: url('+images[i]+') !important;}</style>');
                }, BG_SLIDESHOW_DURATION);
            });

        </script>

    </body>
</html>

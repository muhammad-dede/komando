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

        <!-- App CSS -->
        <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />

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
                background-image: url("{{asset('assets/images/bg6.jpg')}}");
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
                /*background-color: rgba(38, 50, 56, 0.85);*/
                /*background-color: rgba(72, 80, 88, 0.9);*/
                background-color: rgba(100, 176, 242, 0.3);
                /*background-color: rgba(27, 185, 154, 0.6);*/
                z-index: -1;
            }
        </style>

    </head>


    <body class="page-login login">

        <div class=""></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">

        	<div class="account-bg">
                <div class="card-box m-b-0">
                    <div class="text-xs-center m-t-20">
                        <a href="{{url('/')}}" class="logo">
                            <img src="{{asset('assets/images/logo_pln2.png')}}" width="30">
                            KOMANDO<small style="font-weight: normal;">PLN</small>
                        </a>
                    </div>
                    <div class="m-t-30 m-b-20">
                        <div class="col-xs-12 text-xs-center">
                            <h6 class="text-muted text-uppercase m-b-0 m-t-0">Update Email Korporat</h6>
                        </div>
                        {!! Form::open(['url'=>'auth/update-email', 'class'=>'form-horizontal m-t-20']) !!}
                        {!! Form::hidden('user_id',session('user_id')) !!}
                            {{--<div class="form-group ">--}}
                                {{--<div class="col-xs-12">--}}
                                    {{--<input class="form-control" type="email" required="" placeholder="Domain\Username" name="email_baru" value="{{session('domain')}}\{{session('user_id')}}">--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <input class="form-control" type="email" required="" placeholder="Email Korporat" name="email_baru">
                                </div>
                            </div>



                            {{--<div class="form-group">--}}
                                {{--<div class="col-xs-12">--}}
                                    {{--<input class="form-control" type="password" required="" placeholder="Password" name="password">--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group ">--}}
                                {{--<div class="col-xs-12">--}}
                                    {{--<div class="checkbox checkbox-custom">--}}
                                        {{--<input id="checkbox-signup" type="checkbox">--}}
                                        {{--<label for="checkbox-signup">--}}
                                            {{--Remember me--}}
                                        {{--</label>--}}
                                    {{--</div>--}}

                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group text-center m-t-30">
                                <div class="col-xs-12">
                                    <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Update Email</button>
                                </div>
                            </div>

                            @if (count($errors) > 0)
                                <div class="form-group m-t-30 m-b-0">
                                    <div class="alert alert-dismissable alert-danger">
                                        {{--<i class="ti ti-close"></i>&nbsp; --}}
                                        <strong>Failed!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <br>
                                    </div>
                                </div>
                            @endif
                            @if (session('status'))
                                <div class="form-group m-t-30 m-b-0">
                                    <div class="alert alert-dismissable alert-danger">
                                        {{--<i class="ti ti-close"></i>&nbsp; --}}
                                        <strong>Failed!</strong> {{ session('status') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    </div>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="form-group m-t-30 m-b-0">
                                    <div class="alert alert-dismissable alert-danger">
                                        {{--<i class="ti ti-close"></i>&nbsp; --}}
                                        <strong>Failed!</strong> {!! session('error') !!}
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    </div>
                                </div>
                            @endif
                            @if (session('warning'))
                                <div class="form-group m-t-30 m-b-0">
                                    <div class="alert alert-dismissable alert-warning">
                                        {{--<i class="ti ti-close"></i>&nbsp; --}}
                                        <strong>Warning!</strong> {!! session('warning') !!}
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group m-t-50 m-b-0">
                                <div class="col-sm-12 text-xs-center ">
                                    <div style="color: #8e8e93; font-size: smaller;">
                                        &copy; 2017 <span class=""> PT PLN (Persero)</span>.
                                        <span>All rights reserved</span>
                                    </div>
                                </div>
                            </div>

                            {{--<div class="form-group m-t-30 m-b-0">--}}
                                {{--<div class="col-sm-12">--}}
                                    {{--<a href="pages-recoverpw.html" class="text-muted"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group m-t-30 m-b-0">--}}
                                {{--<div class="col-sm-12 text-xs-center">--}}
                                    {{--<h5 class="text-muted"><b>Sign in with</b></h5>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group m-b-0 text-xs-center">--}}
                                {{--<div class="col-sm-12">--}}
                                    {{--<button type="button" class="btn btn-facebook waves-effect waves-light m-t-20">--}}
                                       {{--<i class="fa fa-facebook m-r-5"></i> Facebook--}}
                                    {{--</button>--}}

                                    {{--<button type="button" class="btn btn-twitter waves-effect waves-light m-t-20">--}}
                                       {{--<i class="fa fa-twitter m-r-5"></i> Twitter--}}
                                    {{--</button>--}}

                                    {{--<button type="button" class="btn btn-googleplus waves-effect waves-light m-t-20">--}}
                                       {{--<i class="fa fa-google-plus m-r-5"></i> Google+--}}
                                    {{--</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
            <!-- end card-box-->

            {{--<div class="m-t-20">--}}
                {{--<div class="text-xs-center">--}}
                    {{--<p class="text-white">Don't have an account? <a href="pages-register.html" class="text-white m-l-5"><b>Sign Up</b></a></p>--}}
                {{--</div>--}}
            {{--</div>--}}

        </div>
        <!-- end wrapper page -->


        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
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

    </body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
    <meta name="description" content="Aplikasi Komunikasi Manajemen dan Budaya Organisasi PLN">
    <meta name="author" content="PT PLN (Persero)">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="mobile-web-app-capable" content="yes">
    <!-- App Favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/logo_pln.jpg')}}">

    <!-- App title -->
    <title>{{env('SITE_TITLE', 'PT PLN (Persero)')}}</title>

    <!-- App CSS -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css"/>

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
                    KOMANDO
                    <small style="font-weight: normal;">PLN</small>
                </a>
            </div>
            <div class="m-t-30 m-b-20">
                @if (session('warning'))
                    <div class="form-group m-t-30 m-b-0">
                        <div class="alert alert-dismissable alert-warning">
                            {{--<i class="ti ti-close"></i>&nbsp; --}}
                            <strong>Warning!</strong> {!! session('warning') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        </div>
                    </div>
                @endif
                @if (session('info'))
                    <div class="form-group m-t-30 m-b-0">
                        <div class="alert alert-dismissable alert-warning">
                            {{--<i class="ti ti-close"></i>&nbsp; --}}
                            {!! session('info') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        </div>
                    </div>
                @endif
                <div class="col-xs-12 text-xs-center">
                    <h6 class="text-muted text-uppercase m-b-0 m-t-0">Update Data Pegawai</h6>
                </div>
                {!! Form::open(['url'=>'auth/update-nip', 'class'=>'form-horizontal m-t-20']) !!}
                {!! Form::hidden('user_id',session('user_id')) !!}
                {{--<div class="form-group ">--}}
                {{--<div class="col-xs-12">--}}
                {{--<input class="form-control" type="email" required="" placeholder="Domain\Username" name="email_baru" value="{{session('domain')}}\{{session('user_id')}}">--}}
                {{--</div>--}}
                {{--</div>--}}

                <div class="form-group " id="q_pegawai_baru">
                    <div class="col-xs-12">
                        <h6 class="m-b-20">Apakah Anda pegawai baru di PT PLN (Persero)?</h6>
                        <label class="c-input c-radio">
                            <input id="radio_pegawai_baru_1" name="pegawai_baru" type="radio" value="1">
                            <span class="c-indicator"></span>
                            Ya
                        </label>
                        <label class="c-input c-radio">
                            <input id="radio_pegawai_baru_0" name="pegawai_baru" type="radio" value="0">
                            <span class="c-indicator"></span>
                            Tidak
                        </label>
                    </div>
                </div>

                <div class="form-group " id="q_pernah_login" style="display: none;">
                    <div class="col-xs-12">
                        <h6 class="m-b-20">Apakah Anda sudah pernah melakukan login sebelumnya?</h6>
                        <label class="c-input c-radio">
                            <input id="radio_pernah_login_1" name="pernah_login" type="radio" value="1">
                            <span class="c-indicator"></span>
                            Ya
                        </label>
                        <label class="c-input c-radio">
                            <input id="radio_pernah_login_0" name="pernah_login" type="radio" value="0">
                            <span class="c-indicator"></span>
                            Tidak
                        </label>
                    </div>
                </div>

                <div class="form-group " id="q_pegawai_mutasi" style="display: none;">
                    <div class="col-xs-12">
                        <h6 class="m-b-20">Apakah Anda pegawai pindahan/mutasi atau domain username Anda berubah?</h6>
                        <label class="c-input c-radio">
                            <input id="radio_pegawai_mutasi_1" name="pegawai_mutasi" type="radio" value="1">
                            <span class="c-indicator"></span>
                            Ya
                        </label>
                        <label class="c-input c-radio">
                            <input id="radio_pegawai_mutasi_0" name="pegawai_mutasi" type="radio" value="0">
                            <span class="c-indicator"></span>
                            Tidak
                        </label>
                    </div>
                </div>

                <div class="form-group " id="q_update_username" style="display: none;">
                    <div class="col-xs-12">
                        <h6 class="m-b-20">Apakah Anda mengubah username Active Directory?</h6>
                        <label class="c-input c-radio">
                            <input id="radio_update_username_1" name="update_username" type="radio" value="1">
                            <span class="c-indicator"></span>
                            Ya
                        </label>
                        <label class="c-input c-radio">
                            <input id="radio_update_username_0" name="update_username" type="radio" value="0">
                            <span class="c-indicator"></span>
                            Tidak
                        </label>
                    </div>
                </div>

                <div id="form_update" style="display: none">

                    <div class="form-group m-t-20">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Domain" name="domain"
                                   id="domain_1">
                            <small class="text-muted">Masukkan domain. Contoh: pusat</small>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Username" id="username_1"
                                   name="username">
                            <small class="text-muted">Masukkan username tanpa domain.</small>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="NIP" name="nip" id="nip_1">
                            <small class="text-muted">Tanpa tanda spasi atau minus. Contoh: 8512345Z</small>
                        </div>
                    </div>

                    <div class="form-group text-center m-t-30">
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Update
                                </button>
                            </div>
                            <div class="col-xs-12 m-t-10">
                                <a href="{{url('/auth/login')}}"
                                   class="btn btn-danger btn-block waves-effect waves-light" type="button">Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="form_update_2" style="display: none">
                    <div class="form-group m-t-20">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Domain Lama"
                                   id="domain_lama_2"
                                   name="domain_lama">
                            <small class="text-muted">Masukkan domain lama. Contoh: pusat</small>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Username Lama"
                                   id="username_lama_2"
                                   name="username_lama">
                            <small class="text-muted">Masukkan username lama tanpa domain.</small>
                        </div>
                    </div>

                    <div class="form-group m-t-20">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Domain Baru"
                                   name="domain_baru" id="domain_2">
                            <small class="text-muted">Masukkan domain baru. Contoh: pusat</small>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Username Baru"
                                   id="username_2"
                                   name="username_baru">
                            <small class="text-muted">Masukkan username baru tanpa domain.</small>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="NIP" name="nip_2"
                                   id="nip_2">
                            <small class="text-muted">Tanpa tanda spasi atau minus. Contoh: 8512345Z</small>
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
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Update
                                </button>
                            </div>
                            <div class="col-xs-12 m-t-10">
                                <a href="{{url('/auth/login')}}"
                                   class="btn btn-danger btn-block waves-effect waves-light" type="button">Cancel
                                </a>
                            </div>
                        </div>
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


                <div class="form-group m-t-50 m-b-0">
                    <div class="col-sm-12 text-xs-center ">
                        <div style="color: #8e8e93; font-size: smaller;">
                            &copy; 2017 - {{date('Y')}} <span class=""> PT PLN (Persero)</span>.
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
<script src="{{asset('assets/js/tether.min.js')}}"></script>
<!-- Tether for Bootstrap -->
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/waves.js')}}"></script>
<script src="{{asset('assets/js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('assets/plugins/switchery/switchery.min.js')}}"></script>

<!-- App js -->
<script src="{{asset('assets/js/jquery.core.js')}}"></script>
<script src="{{asset('assets/js/jquery.app.js')}}"></script>

<script>
    $('#radio_pegawai_baru_0').click(function () {
//            alert('HELLO');
        $('#q_pernah_login').show();
        $('#form_update').hide();
        $('#form_update_2').hide();
        toggleForm1(false);
        toggleForm2(false);
    });
    $('#radio_pegawai_baru_1').click(function () {
        $('#q_pernah_login').hide();
        $('#radio_pernah_login_0').attr("checked", false);
        $('#radio_pernah_login_1').attr("checked", false);
        $('#q_pegawai_mutasi').hide();
        $('#radio_pegawai_mutasi_0').attr("checked", false);
        $('#radio_pegawai_mutasi_1').attr("checked", false);
        $('#q_update_username').hide();
        $('#radio_update_username_0').attr("checked", false);
        $('#radio_update_username_1').attr("checked", false);

        $('#form_update').show();
        $('#form_update_2').hide();
//        toggleForm1(true);
        toggleForm2(false);
    });

    $('#radio_pernah_login_1').click(function () {
//            alert('HELLO');
        $('#q_pegawai_mutasi').show();
        $('#form_update').hide();
        $('#form_update_2').hide();
        toggleForm1(false);
        toggleForm2(false);
    });
    $('#radio_pernah_login_0').click(function () {
//        $('#q_pernah_login').hide();
        $('#q_pegawai_mutasi').hide();
        $('#radio_pegawai_mutasi_0').attr("checked", false);
        $('#radio_pegawai_mutasi_1').attr("checked", false);
        $('#q_update_username').hide();
        $('#radio_update_username_0').attr("checked", false);
        $('#radio_update_username_1').attr("checked", false);
        $('#form_update').show();
        $('#form_update_2').hide();
        toggleForm1(true);
        toggleForm2(false);
    });

    $('#radio_pegawai_mutasi_0').click(function () {
//            alert('HELLO');
        $('#q_update_username').show();
        $('#form_update').hide();
        $('#form_update_2').hide();
        toggleForm2(false);
        toggleForm1(false);
    });
    $('#radio_pegawai_mutasi_1').click(function () {
//        $('#q_pernah_login').hide();
//        $('#q_pegawai_mutasi').hide();
        $('#q_update_username').hide();
        $('#radio_update_username_0').attr("checked", false);
        $('#radio_update_username_1').attr("checked", false);
        $('#form_update_2').show();
        $('#form_update').hide();
        toggleForm2(true);
        toggleForm1(false);
    });

    $('#radio_update_username_0').click(function () {
//            alert('HELLO');
        $('#form_update').show();
        $('#form_update_2').hide();
        toggleForm1(true);
        toggleForm2(false);
    });
    $('#radio_update_username_1').click(function () {
//        $('#q_pernah_login').hide();
//        $('#q_pegawai_mutasi').hide();
        $('#form_update_2').show();
        $('#form_update').hide();
        toggleForm2(true);
        toggleForm1(false);
    });

    function toggleForm1(kondisi) {
        $('#domain_1').prop('required', kondisi);
        $('#username_1').prop('required', kondisi);
        $('#nip_1').prop('required', kondisi);
    }

    function toggleForm2(kondisi) {
        $('#domain_lama_2').prop('required', kondisi);
        $('#username_lama_2').prop('required', kondisi);
        $('#domain_2').prop('required', kondisi);
        $('#username_2').prop('required', kondisi);
        $('#nip_2').prop('required', kondisi);
    }
</script>

</body>
</html>

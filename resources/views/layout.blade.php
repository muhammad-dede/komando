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

    <link rel="manifest" href="{{asset('assets/js/manifest.json')}}">

    <!-- App Favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/logo_pln.jpg')}}">

    <!-- App title -->
    <title>{{env('SITE_TITLE', 'PT PLN (Persero)')}}</title>

    <!-- Switchery css -->
    <link href="{{asset('assets/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>

    <!-- Sweet Alert css -->
    <link href="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css"/>

    <!--Form Wizard-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/jquery.steps/demo/css/jquery.steps.css')}}"/>

    @yield('css')
    @stack('styles')

            <!-- App CSS -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- Modernizr js -->
    <script src="{{asset('assets/js/modernizr.min.js')}}"></script>

    @if(config('komando.enable_ga'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-117109636-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-117109636-1');
    </script>
    @endif
</head>


<body>
<!-- Navigation Bar-->
<header id="topnav" >
    @if(session()->get('original_user')!=null)
    <div class="topbar-main" style="border-bottom: yellow solid 5px;">
    @else
    <div class="topbar-main">
    @endif
        <div class="container">

            <!-- LOGO -->
            <div class="topbar-left">
                <a href="{{url('/')}}" class="logo">
                    <img src="{{asset('assets/images/logo_small.png')}}" width="35">
                    <span style="font-family: 'Myriad Pro', 'Gill Sans', 'Gill Sans MT', Calibri, sans-serif; letter-spacing: 0;font-size: 22px;">
                        KOMANDO
                    </span>

                </a>
            </div>
            <!-- End Logo container-->

            <div class="menu-extras">

                <ul class="nav navbar-nav pull-right">

                    <li class="nav-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>
                    <?php
                    $notifs = Auth::user()->notifications()->where('status', 'UNREAD')->take(10)->orderBy('id', 'desc')->get();
                    $jml_notif = $notifs->count();
                    ?>
                    <li class="nav-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown"
                           href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <i class="zmdi zmdi-notifications-none noti-icon"></i>
                            @if($jml_notif!=0)
                                <span class="noti-icon-badge"></span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-lg"
                             aria-labelledby="Preview">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5>
                                    <small><span class="label label-danger pull-xs-right">{{$jml_notif}}</span>Notification
                                    </small>
                                </h5>
                            </div>

                            <!-- item-->
                            <?php $limit = 1;?>
                            @foreach($notifs as $notif)
                                <a href="{{url('notification/'.$notif->id)}}" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-{{$notif->color}}" style="color:white;"><i
                                                class="{{$notif->icon}}"></i></div>
                                    <p class="notify-details">
                                        <b>{{$notif->subject}}</b>
                                        <span>{{$notif->message}}</span>
                                        <small class="text-muted">{{$notif->created_at->diffForHumans()}}</small>
                                    </p>
                                </a>
                                <?php
                                if ($limit == 5)
                                    break;

                                $limit++;
                                ?>
                            @endforeach

                            @if($jml_notif==0)
                                <span class="dropdown-item notify-item">
                                    <p class="notify-details">No notification
                                    </p>
                            </span>
                                @endif
                                        <!-- All-->
                                <a href="{{url('notification')}}" class="dropdown-item notify-item notify-all">
                                    View All
                                </a>

                        </div>
                    </li>

                    <li class="nav-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light nav-user"
                           data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            @if(Auth::user()->foto!='')
                                <img src="{{url('user/foto-thumb')}}" alt="user" class="img-circle">
                            @else
                                <img src="{{asset('assets/images/user.jpg')}}" alt="user" class="img-circle">
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow profile-dropdown "
                             aria-labelledby="Preview">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="text-overflow">
                                    <small>{{Auth::user()->name}}</small>
                                </h5>
                            </div>

                            <!-- item-->
                            <a href="{{url('profile')}}" class="dropdown-item notify-item">
                                <i class="zmdi zmdi-account-circle"></i> <span>Profile</span>
                            </a>

                            <!-- item-->
                            <a href="{{url('help')}}" class="dropdown-item notify-item">
                                {{--<a href="{{asset('assets/doc/user_manual.pdf')}}" class="dropdown-item notify-item">--}}
                                <i class="zmdi zmdi-help"></i> <span>Help</span>
                            </a>

                            <!-- item-->
                            <a href="{{(session('login_from')=='SSO')?url('oauth/logout'):url('auth/logout')}}" class="dropdown-item notify-item">
                                <i class="zmdi zmdi-power"></i> <span>Logout</span>
                            </a>

                        </div>
                    </li>

                    @if(session()->get('original_user')!=null)
                    <li class="nav-item dropdown notification-list">
                        <a class="nav-link arrow-none waves-effect waves-light nav-user" style="background-color: yellow"
                           href="{{url('impersonation/revert')}}" role="button"
                           aria-haspopup="false" aria-expanded="false" title="Stop Impersonation">
                                <img src="{{asset('assets/images/ninja.png')}}" alt="user" class="img-circle">
                        </a>
                    </li>
                    @endif

                </ul>

            </div>
            <!-- end menu-extras -->
            <div class="clearfix"></div>

        </div>
        <!-- end container -->
    </div>
    <!-- end topbar-main -->


    <div class="navbar-custom">
        <div class="container">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                    <li>
                        <a href="{{url('/')}}"><i class="zmdi zmdi-home"></i> <span> Home </span> </a>
                    </li>
                    <li>
                        <a href="{{url('/dashboard-budaya')}}"><i class="zmdi zmdi-chart"></i> <span> Dashboard Budaya </span> </a>
                    </li>
                    @if(Auth::user()->hasRole('pegawai'))
                    <li>
                        <a href="{{url('/commitment')}}"><i class="zmdi zmdi-thumb-up"></i> <span> Commitment </span>
                        </a>
                    </li>
                    @endif
                    @if(!Auth::user()->hasRole('komisaris'))
                    <li>
                        <a href="{{url('/coc')}}">
                            <i class="zmdi zmdi-local-library"></i> <span> Code of Conduct </span>
                        </a>
                    </li>
                    @endif
                    @if(!Auth::user()->hasRole('komisaris'))
                    @php($userLiquid = \App\Models\Liquid\Liquid::query()->forUser(auth()->user())->first())
					@if (Auth::user()->can('show_menu_liquid') || $userLiquid)
						<li class="has-submenu">
							<a href="#">
								<i class="zmdi zmdi-comment"></i> Liquid
							</a>
							<ul class="submenu megamenu">
								<li>
									<ul>
										@foreach (Auth::user()->dashboardMenu() as $item)
											@if($item == \App\Enum\LiquidMenuEnum::ADMIN)
												<li><a href="{{route('dashboard-admin.liquid-jadwal.index')}}">Dashboard Admin</a></li>
											@endif
											@if($item == \App\Enum\LiquidMenuEnum::ATASAN)
												<li><a href="{{route('dashboard-atasan.liquid-jadwal.index')}}">Dashboard Leader</a></li>
											@endif
											@if($item == \App\Enum\LiquidMenuEnum::BAWAHAN)
												<li><a href="{{route('dashboard-bawahan.liquid-jadwal.index')}}">Dashboard Bawahan</a></li>
											@endif
										@endforeach
										@if(auth()->user()->can('liquid_create_liquid'))
											<li><a href="{{route('liquid.create')}}">Create Liquid</a></li>
										@endif
                                        @can('canAccessActLogBook')
                                            <li><a href="{{ route('activity-log.index') }}">Activity Log</a></li>
                                        @endcan
									</ul>
								</li>
							</ul>
						</li>
                    @endif
                    @endif

                    @if(!Auth::user()->hasRole('komisaris'))
                    @if (Auth::user()->can('verifikator') || Auth::user()->can('report_assessment') || Auth::user()->pesertaAssessment->count()>0 || Auth::user()->verifikatorAssessment->count()>0)
                    <li class="has-submenu">
                        <a href="#"><i class="zmdi zmdi-male"></i> <span> Self Assessment </span> </a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    @if(Auth::user()->pesertaAssessment->count()>0)
                                    <li><a href="{{url('self-assessment/pegawai')}}">Pegawai</a></li>
                                    @endif
                                    @if(Auth::user()->verifikatorAssessment->count()>0)
                                    <li><a href="{{url('self-assessment/verifikator')}}">Verifikator</a></li>
                                    @endif
                                    @if(Auth::user()->can('report_assessment'))
                                    <li><a href="{{url('self-assessment/daftar-peserta')}}">Daftar Peserta</a></li>
                                    @endif
                                    {{-- <li><a href="{{url('self-assessment/jadwal')}}">Jadwal</a></li>
                                    <li><a href="{{url('self-assessment/kkj')}}">KKJ</a></li>
                                    <li><a href="{{url('self-assessment/dirkom')}}">Dirkom</a></li>
                                    <li><a href="{{url('self-assessment/report')}}">Report</a></li> --}}
                                    <li><a href="{{asset('assets/doc/panduan-self-assessment.pdf')}}" target="blank">Panduan</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @endif

                    @if(!(Auth::user()->hasRole('komisaris') || Auth::user()->hasRole('shap')))
                    <li class="has-submenu">
                        <a href="#"><i class="zmdi zmdi-favorite"></i> <span> Employee Volunteer Program </span> </a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    @if(Auth::user()->can('evp_dashboard'))
                                        <li><a href="{{url('evp/dashboard')}}">Dashboard</a></li>
                                    @endif
                                    @if(Auth::user()->can('evp_list'))
                                        <li><a href="{{url('evp/program')}}">Program</a></li>
                                    @endif
                                    @if(Auth::user()->isStruktural() || Auth::user()->can('evp_approve'))
                                        @if(Auth::user()->isGM())
                                            <li><a href="{{url('evp/approval')}}">Approval GM</a></li>
                                        @elseif(Auth::user()->isStruktural())
                                            <li><a href="{{url('evp/approval-atasan')}}">Approval Atasan</a></li>
                                        @endif
                                        @if(Auth::user()->hasRole('admin_evp'))
                                            <li><a href="{{url('evp/approval')}}">Approval Admin</a></li>
                                        @elseif(Auth::user()->hasRole('admin_pusat'))
                                            <li><a href="{{url('evp/approval')}}">Approval Pusat</a></li>
                                        @endif
                                    @endif
                                    @if(Auth::user()->hasRole('admin_evp') || Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root') || Auth::user()->isStruktural())
                                        <li><a href="{{url('evp/log')}}">Activity Log</a></li>
                                    @endif
                                    <li><a href="{{url('evp/help')}}">Panduan</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @if(Auth::user()->can('report'))
                    <li class="has-submenu">
                        <a href="#"><i class="zmdi zmdi-collection-text"></i> <span> Report</span> </a>
                        <ul
                            class="submenu megamenu"
                            style="height: auto;max-height: 400px;overflow-x: hidden"
                        >
                            <li>
                                <ul>
                                    {{-- <li><a href="{{url('report/monitoring-checkin-coc')}}">Persentase Baca Materi</a></li> --}}
                                    <li><a href="{{url('report/persentase-baca-materi')}}">Persentase Baca Materi</a></li>
                                    <li><a href="{{url('report/monitoring-baca-materi-pegawai')}}">Monitoring Baca Materi</a></li>
                                    <li><a href="{{url('report/briefing-coc')}}">Laporan Briefing CoC</a></li>
                                    <li><a href="{{url('report/rekap-coc')}}">Rekap Pelaporan CoC</a></li>
                                    <li><a href="{{url('report/persentase-coc')}}">Persentase CoC</a></li>
                                    <li><a href="{{url('report/status-coc-cc')}}">Status CoC Comp. Code</a></li>
                                    <li><a href="{{url('report/status-coc')}}">Status CoC Bus. Area</a></li>
                                    <li><a href="{{url('report/history-coc')}}">History CoC</a></li>
                                    <li><a href="{{url('report/tema-coc')}}">Tema CoC</a></li>
                                    <li><a href="{{url('report/jumlah-coc')}}">Jumlah CoC</a></li>
                                    <li><a href="{{url('report/commitment')}}">Commitment</a></li>
                                    @if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('admin_liquid_pusat'))
                                    <li><a href="{{url('report/commitment-induk')}}">Commitment Comp.Code</a></li>
                                    <li><a href="{{url('report/rekap-commitment')}}">Rekap Komitmen</a></li>
                                    <li><a href="{{url('report/commitment-direksi')}}">Komitmen Direksi</a></li>
                                    <li><a href="{{url('report/commitment-dekom')}}">Komitmen Dekom</a></li>
                                    <li><a href="{{ route('report.survey-liquid') }}">Survey Liquid</a></li>
                                    @endif
                                    <li><a href="{{url('report/problem')}}">Problem</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @if(Auth::user()->can(['md_target_coc','md_materi_coc','md_tema_coc',
                    'md_pedoman_perilaku','md_jabatan','md_posisi','md_organisasi']))
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-dns"></i> <span> Master Data</span> </a>
                            <ul class="submenu megamenu master-data">
                                <li>
                                    <ul>
                                        @if(Auth::user()->can('md_target_coc'))
                                            <li><a href="{{url('master-data/target-checkin-coc')}}">Target Persentase Check-in CoC</a></li>
                                            <li><a href="{{url('master-data/target-coc')}}">Target CoC</a></li>
                                        @endif
                                        @if(Auth::user()->can('md_tema_coc'))
                                            <li><a href="{{url('master-data/tema')}}">Tema CoC</a></li>
                                        @endif
                                        @if(Auth::user()->can('md_materi_coc'))
                                            <li><a href="{{url('master-data/materi')}}">Materi CoC</a></li>
                                        @endif
                                        @if(Auth::user()->can('md_perilaku'))
                                            <li><a href="{{url('master-data/pedoman-perilaku')}}">Pedoman Perilaku & Pertanyaan</a>
                                            </li>
                                        @endif
                                        @if(Auth::user()->can('md_jabatan'))
                                            <li><a href="{{url('master-data/jabatan')}}">Jabatan</a></li>
                                        @endif
                                        @if(Auth::user()->can('md_posisi'))
                                            <li><a href="{{url('master-data/posisi')}}">Posisi</a></li>
                                        @endif
                                        @if(Auth::user()->can('md_organisasi'))
                                            <li><a href="{{url('master-data/organisasi')}}">Organisasi</a></li>
                                        @endif
										{{--<li><a href="{{url('master-data/pertanyaan')}}">Pertanyaan</a></li>--}}
                                        @if(Auth::user()->can('md_config'))
                                            <li><a href="{{url('master-data/config-label')}}">Konfigurasi Label</a></li>
                                        @endif
                                        @if(Auth::user()->can('md_survey_question'))
                                            <li><a href="{{ url('master-data/survey-question') }}">Pertanyaan Survey</a></li>
                                        @endif
										@if (Auth::user()->can('md_kelebihan_kekurangan'))
                                        	<li><a href="{{ route('master-data.kelebihan-kekurangan.index') }}" title="{{ \App\Helpers\ConfigLabelHelper::getLabelSort('kelebihan') }} & {{ \App\Helpers\ConfigLabelHelper::getLabelSort('kekurangan') }}">{!! \App\Helpers\ConfigLabelHelper::getLabelSort('kelebihan') !!} & {!! \App\Helpers\ConfigLabelHelper::getLabelSort('kekurangan') !!}</a></li>
										@endif
                                        @if (Auth::user()->can('md_media'))
                                            <li><a href="{{ url('manajemen-media-banner') }}">Manajemen Media & Banner</a></li>
                                        @endif
                                        @if (Auth::user()->can('md_faq_manual_book'))
                                            <li><a href="{{ url('master-data/faq-manual-book') }}">FAQ & Manual Book</a></li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->hasRole('root') || Auth::user()->can('user_list'))
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-accounts"></i> <span> User Management </span> </a>
                            <ul class="submenu megamenu">
                                <li>
                                    <ul>
                                        <li><a href="{{url('user-management/user')}}">User</a></li>
                                        @if(Auth::user()->hasRole('root'))
                                            <li><a href="{{url('user-management/role')}}">Role</a></li>
                                            <li><a href="{{url('user-management/permission')}}">Permission</a></li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endif

                </ul>
                <!-- End navigation menu  -->
            </div>
        </div>
    </div>
</header>
<!-- End Navigation Bar-->


<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="wrapper">
    <div class="container">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                @yield('title')
            </div>
        </div>

        @yield('content')

                <!-- Footer -->
        <footer class="footer text-right">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        2017 - {{date('Y')}} &copy; PT PLN (Persero)
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->


    </div>
    <!-- container -->

</div>
<!-- End wrapper -->
@if(env('VALIDASI_CV_ENABLE', false) && session('validatecv') != 'valid')
{{-- Modal Kelengkapan CV Portal HC --}}
<div id="modalCV" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="myModalLabel">Progres Pengisian CV Portal HC</h4>
            </div>
            <div class="modal-body">

                <div style="padding-left:10px; padding-right:10px;">
                    {{-- <p>Sesuai dengan ketentuan surat nomor XXX/123/HSC/2022 perihal Kelengkapan CV Portal HC, Anda belum diperbolehkan menggunakan aplikasi Komando sebelum melengkapi CV Portal HC.</p> --}}
                    <p>Berikut adalah progress pengisian CV Anda pada aplikasi Portal HC:</p>
                </div>

                <div class="row p-10" style="margin-top: 20px; margin-left:10px;">
                    <div class="col-xs-2">
                        @if(Auth::user()->foto!='')
                                <img src="{{url('user/foto-thumb')}}" alt="user" class="img-circle img-fluid">
                            @else
                                <img src="{{asset('assets/images/user.jpg')}}" alt="user" class="img-circle img-fluid">
                            @endif
                    </div>
                    <div class="col-xs-10">
                        <h5>{{ Auth::user()->name }}</h5>
                        <span>{{ Auth::user()->nip }} / {{ @Auth::user()->strukturPosisi()->stext }}</span>
                    </div>
                
                </div>

                <div class="p-10" style="margin-top: 20px;">
                    
                    <table class="table">
                        <thead class="thead-default">
                        <tr>
                            {{-- <th>No</th> --}}
                            <th>Profil</th>
                            <th>Jumlah</th>
                            <th>Target</th>
                            <th>Progres</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(session('list_validasi_cv')!=null)
                        @php
                            $validasi_cv = session('list_validasi_cv');
                        @endphp
                            @foreach(session('list_kelengkapan') as $key => $value)
                            <tr>
                                {{-- <td>1</td> --}}
                                <td>{{ $value->description }}</td>
                                <td align="center">{{ $validasi_cv[$value->id]['jumlah'] }}</td>
                                <td align="center">{{ $value->target }}%</td>
                                <td align="center">{{ $validasi_cv[$value->id]['progress'] }}%</td>
                                <td align="center">
                                    @if($validasi_cv[$value->id]['status'] == '1')
                                        <i class="fa fa-check text-success"></i>
                                    @else
                                        <i class="fa fa-times text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        
                        </tbody>
                    </table>
                </div>
                <div style="padding-left:10px; padding-right:10px;margin-top:20px;">
                    <p>Silakan lengkapi CV Anda pada aplikasi Portal HC (portalhc.pln.co.id) agar dapat menggunakan aplikasi Komando kembali.</p>
                    <p>Terima kasih.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" onclick="window.location.href='{{ url('auth/logout') }}'">Logout</button>
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="window.location.href='{{ url('logout-portalhc') }}'">Go to Portal HC</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif

<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/tether.min.js')}}"></script>
<!-- Tether for Bootstrap -->
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/waves.js')}}"></script>
<script src="{{asset('assets/js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('assets/plugins/switchery/switchery.min.js')}}"></script>

<!--Form Wizard-->
<script src="{{asset('assets/plugins/jquery.steps/build/jquery.steps.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/plugins/jquery-validation/dist/jquery.validate.min.js')}}"></script>

@yield('javascript')
@stack('scripts')

        <!-- App js -->
<script src="{{asset('assets/js/jquery.core.js')}}"></script>
<script src="{{asset('assets/js/jquery.app.js')}}"></script>

<!-- Sweet Alert js -->
@if(isset($swal2))
    <script src="{{asset('assets/plugins/bootstrap-sweetalert/sweetalert2.all.js')}}"></script>
@else
    <script src="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
@endif
{{--<script src="{{asset('assets/pages/jquery.sweet-alert.init.js')}}"></script>--}}

<script src="{{asset('assets/js/konami.js')}}"></script>
<script src="{{asset('assets/js/code.js')}}"></script>

<script>
    $(window).load(function () {
        @if(env('VALIDASI_CV_ENABLE', false) && session('validatecv') != 'valid')
            $('#modalCV').modal('show');
        @endif
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

</script>

</body>
</html>

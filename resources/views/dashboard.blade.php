@extends('layout')

@section('css')

@stop

@section('title')
    {{--<div class="btn-group pull-right m-t-15">--}}
    {{--<button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light"--}}
    {{--data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i--}}
    {{--class="fa fa-cog"></i></span></button>--}}
    {{--<div class="dropdown-menu">--}}
    {{--<a class="dropdown-item" href="#">Action</a>--}}
    {{--<a class="dropdown-item" href="#">Another action</a>--}}
    {{--<a class="dropdown-item" href="#">Something else here</a>--}}
    {{--<div class="dropdown-divider"></div>--}}
    {{--<a class="dropdown-item" href="#">Separated link</a>--}}
    {{--</div>--}}

    {{--</div>--}}
    {{--<h4 class="page-title">Home</h4>--}}
@stop

@section('content')

    <div class="row m-t-15 hidden-sm-up">

        @if($is_streaming)
        <div class="row" style="margin-top:10px;">
            <div class="col-md-12">
                
                <div class="card card-block">
                    <h3 class="card-title m-b-10 text-info"><i class="fa fa-youtube-play"></i> {{ $streaming->title }}</h3> 
                    <div>
                        <img src="{{ $streaming->banner_mobile }}" class="img-fluid" onmouseover="this.style.cursor='pointer';" alt="{{ $streaming->title }}" id="btn_streaming_mobile" onclick="playStreamingMobile()">
                        <div id="townhall_mobile" style="display: none;">
                            {{-- <div style="border: 2px solid red; width: 60px;background: transparent;z-index: 99;position: absolute;right: 0px;margin-top: 0px;height: 58px;cursor: not-allowed;"></div> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="width: 100%;z-index: 99;position: absolute;margin-top: 0px;height: 100px;background: transparent;left:0;" class="privacy-video-block"></div>
                                    <div style="width: 100%;z-index: 99;position: absolute;margin-top: 150px;height: 100px;background: transparent;left:0;" class="privacy-video-block"></div>
                                    <div style="width: 200px;z-index: 99;position: absolute;margin-top: 0px;height: 240px; background: transparent;left:0;" class="privacy-video-block"></div>
                                    <div style="width: 200px;z-index: 99;position: absolute;margin-top: 0px;height: 240px; background: transparent;left:260px;" class="privacy-video-block"></div>
                                    <iframe id="iframe_youtube_mobile" style="margin-left: auto;margin-right: auto;" width="426" height="240" title="{{ $streaming->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                @if($streaming->live_chat_enabled)
                                <div class="col-md-12">
                                    <iframe id="iframe_chat_mobile" width="100%" height="500" frameborder="0"></iframe>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->can('input_coc_local'))
            <div class="col-xs-6 col-md-6 col-lg-6 col-xl-3">
                <a href="{{url('coc/create/local')}}">
                    <div class="card-box tilebox-three">
                        <div class="text-purple text-center" align="center">
                            <i class="icon-calender text-center" style="font-size: 48px;"></i>
                        </div>
                        <div class="text-xs-center">
                            <h5 class="m-b-15 m-t-10">Jadwal CoC</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xs-6 col-md-6 col-lg-6 col-xl-3">
                <a href="{{url('coc/list/admin')}}">
                    <div class="card-box tilebox-three">
                        <div class="text-success text-center" align="center">
                            <i class="icon-check text-center" style="font-size: 48px;"></i>
                        </div>
                        <div class="text-xs-center">
                            <h5 class="m-b-15 m-t-10">Complete CoC</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (Auth::user()->can('liquid_create_liquid'))
        <div class="col-xs-6 col-md-6 col-lg-6 col-xl-3">
            <a href="{{route('dashboard-admin.liquid-jadwal.index')}}">
                <div class="card-box tilebox-three">
                    <div class="text-success text-center" align="center">
                        <i class="icon-chart text-center" style="font-size: 48px;"></i>
                    </div>
                    <div class="text-xs-center">
                        <h6 class="m-b-15 m-t-10">Liquid : Admin</h6>
                    </div>
                </div>
            </a>
        </div>
        @endif
        <div class="col-xs-6 col-md-6 col-lg-6 col-xl-3">
            <a href="{{url('coc')}}">
                <div class="card-box tilebox-three">
                    <div class="text-warning text-center" align="center">
                        <i class="icon-bubbles text-center" style="font-size: 48px;"></i>
                    </div>
                    <div class="text-xs-center">
                        <h5 class="m-b-15 m-t-10">CoC</h5>
                    </div>
                </div>
            </a>
        </div>
        @if (Auth::user()->isAtasanLiquid())
        <div class="col-xs-6 col-md-6 col-lg-6 col-xl-3">
            <a href="{{route('dashboard-atasan.liquid-jadwal.index')}}">
                <div class="card-box tilebox-three">
                    <div class="text-info text-center" align="center">
                        <i class="icon-user-following text-center" style="font-size: 48px;"></i>
                    </div>
                    <div class="text-xs-center">
                        <h6 class="m-b-15 m-t-10">Liquid : Atasan</h6>
                    </div>
                </div>
            </a>
        </div>
        @endif
        @if (Auth::user()->isBawahanLiquid())
        <div class="col-xs-6 col-md-6 col-lg-6 col-xl-3">
            <a href="{{route('dashboard-bawahan.liquid-jadwal.index')}}">
                <div class="card-box tilebox-three">
                    <div class="text-danger text-center" align="center">
                        <i class="icon-user-following text-center" style="font-size: 48px;"></i>
                    </div>
                    <div class="text-xs-center">
                        <h6 class="m-b-15 m-t-10">Liquid : Bawahan</h6>
                    </div>
                </div>
            </a>
        </div>
        @endif
        
        <div class="col-xs-6 col-md-6 col-lg-6 col-xl-3">
            <a href="{{url('commitment')}}">
                <div class="card-box tilebox-three">
                    <div class="text-primary text-center" align="center">
                        <i class="icon-like text-center" style="font-size: 48px;"></i>
                    </div>
                    <div class="text-xs-center">
                        <h5 class="m-b-15 m-t-10">Commitment</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xs-6 col-md-6 col-lg-6 col-xl-3">
            <a href="{{url('commitment/buku')}}">
                <div class="card-box tilebox-three">
                    <div class="text-danger text-center" align="center">
                        <i class="icon-book-open text-center" style="font-size: 48px;"></i>
                    </div>
                    <div class="text-xs-center">
                        <h6 class="m-b-15 m-t-10">Pedoman Perilaku</h6>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xs-6 col-md-6 col-lg-6 col-xl-3">
            <a href="{{url('evp/program')}}">
                <div class="card-box tilebox-three">
                    <div class="text-pink text-center" align="center">
                        <i class="icon-heart text-center" style="font-size: 48px;"></i>
                    </div>
                    <div class="text-xs-center">
                        <h5 class="m-b-15 m-t-10">EVP</h5>
                    </div>
                </div>
            </a>
        </div>
        
    </div>

    @if($is_streaming)
    <div class="row hidden-sm-down" style="margin-top:10px;">
        <div class="col-md-12">
            
            <div class="card card-block">
                <h3 class="card-title m-b-10 text-info"><i class="fa fa-youtube-play"></i> {{ $streaming->title }}</h3> 
                <div>
                    <img src="{{ $streaming->banner_desktop }}" class="img-fluid" onmouseover="this.style.cursor='pointer';" alt="{{ $streaming->title }}" id="btn_streaming" onclick="playStreaming()" style="margin-left: auto;margin-right: auto;">
                    <div id="townhall" style="display:none">
                        {{-- <div style="width: 100%;z-index: 99;position: absolute;margin-top: 0px;height: 300px;background: transparent;left:0;" class="privacy-video-block"></div>
                        <div style="width: 100%;z-index: 99;position: absolute;margin-top: 420px;height: 300px;background: transparent;left:0;" class="privacy-video-block"></div> --}}
                        <div class="row">
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                                <div class="col-md-12">
                                    <div style="width: 100%;z-index: 99;position: absolute;margin-top: 0px;height: 330px;background: transparent;left:0;" class="privacy-video-block"></div>
                                    <div style="width: 100%;z-index: 99;position: absolute;margin-top: 390px;height: 340px;background: transparent;left:0;" class="privacy-video-block"></div>
                                    {{-- <div style="border: 2px solid red; width: 45%;z-index: 99;position: absolute;margin-top: 0px;height: 720px; background: transparent;left:0;" class="privacy-video-block"></div> --}}
                                    {{-- <div style="border: 2px solid rgb(0, 34, 255); width: 45%;z-index: 99;position: absolute;margin-top: 0px;height: 720px; background: transparent;left:750px;" class="privacy-video-block"></div> --}}
                                    {{-- <iframe id="border: 2px solid red; iframe_youtube" style="margin-left: auto;margin-right: auto;" width="1280" height="720" title="{{ $streaming->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
                                    <iframe id="iframe_youtube" style="margin-left: auto;margin-right: auto;" width="100%" height="720" title="{{ $streaming->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                @if($streaming->live_chat_enabled)
                                <div class="col-md-6">
                                    <iframe id="iframe_chat_desktop" width="100%" height="720" frameborder="0"></iframe>
                                </div>
                                {{-- <div class="col-md-6">
                                    <img src="{{ asset($streaming->banner_mobile) }}" class="img-fluid" onmouseover="this.style.cursor='pointer';" alt="{{ $streaming->title }}" id="btn_streaming_mobile" onclick="location.reload()">
                                </div> --}}
                                @endif
                            @else
                                @if($streaming->live_chat_enabled)
                                    <div class="col-md-9">
                                        {{-- <iframe id="iframe_youtube" style="pointer-events: none;margin-left: auto;margin-right: auto;" width="1280" height="720" title="{{ $streaming->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
                                        <iframe id="iframe_youtube" style="pointer-events: none;margin-left: auto;margin-right: auto;" width="100%" height="720" title="{{ $streaming->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    <div class="col-md-3">
                                        <iframe id="iframe_chat_desktop" width="100%" height="720" frameborder="0"></iframe>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <img src="{{ asset($streaming->banner_mobile) }}" class="img-fluid" onmouseover="this.style.cursor='pointer';" alt="{{ $streaming->title }}" id="btn_streaming_mobile" onclick="location.reload()">
                                    </div> --}}
                                @else
                                    <div class="col-md-12">
                                        <iframe id="iframe_youtube" style="pointer-events: none;margin-left: auto;margin-right: auto;" width="100%" height="720" title="{{ $streaming->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row m-t-15">
        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card-box tilebox-two">
                <i class="icon-people pull-xs-right text-muted"></i>
                <h6 class="text-success text-uppercase m-b-15 m-t-10">Jumlah Pegawai</h6>

                <h2 class="m-b-10">
                    <span data-plugin="counterup">{{number_format(\App\StrukturJabatan::getJumlahPegawai())}}</span>
                </h2>
                {{--<progress class="progress progress-striped progress-xs progress-info m-b-0" value="25" max="100">25%--}}
                {{--</progress>--}}

            </div>
        </div>

        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card-box tilebox-two">
                <i class=" icon-user-following pull-xs-right text-muted"></i>
                <h6 class="text-primary text-uppercase m-b-15 m-t-10">Komitmen Pegawai</h6>

                <h2 class="m-b-10">
                    <span data-plugin="counterup">{{number_format(\App\KomitmenPegawai::getKomitmenPegawai())}}</span>
                </h2>
                {{--<progress class="progress progress-striped progress-xs progress-success m-b-0" value="25" max="100">25%--}}
                {{--</progress>--}}

            </div>
        </div>

        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card-box tilebox-two">
                <i class="icon-bubbles pull-xs-right text-muted"></i>
                <h6 class="text-pink text-uppercase m-b-15 m-t-10">Code of Conduct</h6>

                <h2 class="m-b-10" data-plugin="counterup">
                    {{number_format(\App\Coc::getJmlCoc())}}
                </h2>
                {{--<progress class="progress progress-striped progress-xs progress-warning m-b-0" value="25" max="100">25%--}}
                {{--</progress>--}}

            </div>
        </div>

        {{--<div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">--}}
        {{--<div class="card-box tilebox-two">--}}
        {{--<i class="icon-feed pull-xs-right text-muted"></i>--}}
        {{--<h6 class="text-info text-uppercase m-b-15 m-t-10">User Online</h6>--}}
        {{--<h2 class="m-b-10"><span data-plugin="counterup">{{number_format($user_online)}}</span></h2>--}}
        {{--<progress class="progress progress-striped progress-xs progress-danger m-b-0" value="25" max="100">25%--}}
        {{--</progress>--}}

        {{--</div>--}}
        {{--</div>--}}

        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card-box tilebox-two">
                <i class="icon-book-open pull-xs-right text-muted"></i>
                <h6 class="text-info text-uppercase m-b-15 m-t-10">Materi</h6>

                <h2 class="m-b-10">
                    <span data-plugin="counterup">{{number_format(\App\Materi::getJmlMateri())}}</span>
                </h2>
                {{--<progress class="progress progress-striped progress-xs progress-danger m-b-0" value="25" max="100">25%--}}
                {{--</progress>--}}

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            @include('components.slideshow', ['id' => 'slideshow-banner', 'images' => \App\Models\MediaKit::getBannerUrl()])

            {{-- <div class="row m-t-30 m-b-30">
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <h3 class="m-b-10 text-info"><i class="fa fa-youtube-play"></i> AKHLAK Sebagai Core Values</h3>
                    <video width="100%" controls="">
			        <source src="{{asset('assets/videos/akhlak.webm')}}">
                        Your browser does not support HTML5 video.
                    </video>
                    <div class="m-t-10">
			        <h4>Komitmen PLN Group Menerapkan AKHLAK Sebagai Core Values</h4>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <h3 class="m-b-10 text-info"><i class="fa fa-youtube-play"></i> PLN Siap Laksanakan New Normal</h3>

                    <video width="100%" controls="">
                        <source src="{{asset('assets/videos/new_normal_pln.m4v')}}">
                        Your browser does not support HTML5 video.
                    </video>
                    <div class="m-t-10">
                        <h4> PLN Siap Laksanakan New Normal</h4>
                    </div>
                </div>

            </div> --}}
@include('components.video-grid')

            <div class="row m-t-30">
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <h3 class="m-b-10 text-info"><i class="fa fa-group"></i> Gallery CoC</h3>
                    <div id="carousel-gallery" data-ride="carousel" class="carousel slide">
{{--                        <ol class="carousel-indicators">--}}
{{--                        <li data-target="#carousel-gallery" data-slide-to="0" class="active"></li>--}}
{{--                        <li data-target="#carousel-gallery" data-slide-to="1"></li>--}}
{{--                        <li data-target="#carousel-gallery" data-slide-to="2"></li>--}}
{{--                        <li data-target="#carousel-gallery" data-slide-to="3"></li>--}}
{{--                        <li data-target="#carousel-gallery" data-slide-to="4"></li>--}}
{{--                        <li data-target="#carousel-gallery" data-slide-to="5"></li>--}}
{{--                        </ol>--}}
                        <div role="listbox" class="carousel-inner">
                            <?php $x = 1;?>
                            @foreach($gallery_unit as $foto)
                                <div class="card carousel-item {{($x==1)?'active':''}}">
                                    {{--<img class="img-fluid" src="https://budaya.pln.co.id/coc/foto/{{$foto->id}}" alt="{{$foto->judul}}">--}}
                                    <img class="img-fluid" src="{{url('coc/foto-dashboard/'.$foto->id)}}" alt="{{$foto->judul}}">

                                    {{--<div class="carousel-caption">--}}
                                    {{--<h4 class="text-white font-600">{{@$foto->coc->judul}}</h4>--}}
                                    {{--<p>--}}
                                    {{--{{@$foto->coc->organisasi->stxt2}}, {{@$foto->coc->organisasi->stext}}<br>--}}
                                    {{--dipimpin oleh {{@$foto->coc->pemateri->cname}}, {{@$foto->coc->pemateri->strukturPosisi->stext}}<br>--}}
                                    {{--{{@$foto->coc->tanggal_jam->format('d-m-Y')}}--}}
                                    {{--</p>--}}
                                    {{--</div>--}}

                                    <div class="card-block m-b-30">
                                        <div class="row">
                                            <div class="col-xs-2">
                                                <img class="img-thumbnail"
                                                     src="{{asset('assets/images/logo_pln3.png')}}">
                                            </div>
                                            <div class="col-xs-10">
                                                <h4>{{@$foto->coc->judul}}</h4>

                                                <p class="card-text">
                                                    <small class="text-muted">{{@$foto->coc->tanggal_jam->format('j M Y, H:i')}}
                                                        - {{@$foto->coc->organisasi->stxt2}}
                                                        , {{@$foto->coc->organisasi->stext}}</small>
                                                    <br>
                                                    <br>
                                                    Dipimpin oleh {{@$foto->coc->pemateri->cname}}
                                                    , {{@$foto->coc->pemateri->strukturPosisi->stext}}
                                                </p>
                                            </div>
                                        </div>
                                        {{--<p class="card-text">--}}
                                        {{--{{@$foto->coc->organisasi->stxt2}}, {{@$foto->coc->organisasi->stext}}<br>--}}
                                        {{--dipimpin oleh {{@$foto->coc->pemateri->cname}}, {{@$foto->coc->pemateri->strukturPosisi->stext}}<br>--}}
                                        {{--</p>--}}
                                    </div>

                                </div>

                                <?php $x++;?>
                            @endforeach
                        </div>
                        <a href="#carousel-gallery" role="button" data-slide="prev"
                           class="left carousel-control">
                            <span aria-hidden="true" class="fa fa-angle-left"></span> <span
                                    class="sr-only">Previous</span> </a>
                        <a href="#carousel-gallery" role="button" data-slide="next"
                           class="right carousel-control">
                            <span aria-hidden="true" class="fa fa-angle-right"></span> <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>

                @if($berita_col!=null)
                    <div class="col-md-6 col-xs-12 col-sm-12">
                        <h3 class="m-b-10 text-info"><i class="fa fa-newspaper-o"></i> PLN News</h3>

                        <div id="carousel-news" data-ride="carousel" class="carousel slide">
                            <div role="listbox" class="carousel-inner">
                                <?php $x = 1;?>
                                @foreach($berita_col as $berita)
                                    <div class="card carousel-item {{($x==1)?'active':''}}">
                                        <img class="img-fluid" src="{{@$berita->image->full}}"
                                             alt="{{@$berita->title}}">

                                        <div class="card-block">
                                            <div class="row">
                                                <div class="col-xs-2">
                                                    <img class="img-thumbnail"
                                                         src="{{asset('assets/images/logo_pln3.png')}}">
                                                </div>
                                                <div class="col-xs-10">
                                                    {{--<a href="{{@$berita->url}}" target="_blank"><h4>{{@$berita->title}}</h4></a>--}}
                                                    <h4>{{@$berita->title}}</h4>

                                                    <p class="card-text">
                                                        <small class="text-muted">{{@$berita->date}} - www.pln.co.id
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="card-text">{!!  @$berita->excerpt  !!} <a href="{{@$berita->url}}"
                                                                                                class="card-link"
                                                                                                target="_blank">Read
                                                    More</a></p>
                                        </div>

                                    </div>

                                    <?php $x++;?>
                                @endforeach
                            </div>
                            <a href="#carousel-news" role="button" data-slide="prev"
                               class="left carousel-control">
                                <span aria-hidden="true" class="fa fa-angle-left"></span> <span
                                        class="sr-only">Previous</span> </a>
                            <a href="#carousel-news" role="button" data-slide="next"
                               class="right carousel-control">
                                <span aria-hidden="true" class="fa fa-angle-right"></span> <span
                                        class="sr-only">Next</span>
                            </a>
                        </div>


                        {{--<div class="card">--}}
                        {{--<img class="img-fluid" src="{{@$berita->image->full}}" alt="{{@$berita->title}}">--}}
                        {{--<div class="card-block">--}}
                        {{--<div class="row">--}}
                        {{--<div class="col-xs-2">--}}
                        {{--<img class="img-thumbnail" src="{{asset('assets/images/logo_pln3.png')}}" >--}}
                        {{--</div>--}}
                        {{--<div class="col-xs-10">--}}
                        {{--<a href="{{@$berita->url}}" target="_blank"><h4>{{@$berita->title}}</h4></a>--}}
                        {{--<h4>{{@$berita->title}}</h4>--}}
                        {{--<p class="card-text">--}}
                        {{--<small class="text-muted">{{@$berita->date}} - www.pln.co.id</small>--}}
                        {{--</p>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<p class="card-text">{!!  @$berita->excerpt  !!} <a href="{{@$berita->url}}" class="card-link" target="_blank">Read More</a></p>--}}
                        {{--</div>--}}
                        {{--</div>--}}

                    </div>
                @endif
            </div>

            <!-- END carousel-->
            <div class="m-t-30" align="center" style="color:#BEBEBE;">
                {!! $inspire !!}
            </div>
        </div>

        <!-- sample modal content -->
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                        <h4 class="modal-title" id="myModalLabel">Employee Volunteer Program</h4>
                    </div>
                    <div class="modal-body" align="center">
                        <a href="{{url('evp')}}"><img src="{{asset('assets/images/big/papua.png')}}" class="img-fluid center"></a>
                        {{--<a href="{{asset('assets/doc/evp-papua.pdf')}}" target="_blank"><img src="{{asset('assets/images/big/papua.png')}}" class="img-fluid center"></a>--}}
                    </div>
                    <div class="modal-footer">
                        {{--<button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>--}}
                        {{--                        <button type="button" class="btn btn-primary waves-effect" onclick="window.location.href='{{url('evp')}}'">More Info</button>--}}
                        <button type="button" class="btn btn-primary waves-effect" onclick="window.location.href='{{asset('assets/doc/lampiran_evp.docx')}}'">Surat Pernyataan</button>
                        <button type="button" class="btn btn-primary waves-effect" onclick="window.location.href='{{asset('assets/doc/form_data_pribadi_dan_surat_pernyataan_kesehatan.docx')}}'">Form Data Diri</button>
                        {{--<button type="button" class="btn btn-primary dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"> Download <span class="caret"></span> </button>--}}
                        {{--<div class="dropdown-menu">--}}
                        {{--<a class="dropdown-item" href="{{asset('assets/doc/form_data_pribadi_dan_surat_pernyataan_kesehatan.docx')}}">Form Data Diri</a>--}}
                        {{--<a class="dropdown-item" href="{{asset('assets/doc/lampiran_evp.docx')}}">Surat Pernyataan</a>--}}
                        {{--</div>--}}

                        {{--                        <button type="button" class="btn btn-primary waves-effect" onclick="window.location.href='{{asset('assets/doc/evp-papua.pdf')}}'">More Info</button>--}}
                        {{--<button type="button" class="btn btn-primary waves-effect" onclick="window.location.href='{{url('evp')}}'">More Info</button>--}}
                        {{--<button type="button" class="btn btn-primary waves-effect waves-light" onclick="window.location.href='{{url('evp/register')}}'">Join Now!</button>--}}
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- sample modal content -->
        <div id="modalMaintenance" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalMaintenance" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                        <h4 class="modal-title" id="myModalLabel">Jadwal Maintenance</h4>
                    </div>
                    <div class="modal-body" align="left">
                        <img src="{{asset('assets/images/big/maintenance.png')}}" class="img-fluid center" height="100">
                        <div class="m-t-20 p-20">
                            <h4>Semangat Pagi Kawan-Kawan,</h4>
                            <p class="m-t-20">Saat ini, sedang dilakukan maintenance di aplikasi KOMANDO yang dilaksanakan mulai hari <b>Kamis – Minggu, 15 – 18 Agustus 2019</b>.</p>
                            <p>Create Room dan Upload Materi untuk sementara tidak dapat digunakan selama proses maintenance, dan dapat digunakan kembali pada tanggal <b>19 Agustus 2019</b>.</p>
                            <p>Mohon maaf atas ketidaknyamanannya.</p>
                            <p>Demikian kami sampaikan, terimakasih.</p>
                            <p>Salam,<br>
                                ADMIN KOMANDO
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <!-- sample modal content -->
        <div id="modalCorona" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCorona" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                        <h4 class="modal-title" id="myModalLabel">Bersama Perangi Corona</h4>
                    </div>
                    <div class="modal-body" align="left">
                        <img src="{{asset('assets/images/corona.jpg')}}" class="img-fluid center" height="70">
                        <div class="m-t-20 p-20">
                            <h4>Semangat Pagi Kawan-Kawan,</h4>
                            <p class="m-t-20">Menghadapi pandemi virus Corona. Silakan catat suhu badan Anda pada form di bawah ini setiap hari.</p>
                            <div align="center">
                                {!! Form::open(['url'=>'corona/store','class'=>'form-inline']) !!}
{{--                                <form class="form-inline" method="post">--}}
                                    <div class="form-group">
{{--                                        <label for="suhu_badan">Suhu</label>--}}
                                        <input type="number" step="0.1" class="form-control" id="suhu_badan" name="suhu"
                                               placeholder="Suhu Badan (ex: 36.5)" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="keterangan" name="keterangan"
                                               placeholder="Keterangan (optional)">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
{{--                                </form>--}}
                                {!! Form::close() !!}

                            </div>
                            <br>
                            <p>Lindungi Diri, Lindungi Sesama. Terimakasih.</p>
                            <p>Salam,<br>
                                ADMIN KOMANDO
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
@stop

@section('javascript')

    <script>
        $(window).load(function () {
            
            {{--  @if(Auth::user()->cekSuhuBadan())
                $('#modalCorona').modal('show');
            @else  --}}
            {{--@for($x=2017;$x<=date('Y');$x++)--}}
            {{--                @if(Auth::user()->cekBelumTandaTangan($x))--}}


            @if(!Auth::user()->hasRole(['direksi','komisaris','shap']))
            @if(Auth::user()->cekBelumTandaTangan(date('Y')))
            swal({
                        title: "Anda belum melakukan tandatangan komitmen {{date('Y')}}",
                        text: "Organisasi: {{Auth::user()->getOrgText()}}",
                        text: "{{ (Auth::user()->hasRole(['direksi','komisaris']))?'Klik tombol di bawah ini untuk menandatangani komitmen':'Klik tombol di bawah ini untuk membaca komitmen' }}",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: 'btn-secondary waves-effect',
                        confirmButtonClass: 'btn-primary waves-effect waves-light',
                        confirmButtonText: '{{ (Auth::user()->hasRole(['direksi','komisaris']))?'Tandatangan':'Baca Komitmen' }}',
                        //                closeOnConfirm: false,
                    }, function (isConfirm) {
                        if (isConfirm) {
                            //                    swal("Terimakasih!", "Anda telah melakukan komitmen 2017.", "success");
                            window.location.href = '{{(Auth::user()->hasRole(['direksi','komisaris']))?url('commitment/direksi-komisaris/'.date('Y')):url('commitment/pedoman-perilaku/tahun/'.date('Y'))}}'
                        }
                        //                else {
                        //                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                        //                }
                    });
            @else
                // $('#modalMaintenance').modal('show');
            @endif
            @endif

            {{--  @endif  --}}

        {{--@endfor--}}
        });

        @if($is_streaming)
        function playStreaming(){
            // var videoid = 'PViMaJaGDgo';
            // var videoid = 'ZthmoU-Eanc';

            $('#btn_streaming').hide();
            $('#townhall').show();
            $('#iframe_youtube').attr('src','{{ $streaming->url }}');
            @if($streaming->live_chat_enabled)
                $('#iframe_chat_desktop').attr('src','{{ $streaming->live_chat_url }}&embed_domain='+window.location.hostname);
            @endif
            
        }

        function playStreamingMobile(){
            // var videoid = 'PViMaJaGDgo';
            // var videoid = 'ZthmoU-Eanc';

            $('#btn_streaming_mobile').hide();
            $('#townhall_mobile').show();
            $('#iframe_youtube_mobile').attr('src','{{ $streaming->url }}');
            @if($streaming->live_chat_enabled)
                $('#iframe_chat_mobile').attr('src','{{ $streaming->live_chat_url }}&embed_domain='+window.location.hostname);
            @endif    
            
        }
        @endif
    </script>
@stop

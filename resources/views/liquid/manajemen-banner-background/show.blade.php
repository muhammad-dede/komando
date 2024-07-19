@extends('layout')

@push('styles')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Sweet Alert css -->
    <link href="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/fileuploader-2.2/dist/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('vendor/fileuploader-2.2/dist/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/light-gallery/dist/css/lightgallery.min.css') }}"/>
    <style>
        .select2 {
            width: 100% !important;
        }

        .fileuploader-input-button {
            background: #039cfd !important;
        }

        .label-upload {
            position: absolute;
            top: 48px;
            font-size: 11px;
            font-style: italic;
            color: #789bec;
            font-weight: bold;
        }

        .embed-responsive {
            position: relative;
            display: block;
            width: 100%;
            padding: 0;
            overflow: hidden;
        }

        .embed-responsive::before {
            display: block;
            content: "";
        }

        .embed-responsive .embed-responsive-item,
        .embed-responsive iframe,
        .embed-responsive embed,
        .embed-responsive object,
        .embed-responsive video {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .embed-responsive-21by9::before {
            padding-top: 42.857143%;
        }

        .embed-responsive-16by9::before {
            padding-top: 56.25%;
        }

        .embed-responsive-4by3::before {
            padding-top: 75%;
        }

        .embed-responsive-1by1::before {
            padding-top: 100%;
        }

    </style>
@endpush

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12 col-lg-6">
            <h4 class="page-title">Edit Media & Banner</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xs-12 lh-70 align-right">
            <a href="{{ route('manajemen-media-banner.index') }}" class="btn btn-primary"><em
                        class="fa fa-arrow-left"></em> Kembali</a>
        </div>
    </div>
@stop

@section('content')
    <form action="" method="post">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link previous active" data-toggle="tab" href="#informasi" role="tab"
                                   aria-controls="home" aria-selected="true">Informasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link next" data-toggle="tab" href="#file" role="tab"
                                   aria-controls="profile" aria-selected="false">File</a>
                            </li>
                        </ul>
                        <div class="tab-content pad-1rem">
                            <div class="tab-pane previous fade show active in" id="informasi" role="tabpanel"
                                 aria-labelledby="home-tab">
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Judul Media & Banner<span class="text-danger">*</span></label>
                                                <input disabled value="{{ $item->judul }}" type="text"
                                                       name="media_banner" class="form-control"
                                                       placeholder="Tulis Judul" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Jenis <span class="text-danger">*</span></label>
                                                        <input disabled value="{{ $item->jenis }}" type="text"
                                                               name="media_banner" class="form-control"
                                                               placeholder="Tulis Judul" required=""></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Status <span class="text-danger">*</span></label>
                                                        <input disabled value="{{ $item->status }}" type="text"
                                                               name="media_banner" class="form-control"
                                                               placeholder="Tulis Judul" required="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane next fade" id="file" role="tabpanel" aria-labelledby="profile-tab">
                                <div id="banner" class="row">
                                    @foreach($item->getImages() as $media)
                                        <div class="col-sm-4 col-md-3">
                                            <div class="card-box radius-10">
                                                <div class="item" data-src="{{ $media->getUrl() }}">
                                                    {!! app_media_thumbnail($media) !!}
                                                </div>
                                            </div>
                                            <input class="form-control" type="text" value="{{ $media->getUrl() }}">
                                        </div>
                                    @endforeach
                                </div>

                                <div class="media">
                                    <div class="row">
                                        @foreach($item->getVideos() as $media)
                                            <div class="col-md-6 col-xs-12 col-sm-12">
                                                {!! app_media_thumbnail($media) !!}
                                                <input class="form-control" type="text" value="{{ $media->getUrl() }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <!-- Sweet Alert js -->
    <script type="text/javascript" src="{{asset('vendor/fileuploader-2.2/dist/jquery.fileuploader.min.js')}}"></script>
    <script src="{{ asset('vendor/light-gallery/dist/js/lightgallery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#banner').lightGallery({
                thumbnail: true,
                selector: '.item'
            });
        });

    </script>
@endpush

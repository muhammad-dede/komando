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
    <link href="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/fileuploader-2.2/dist/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <link href="{{ asset('vendor/fileuploader-2.2/dist/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
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
    </style>
@endpush

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12 col-lg-6">
            <h4 class="page-title">Tambah Media & Banner</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xs-12 lh-70 align-right">
            <a href="{{ url('manajemen-media-banner') }}" class="btn btn-primary"><em class="fa fa-arrow-left"></em> Kembali</a>
        </div>
    </div>
@stop

@section('content')
    @include('components.flash')
    <form action="{{ route('manajemen-media-banner.store') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        @include('liquid.manajemen-banner-background._form')

                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
										<label for="pernr_leader" class="form-control-label">Upload File</label>
										<span class="font-italic text-danger video-warn" style="visibility: hidden">Format file .mp4 dan max size 300MB</span>
                                        <input type="file" name="media">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-t-20">
                            <div class="col-md-6 col-xs-12">
                            </div>
                            <div class="col-md-6 col-xs-12 pull-right">
                                <div class="button-list">
                                    <div class="button-list">
                                        <button type="submit" id="submit" class="btn btn-primary btn-lg pull-right"><em
                                                    class="fa fa-save"></em>
                                            Save
                                        </button>
                                    </div>
                                    <a href="{{ url('manajemen-media-banner') }}" class="btn btn-warning btn-lg pull-right"><em
                                                class="fa fa-times"></em>
                                        Cancel
                                    </a>
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
    <script type="text/javascript">
        const VIDEO = '{{ \App\Enum\MediaKitJenis::VIDEO }}';
        const MAX_SIZE_VIDEO = '{{ config('komando.max_upload_video') }}';
        const MAX_SIZE_IMAGE = '{{ config('komando.max_upload_image') }}';

        $(document).ready(function () {
            var uploader = $('input[name="media"]').fileuploader({
                enableApi: true,
                fileMaxSize: MAX_SIZE_IMAGE,
                extensions: ['mp4','jpg','png','jpeg'],
                thumbnails: {
                    removeConfirmation: false
                }
            });

            $(".select2").select2();

            var api = $.fileuploader.getInstance(uploader);

            $('select[name="jenis"]').on('change', function (e) {
				let warnWording = $('.video-warn');
                if ($(this).val() == VIDEO) {
                    api.setOption('fileMaxSize', MAX_SIZE_VIDEO);
                    api.setOption('extensions', ['mp4','webm']);
					warnWording.css('visibility', 'visible');
                } else {
                    api.setOption('fileMaxSize', MAX_SIZE_IMAGE);
                    api.setOption('extensions', ['jpg', 'png', 'jpeg']);
					warnWording.css('visibility', 'hidden');
                }
            })
        });

    </script>
@endpush

@extends('layout')

@section('css')
    <link href="{{ asset('vendor/fileuploader-2.2/dist/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/fileuploader-2.2/dist/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
@stop

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">Show Liquid #{{ $liquid->id }}</h4>
        </div>
        <div class="col-md-6 col-xs-12 lh-70 align-right">
            <a href="{{ url('liquid/'.Request::segment(2).'/edit') }}" class="btn btn-warning"><em
                        class="fa fa-pencil"></em> Edit Liquid</a>
            <a href="{{ url('dashboard-admin/liquid-jadwal') }}" class="btn btn-primary"><em class="fa fa-arrow-right"></em> Back Dashboard</a>
        </div>
    </div>
@stop

@section('content')

    @include('components.flash')

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                @include('components.liquid-tab-show', ['active' => 'dokumen'])

                <div class="row">
                    <div class="col-md-6">
                        <br>
                        <div class="bold-text">Surat / Dokumen</div>
                        @foreach($dokumen as $file)
                            <div class="wrapper-file">
                                <a href="{{ $file->getUrl() }}" target="_blank"><em class="fa fa-file fa-3x download"></em>
                                    <div>{{ $file->file_name }}</div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript" src="{{asset('vendor/fileuploader-2.2/dist/jquery.fileuploader.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('input[name="dokumen"]').fileuploader({
                addMore: true,
                fileMaxSize: 5,
                extensions: ['pdf'],
                thumbnails: {
                    removeConfirmation: false
                }
            });
        });
    </script>
@stop

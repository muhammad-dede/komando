@extends('layout')

@push('styles')
    <link href="{{ asset('vendor/fileuploader-2.2/dist/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/fileuploader-2.2/dist/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
    <style>
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
        <div class="col-md-12 col-xs-12">
            <h4 class="page-title">Edit Liquid #{{ $liquid->id }}</h4>
        </div>
    </div>
@stop

@section('content')

    @include('components.flash')

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                @include('components.liquid-tab', ['active' => 'dokumen'])
                <form action="{{ route('liquid.dokumen.update', $liquid) }}" method="POST"
                      enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    {!! method_field('PUT') !!}
                    <div class="row">
                        <div class="col-md-6">
                            <input type="file" name="dokumen">
                        </div>

                        <div class="col-md-6">
                            <br>
                            <table class="table table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama File</th>
                                        <th>Ukuran File</th>
                                        <th class="align-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($dokumen as $file)
                                    <tr>
                                        <td><a href="{{ $file->getUrl() }}" target="_blank">{{ $file->file_name }}</a></td>
                                        <td>{{ $file->humanReadableSize }}</td>
                                        <td align="center">
                                            <a href="{{ route('liquid.dokumen.destroy', [$liquid, $file->id]) }}"
                                               class="badge badge-danger hapus" data-toggle="tooltip" title="hapus"><em
                                                        class="fa fa-trash-o fa-2x"></em></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-xs-6 mar-b-1rem">
                            <button type="submit" class="btn btn-primary btn-lg pull-right">
                                Upload Dokumen
                            </button>
                        </div>
                        <div class="col-xs-12">
                            <a href="{{ url('liquid/'.Request::segment(2).'/gathering/edit') }}"  class="btn btn-primary btn-lg pull-right">
                                <i aria-hidden="true" class="fa fa-arrow-right"></i> Next
                            </a>
                            <a href="{{ url('liquid/'.Request::segment(2).'/peserta/edit') }}" class="mar-r-1rem btn btn-warning btn-lg pull-right">
                                <i aria-hidden="true" class="fa fa-arrow-left"></i> Previous
                            </a>
                        </div>
                    </div>
                </form>
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
            $( "<div class='label-upload'>Only .Pdf file format and max size 5MB.</div>" ).appendTo( $( ".fileuploader-input" ) );
        });
    </script>
@stop

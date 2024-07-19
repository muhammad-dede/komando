@extends('layout')
@inject('liquidService', 'App\Services\LiquidService')

@push('styles')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@section('title')
    <div class="row">
        <div class="lh-70 col-md-3 col-lg-4 col-xs-12">
            <h4 class="page-title">Master Data Media & Banner</h4>
        </div>
        <div class="col-md-9 col-lg-8 col-xs-12 lh-70 align-right">
            <a href="{{ url('manajemen-media-banner/create') }}" class="btn btn-primary"><em class="fa fa-plus"></em> Tambah Master Data</a>
        </div>
    </div>
    <div class="row">
        @include('liquid.manajemen-banner-background._table')
    </div>
@stop

@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.datatable').DataTable();
        });
    </script>
@endpush

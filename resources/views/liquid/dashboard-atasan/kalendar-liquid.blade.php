@extends('layout')
@inject('liquidService', 'App\Services\LiquidService')

@push('styles')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@section('title')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            @include('liquid.dashboard-atasan._title-page')
        </div>
    </div>
@stop

@section('content')
    @include('liquid.dashboard-atasan._resolusi')

    <div class="row m-t-20 dashboard-atasan-content">
        <div class="col-lg-4 col-md-12">
            @include('liquid.dashboard-atasan._side-menu')
        </div>
        <div class="col-lg-8">
            @include('liquid.dashboard-atasan._kalendar')
        </div>
    </div>

    @include('liquid.dashboard-atasan._history-penilaian')
@stop

@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
            $('.datatable').DataTable();

            if (innerWidth < 1024) {
                $('.dashboard-atasan-content').attr('style', '');
            } else {
                $('.dashboard-atasan-content').attr('style', 'flex-wrap: nowrap;display: flex;align-content: center;justify-content: center;');
            }

            window.onresize = function() {
                if (window.innerWidth < 1024) {
                    $('.dashboard-atasan-content').attr('style', '');
                } else {
                    $('.dashboard-atasan-content').attr('style', 'flex-wrap: nowrap;display: flex;align-content: center;justify-content: center;');
                }
            }
        });
    </script>
@endpush

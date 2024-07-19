@push('styles')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@extends('layout')
@inject('liquidService', 'App\Services\LiquidService')

@section('title')
    <div class="row">
        <div class="lh-70 col-md-9 col-xs-12">
            @include('liquid.dashboard-atasan._title-page')
        </div>
    </div>
@stop

@section('content')
    <div class="row m-t-20 dashboard-admin-second-section">
        <div class="col-md-3">
            @include('liquid.dashboard-bawahan._side-menu')
        </div>
        <div class="col-md-9 pull-right">
            @include('liquid.dashboard-bawahan._table-resolusi-atasan')
        </div>
    </div>
    @include('liquid.dashboard-bawahan._history-penilaian')
    @include('liquid.dashboard-bawahan._activity-log')
@stop


@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('.datatable').DataTable();
            $('#table-activity').DataTable();
        });
    </script>
@endpush

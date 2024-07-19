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
        <div class="col-md-4 col-lg-4 col-xs-12">
            @include('liquid.dashboard-atasan._side-menu')
        </div>
        <div class="col-md-8 col-lg-8 col-xs-12 pull-right">
            <form action="{{ url('dashboard-atasan/liquid-status') }}" method="get">
                <div class="row">
                    <div class="col-md-10 col-xs-12" style="margin-bottom: 10px;">
                        <div class="float-right width-full">
                            <select class="form-control" name="year">
                                <option>Filter Periode</option>
                                @foreach ($years as $item)
                                    <option value="{{ $item->year }}" {{ $item->isSelected ? 'selected' : '' }}>{{ $item->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-12">
                        <button type="submit" class="btn btn-primary width-full" style="margin-bottom: 10px;">Filter</button>
                    </div>
                </div>
            </form>
            @include('liquid.dashboard-atasan._tracking-status')
        </div>
    </div>
    @include('liquid.dashboard-atasan._history-penilaian')
    @include('liquid.dashboard-atasan._activity-log')
@stop

@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
            $('.datatable').DataTable();
        });
    </script>
@endpush

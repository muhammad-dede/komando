@extends('layout')
@inject('liquidService', 'App\Services\LiquidService')

@section('title')
    <form action="/dashboard-admin/liquid-jadwal">
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-2 col-xs-12">
                <h4 class="page-title">Dashboard LIQUID</h4>
            </div>

            <div class="col-md-3 col-xs-12" style="display: flex; align-items: center;">
                <div class="float-right width-full">
                    @include('components.dropdown-unit')
                </div>
            </div>

            @if(auth()->user()->can(\App\Enum\LiquidPermission::VIEW_ALL_UNIT) && (is_unit_pusat(request('unit_code', auth()->user()->getKodeUnit()))))
                <div class="col-md-2 col-xs-12" style="display: flex; align-items: center;">
                    <div class="float-right width-full">
                        @include('components.dropdown-divisi')
                    </div>
                </div>
            @endif

            <div class="col-md-2 col-xs-12" style="display: flex; align-items: center;">
                <div class="float-right width-full">
                    <select class="select2 form-control" name="year">
                        <option>Filter Periode</option>
                        @foreach ($years as $item)
                            <option value="{{ $item->year }}" {{ $item->isSelected ? 'selected' : '' }}>{{ $item->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-1 col-xs-12" style="display: flex; align-items: center;">
                <div class="float-right width-full">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </form>
@stop

@section('content')
    @include('liquid.dashboard-admin._dashboard-liquid')
    <div class="row m-t-20">
        <div class="col-lg-3 col-12">
            @include('liquid.dashboard-admin._side-component')
        </div>
        <div class="col-lg-9 col-12 pull-right">
            @include('liquid.dashboard-admin._kalendar')
        </div>
    </div>
    @include('liquid.dashboard-admin._history-liquid')
@stop

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <script>
        $(document).ready(function () {
            let endDate = new Date();

            jQuery('[class^=input-daterange]').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy',
                weekStart: 1,
                endDate: endDate,
                clearBtn: true
            });
        })
    </script>
@endsection

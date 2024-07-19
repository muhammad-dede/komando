@extends('layout')
@inject('liquidService', 'App\Services\LiquidService')

@section('title')
    <div class="row">
        <div class="lh-70 col-md-3 col-lg-4 col-xs-12">
            <h4 class="page-title">Dashboard LIQUID</h4>
        </div>

        <div class="col-md-9 col-lg-8 col-xs-12">
            <form action="{{ url('dashboard-admin/liq-status') }}" method="get">
                <div class="row">
                    <div class="col-md-3 col-xs-12 lh-70 ">
                        <div class="lh-70 float-right width-full">
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

                    <div class="col-md-1 col-xs-12">
                        <button type="submit" class="btn btn-primary width-full" style="margin-top: 18px; margin-bottom: 15px;">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('content')
    @include('liquid.dashboard-admin._dashboard-liquid')
    <div class="row m-t-20 dashboard-admin-second-section">
        <div class="col-md-4 col-lg-3 col-xs-12">
            @include('liquid.dashboard-admin._side-component')
        </div>
        <div class="col-md-8 col-lg-9 col-xs-12 pull-right">
            @include('liquid.dashboard-admin._keterangan-tracking')
        </div>
    </div>
    @include('liquid.dashboard-admin._history-liquid')
@stop


@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
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
                @include('components.liquid-tab-show', ['active' => 'gathering'])
                <div class="tab-content comp-tab-content" style="min-height: 350px;">
                    <form action="{{ route('liquid.gathering.update', $liquid) }}" method="POST">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai </label>
                                    <div>
                                        <div class="input-group">
                                            <input disabled  type="text" class="form-control tanggal"
                                                   name="gathering_start_date" placeholder="dd-mm-YYYY"
                                                   value="{{ old('gathering_start_date', $liquid->getGatheringStartDate() ) }}"/>
                                            <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-calender"></em></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Selesai</label>
                                    <div>
                                        <div class="input-group">
                                            <input disabled  type="text" class="form-control tanggal"
                                                   name="gathering_end_date" placeholder="dd-mm-YYYY"
                                                   value="{{ old('gathering_end_date', $liquid->getGatheringEndDate()) }}"
                                                   autocomplete="off"/>
                                            <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-calender"></em></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lokasi Gathering</label>
                                    <div>
                                        <div class="input-group">
                                            <input disabled  type="text" class="form-control"
                                                   name="gathering_location"  placeholder="Lokasi Gathering"
                                                   value="{{ old('gathering_location', $liquid->gathering_location) }}">
                                            <span class="input-group-addon bg-custom b-0"><em
                                                        class="icon-location-pin"></em></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Link Virtual Meeting</label>
                                    <div>
                                        <div class="input-group">
                                            <input disabled type="text" class="form-control" name="link_meeting" placeholder="Link meeting" value="{{ old('link_meeting', $liquid->link_meeting) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <div>
                                        <div class="input-group">
                                            <textarea disabled name="keterangan" cols="30" rows="10" class="form-control" style="width: 400px;">{{ old('keterangan', $liquid->keterangan) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/plugins/multiselect/js/jquery.multi-select.js')}}"></script>
    <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script>

        jQuery('.tanggal').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            orientation: 'left'
        });
    </script>
@stop

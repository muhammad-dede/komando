@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Update Tema CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                {{--{!! Form::model($tema_coc, ['method'=>'PATCH', 'url'=>'master-data/tema/'.$tema_coc->id])!!}--}}
                {!! Form::open(['url'=>'master-data/tema-coc/'.$tema_coc->id.'/edit'])!!}
                {{--<fieldset class="form-group">--}}
                    {{--<label for="tema">Tema CoC</label>--}}
                    {{--<input type="text" class="form-control" id="tema" placeholder="Masukkan Tema CoC" name="tema">--}}
                    {{--{{ Form::text('tema', null, ['placeholder' => 'Masukkan Tema CoC', 'class' => 'form-control', 'id'=>'tema']) }}--}}
                    {{--<small class="text-muted">We'll never share your email with anyone else.</small>--}}
                {{--</fieldset>--}}
                <div class="row">
                    <div class="col-md-6 pull-right">
                        <div class="m-l-20">
                            {{--<div class="form-group" style="position: relative;z-index: 1000;">--}}
                                {{--<label>Tanggal</label>--}}
                                {{--<div>--}}
                                    {{--<div class="input-daterange input-group" id="date-range">--}}
                                        {{--<input type="text" class="form-control" name="start_date" value="{{$tema_coc->start_date->format('d-m-Y')}}"/>--}}
                                        {{--<span class="input-group-addon bg-custom b-0">to</span>--}}
                                        {{--<input type="text" class="form-control" name="end_date" value="{{$tema_coc->end_date->format('d-m-Y')}}"/>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group">
                                <label for="tema_id" class="form-control-label">Tema</label>

                                <div>
                                    {!! Form::select('tema_id', $tema_list, $tema_coc->tema_id, ['class'=>'form-control select2', 'id'=>'tema_id', 'width'=>'100%', 'style'=>'width: 100% !important; padding: 0; z-index:10000;']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="start_date" class="form-control-label">Start Date</label>

                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="dd-mm-yyyy"
                                               id="start_date"
                                               name="start_date" value="{{$tema_coc->start_date->format('d-m-Y')}}">
                                        <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="end_date" class="form-control-label">End Date</label>

                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="dd-mm-yyyy"
                                               id="end_date"
                                               name="end_date" value="{{$tema_coc->end_date->format('d-m-Y')}}">
                                        <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 pull-right">
                        <div class="button-list">
                            <button type="button" class="btn btn-warning btn-lg pull-right"
                                    onclick="window.location.href='{{url('master-data/tema')}}';"><i
                                        class="fa fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg pull-right"><i class="fa fa-save"></i>
                                Update
                            </button>

                        </div>

                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>

    <script>
        $(document).ready(function () {
            $("#tema_id").select2();
            jQuery('#date-range').datepicker({
                autoclose: true,
                toggleActive: true,
                todayHighlight: true,
                orientation: 'bottom',
                format: 'dd-mm-yyyy'
            });
            jQuery('#start_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
            jQuery('#end_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });
        });
    </script>
@stop
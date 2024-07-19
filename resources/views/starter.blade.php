@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">Starter Page</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                HELLO


                <div class="row m-t-20">
                    <div class="col-md-6">
                        <a href="{{url('coc')}}" type="button" class="btn btn-primary btn-lg pull-left">
                            <i class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                    <div class="col-md-6">
                        <button id="btn_next" type="submit" class="btn btn-primary btn-lg disabled pull-right" disabled>
                            Next <i class="fa fa-chevron-circle-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')

@stop
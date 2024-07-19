@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">Create Tema CoC</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                {!! Form::open(['url'=>'master-data/tema/'])!!}
                <fieldset class="form-group">
                    <label for="tema">Tema CoC</label>
                    <input type="text" class="form-control" id="tema" placeholder="Masukkan Tema CoC" name="tema">
                    {{--<small class="text-muted">We'll never share your email with anyone else.</small>--}}
                </fieldset>

                <div class="row">
                    <div class="col-md-12 pull-right">
                        <div class="button-list">
                            <button type="button" class="btn btn-warning btn-lg pull-right"
                                    onclick="window.location.href='{{url('master-data/tema')}}';"><i
                                        class="fa fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg pull-right"><i class="fa fa-save"></i>
                                Save
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

@stop
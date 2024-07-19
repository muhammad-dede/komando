@extends('layout')

@section('css')

@stop

@section('title')
    {{--<div class="btn-group pull-right m-t-15">--}}
    {{--<button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light"--}}
    {{--data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i--}}
    {{--class="fa fa-cog"></i></span></button>--}}
    {{--<div class="dropdown-menu">--}}
    {{--<a class="dropdown-item" href="#">Action</a>--}}
    {{--<a class="dropdown-item" href="#">Another action</a>--}}
    {{--<a class="dropdown-item" href="#">Something else here</a>--}}
    {{--<div class="dropdown-divider"></div>--}}
    {{--<a class="dropdown-item" href="#">Separated link</a>--}}
    {{--</div>--}}

    {{--</div>--}}
    <h4 class="page-title">Commitment</h4>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12 text-xl-center">
            <div class="card-box">
                <h4>
                    Terimakasih. Anda telah melakkan tandatangan komitmen 2017
                </h4>

                <div class="button-list">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                                           <span class="btn-label"><i class="fa fa-file-text-o"></i>
                                           </span>Baca Pedoman Perilaku
                    </button>

                    <button type="button" class="btn btn-success waves-effect waves-light">
                                           <span class="btn-label"><i class="fa fa-thumbs-o-up"></i>
                                           </span>Lihat Pernyataan Komitmen
                    </button>

                    {{--<button type="button" class="btn btn-info waves-effect waves-light">--}}
                    {{--<span class="btn-label"><i class="fa fa-exclamation"></i>--}}
                    {{--</span>Info</button>--}}

                    {{--<button type="button" class="btn btn-warning waves-effect waves-light">--}}
                    {{--<span class="btn-label"><i class="fa fa-warning"></i>--}}
                    {{--</span>Warning</button>--}}
                    {{--<br>--}}

                    {{--<button type="button" class="btn btn-primary waves-effect waves-light">--}}
                    {{--<span class="btn-label"><i class="fa fa-arrow-left"></i>--}}
                    {{--</span>Left</button>--}}

                    {{--<button type="button" class="btn btn-success waves-effect waves-light">Right--}}
                    {{--<span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i>--}}
                    {{--</span>--}}
                    {{--</button>--}}
                </div>

            </div>
        </div>
    </div>

@stop

@section('javascript')

@stop
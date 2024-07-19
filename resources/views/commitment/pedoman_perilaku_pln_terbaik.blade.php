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

{{--    <h4 class="page-title">{{$pedoman->nomor_urut." ".$pedoman->pedoman_perilaku}}</h4>--}}
    <h5 style="margin-top: 20px;">Komitmen {{$tahun}}</h5>
@stop

@section('content')
    <progress class="progress progress-info progress-xs" value="{{$progress}}" max="100">{{$progress}}%</progress>
    {!! Form::open(['url'=>'commitment/pedoman-perilaku/quiz']) !!}
    {!! Form::hidden('pedoman_perilaku_id', $pedoman->id) !!}
    {!! Form::hidden('tahun', $tahun) !!}
    {!! Form::hidden('token', $token) !!}
    {{--@foreach($list_pedoman as $pedoman)--}}
    <div style="margin-bottom: 10px;">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-block">
                    <div class="row">
                        <div class="col-md-12" style="font-size: 16px; ">
                            {{--{!! @$pedoman->perilaku()->first()->perilaku !!}--}}
                            @include(@$pedoman->template)
                        </div>
                    </div>
                    <hr>
                    {{--<h1 align="center"--}}
                    {{--style="font-size: 60px; font-weight: bold; text-align: center; color: #2ea36e;">--}}
                    {{--DOs</h1>--}}
                    {{--<hr>--}}
                    {{--<ol style="font-size: 18px;">--}}
                    {{--@foreach($pedoman->perilaku()->where('jenis',1)->get() as $perilaku)--}}
                    {{--<li class="card-text" style="padding: 20px;">--}}
                    {{--{!! $perilaku->perilaku !!}--}}
                    {{--</li>--}}
                    {{--@endforeach--}}
                    {{--<ol style="font-size: 18px;color: #2ea36e; ">--}}
                    {{--<ol style="font-size: 18px;color: #7f7f7f; ">--}}
                    {{--</ol>--}}

                    {{--<p class="card-text">--}}
                    <div class="checkbox checkbox-primary">
                        <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                        <label for="checkbox_do">
                            Saya sudah membaca dan memahami
                        </label>
                    </div>
                    {{--</p>--}}
                    {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
                </div>
            </div>
            {{--<div class="col-sm-6" style="display: none" id="box_dont">--}}
            {{--<div class="card card-block">--}}
            {{--<h3 class="card-title"><span class="icon-dislike"></span> Don't</h3>--}}
            {{--<h3 class="card-title"><span class="zmdi zmdi-thumb-down"></span> Don't</h3>--}}
            {{--<h3 class="card-title"><span class="fa fa-thumbs-o-down"></span> Don't</h3>--}}
            {{--<div class="row">--}}
            {{--<div class="col-md-8 vertical-align-middle" align="center">--}}
            {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> Do</h1>--}}
            {{--</div>--}}
            {{--<div class="col-md-12" align="center">--}}
            {{--<img src="{{asset('assets/images/dont.png')}}" width="150">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
            {{--<hr>--}}
            {{--<h1 align="center"--}}
            {{--style="font-size: 60px; font-weight: bold; text-align: center; color: #dc5e52;">--}}
            {{--DON'Ts</h1>--}}
            {{--<hr>--}}
            {{--<ol style="font-size: 18px;color: #dc5e52; ">--}}

            {{--@foreach($pedoman->perilaku()->where('jenis',2)->get() as $perilaku)--}}
            {{--<li class="card-text" style="padding: 20px;">--}}
            {{--{!! $perilaku->perilaku !!}--}}
            {{--</li>--}}
            {{--@endforeach--}}
            {{--</ol>--}}

            {{--<p class="card-text">--}}

            {{--<div class="checkbox checkbox-primary">--}}
            {{--<input id="checkbox_dont" name="checkbox_dont" type="checkbox" value="1">--}}
            {{--<label for="checkbox_dont">--}}
            {{--Saya sudah membaca dan memahami Pedoman Perilaku di atas--}}
            {{--</label>--}}
            {{--</div>--}}

            {{--</p>--}}
            {{--<a href="{{url('commitment/pertanyaan')}}" class="btn btn-primary">Next</a>--}}

            {{--</div>--}}
            {{--<div class="row pull-right" id="btn_next" style="display: none">--}}
            {{--<div class="col-md-12">--}}
            {{--<a href="{{url('commitment/pedoman-perilaku/pertanyaan')}}" class="btn btn-primary btn-lg">Next <i--}}
            {{--class="fa fa-chevron-circle-right"></i></a>--}}
            {{--<button type="submit" class="btn btn-primary btn-lg">Next <i class="fa fa-chevron-circle-right"></i>--}}
            {{--</button>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
    <div class="row pull-right" id="btn_next" style="display: none; position: relative; z-index:10000000;">
        <div class="col-md-12">
            {{--<a href="{{url('commitment/pedoman-perilaku/pertanyaan')}}" class="btn btn-primary btn-lg">Next <i--}}
            {{--class="fa fa-chevron-circle-right"></i></a>--}}
            <button type="submit" class="btn btn-primary btn-lg">Next <i class="fa fa-chevron-circle-right"></i>
            </button>
        </div>
    </div>
    {{--@endforeach--}}
    {{--<div class="row pull-right">--}}
    {{--<div class="col-md-12">--}}
    {{--<a href="{{url('commitment/pertanyaan')}}" class="btn btn-primary">Next</a>--}}
    {{--</div>--}}
    {{--</div>--}}
    {!! Form::close() !!}
@stop

@section('javascript')
    <script>
        //        $('#checkbox_do').click(function () {
        //            if ($('#checkbox_do').is(':checked')) {
        //                $('html, body').animate({scrollTop: 0}, 'fast');
        //            }
        //            $('#box_dont').fadeToggle();
        //        });
        $('#checkbox_do').click(function () {
            $('#btn_next').fadeToggle();
        });
    </script>
@stop
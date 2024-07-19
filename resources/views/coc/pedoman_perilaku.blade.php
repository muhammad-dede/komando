@extends('layout')

@section('css')
<style>
    .nopadding {
        padding: 0 !important;
        margin: 0 !important;
    }
    .nopaddingdo {
        padding-right: -50px !important;
        margin-right: -50px !important;
    }
    .nopaddingdont {
        padding-left: 0 !important;
        margin-left: -5px !important;
    }
    #progress_do, #progress_dont {
        width: 100%;
        background-color: #ddd;
    }

    #bar_do, #bar_dont {
        width: 1%;
        height: 5px;
        background-color: #4CAF50;
    }
</style>
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
    <h4 class="page-title">{{$pedoman->judul}}</h4>
    <p class="text-muted" style="margin-top: -10px;">{{$pedoman->deskripsi}}</p>
@stop

@section('content')
    <progress class="progress progress-info progress-xs" value="80" max="100">80%</progress>
    {{--<progress class="progress progress-info progress-xs" value="{{$progress}}" max="100">{{$progress}}%</progress>--}}
{{--    {!! Form::open(['url'=>'commitment/pedoman-perilaku/quiz']) !!}--}}
{{--    {!! Form::hidden('pedoman_perilaku_id', $pedoman->id) !!}--}}
    {{--@foreach($list_pedoman as $pedoman)--}}
        <div style="margin-bottom: 30px;">
            <div class="row">
                @if($hasDo)
                <div class="col-sm-6 nopadding">
                    {{--<div class="card card-block" style="background-image: url('{{asset('assets/images/do2.png')}}'); background-repeat: no-repeat; background-size: 100%;">--}}
                    <div class="card card-block" style="background-color: #daeff4;">
                        <table border="0" width="100%" class="hidden-sm-down">
                            <tr>
                                <td>
                                    <ul type="square" style="font-size: 18px; color: #1a5261;">
                                        <?php
                                            $string_do = '';
                                        ?>
                                        @foreach($pedoman->perilaku()->where('jenis',1)->get() as $perilaku)
                                            @if(in_array($perilaku->id, array_flatten($coc->ritualCoc()->get(['perilaku_id'])->toArray())))
                                                <li class="card-text" style="padding: 20px;">
                                                    {!! $perilaku->perilaku !!}
                                                    <?php
                                                        $string_do = $string_do.' '.$perilaku->perilaku;
                                                    ?>
                                                </li>
                                            @endif
                                        @endforeach
                                        <?php
                                            $count_word_do = count(explode(' ', $string_do));
                                        ?>
                                    </ul>
                                </td>
                                <td align="right">
                                    <img src="{{asset('assets/images/do2.png')}}" width="150" style="margin-right: -18px;">
                                </td>
                            </tr>
                        </table>

                        <div class="hidden-sm-up">
                            {{--<div class="row">--}}
                                {{--<div class="col-md-12 col-xs-12">--}}
                                    {{--<img src="{{asset('assets/images/do2_2.png')}}" width="150">--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="row">
                            <img src="{{asset('assets/images/do2_2.png')}}" width="150" align="left">
                            </div>
                            {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
                            {{--<hr>--}}
                            {{--<h1 align="center"--}}
                            {{--style="font-size: 60px; font-weight: bold; text-align: center; color: #dc5e52;">--}}
                            {{--DON'Ts</h1>--}}
                            {{--<hr>--}}
                            <div class="row">
                            <ul type="square" style="font-size: 18px; color: #1a5261;">

                                @foreach($pedoman->perilaku()->where('jenis',1)->get() as $perilaku)
                                    @if(in_array($perilaku->id, array_flatten($coc->ritualCoc()->get(['perilaku_id'])->toArray())))
                                        <li class="card-text" style="padding: 20px;">
                                            {!! $perilaku->perilaku !!}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            </div>
                        </div>
                        {{--<p class="card-text">--}}
                        <div id="progress_do" class="m-t-30">
                            <div id="bar_do"></div>
                        </div>
                        <div class="checkbox checkbox-primary m-t-20" id="check_do" style="display: none;">
                            <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                            <label for="checkbox_do">
                                Saya sudah membaca dan memahami Pedoman Perilaku di atas
                            </label>
                        </div>
                        {{--</p>--}}
                        {{--<a href="#" class="btn btn-primary">Go somewhere</a>--}}
                    </div>
                </div>
                @endif
                @if($hasDont)
                <div class="col-sm-6 nopadding"
                     @if($hasDo)
                     style="display: none"
                     @endif
                     id="box_dont">
                    <div class="card card-block" style="background-color: #d4dcdf;">

                        <table border="0" width="100%" class="hidden-sm-down">
                            <tr>
                                <td align="left">
                                    <img src="{{asset('assets/images/dont2.png')}}" width="150" style="margin-left: -18px;">
                                </td>
                                <td>
                                    <ul type="square" style="font-size: 18px;color: #c12621;">
                                        <?php
                                        $string_dont = '';
                                        ?>
                                        @foreach($pedoman->perilaku()->where('jenis',2)->get() as $perilaku)
                                            @if(in_array($perilaku->id, array_flatten($coc->ritualCoc()->get(['perilaku_id'])->toArray())))
                                                <li class="card-text" style="padding: 20px;">
                                                    {!! $perilaku->perilaku !!}
                                                </li>
                                                    <?php
                                                    $string_dont = $string_dont.' '.$perilaku->perilaku;
                                                    ?>
                                            @endif
                                        @endforeach
                                            <?php
                                            $count_word_dont = count(explode(' ', $string_dont));
                                            ?>
                                    </ul>
                                </td>
                            </tr>
                        </table>

                        <div class="hidden-sm-up">
                            {{--<div class="row">--}}
                                {{--<div class="col-md-12" align="center">--}}
                                    {{--<img src="{{asset('assets/images/dont2_2.png')}}" width="150">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="row">
                                <img src="{{asset('assets/images/dont2_2.png')}}" width="150" align="right">
                            </div>
                            {{--<h1 class="card-title"><span class="fa fa-thumbs-o-up"></span> DOs</h1>--}}
                            {{--<hr>--}}
                            {{--<h1 align="center"--}}
                                {{--style="font-size: 60px; font-weight: bold; text-align: center; color: #dc5e52;">--}}
                                {{--DON'Ts</h1>--}}
                            {{--<hr>--}}
                            <div class="row">
                            <ul type="square" style="font-size: 18px;color: #c12621; ">

                                @foreach($pedoman->perilaku()->where('jenis',2)->get() as $perilaku)
                                    @if(in_array($perilaku->id, array_flatten($coc->ritualCoc()->get(['perilaku_id'])->toArray())))
                                    <li class="card-text" style="padding: 20px;">
                                        {!! $perilaku->perilaku !!}
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                            </div>
                        </div>

                        {{--<p class="card-text">--}}

                        <div id="progress_dont" class="m-t-30">
                            <div id="bar_dont"></div>
                        </div>
                        <div class="checkbox checkbox-primary m-t-20" id="check_dont" style="display: none;">
                            <input id="checkbox_dont" name="checkbox_dont" type="checkbox" value="1">
                            <label for="checkbox_dont">
                                Saya sudah membaca dan memahami Pedoman Perilaku di atas
                            </label>
                        </div>

                        {{--</p>--}}
                        {{--<a href="{{url('commitment/pertanyaan')}}" class="btn btn-primary">Next</a>--}}

                    </div>
                    {{--<div class="row pull-right" id="btn_next" style="display: none">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<a href="{{url('commitment/pedoman-perilaku/pertanyaan')}}" class="btn btn-primary btn-lg">Next <i--}}
                                        {{--class="fa fa-chevron-circle-right"></i></a>--}}
                            {{--<button type="submit" class="btn btn-primary btn-lg">Next <i class="fa fa-chevron-circle-right"></i></button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <small>Status :</small>
                    @if($status_checkin=='1')
                        <span class="label label-success"><b>{{App\StatusCheckIn::find($status_checkin)->status}}</b></span>
                    @else
                        <span class="label label-danger"><b>{{App\StatusCheckIn::find($status_checkin)->status}}</b></span>
                    @endif
                </div>
            </div>
            {!! Form::open(['url'=>'coc/pelanggaran/'.$coc->id]) !!}
            {!! Form::hidden('status_checkin', $status_checkin, ['id'=>'status_checkin']) !!}
            <div class="row m-t-20">
                <div class="col-md-6 col-xs-6">
                    <a href="{{url('coc/check-in/'.$coc->id)}}" type="button"
                       class="btn btn-primary btn-lg pull-left">
                        <i class="fa fa-chevron-circle-left"></i> Back</a>
                </div>
                <div class="col-md-6 col-xs-6">
                    <button id="btn_next" type="submit" class="btn btn-primary btn-lg pull-right"
                            style="display: none;">
                        Next <i class="fa fa-chevron-circle-right"></i>
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    {{--@endforeach--}}
    {{--<div class="row pull-right">--}}
    {{--<div class="col-md-12">--}}
    {{--<a href="{{url('commitment/pertanyaan')}}" class="btn btn-primary">Next</a>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--{!! Form::close() !!}--}}
@stop

@section('javascript')
<script>

    $(window).load(function(){

        @if($hasDo)
        setTimeout(function(){ move('bar_do', 'check_do', '{{($count_word_do*8)/2}}'); }, 500);
        @else
        setTimeout(function(){ move('bar_dont', 'check_dont', '{{($count_word_dont*8)/2}}'); }, 500);
        @endif

    });

    $('#checkbox_do').click(function(){
        if ($('#checkbox_do').is(':checked')){
//            $('html, body').animate({scrollTop: 0}, 'fast');
        }
        @if($hasDont)
            move('bar_dont', 'check_dont', '{{($count_word_dont*8)/2}}');
            $('#box_dont').fadeToggle();
        @else
            $('#btn_next').fadeToggle();
        @endif
    });
    $('#checkbox_dont').click(function(){
        $('#btn_next').fadeToggle();
    });

    function move(bar, check, interval=50) {
        var i = 0;
        if (i == 0) {
            i = 1;
            var elem = document.getElementById(bar);
            var width = 1;
            var id = setInterval(frame, interval);
            function frame() {
                if (width >= 100) {
                    clearInterval(id);
                    i = 0;
                    document.getElementById(check).style.display = 'block';
                    document.getElementById(bar).style.display = 'none';
                } else {
                    width++;
                    elem.style.width = width + "%";
                }
            }
        }
    }
</script>
@stop
@extends('layout')

@section('css')

@stop

@section('title')
{{--    <h4 class="page-title">{{$pertanyaan->pedomanPerilaku->nomor_urut." ".$pertanyaan->pedomanPerilaku->pedoman_perilaku}}</h4>--}}
<h5 style="margin-top: 20px;">Komitmen {{$tahun}}</h5>
@stop

@section('content')
    <progress class="progress progress-info progress-xs" value="{{$progress}}" max="100">{{$progress}}%</progress>
    {!! Form::open(['url'=>'commitment/pedoman-perilaku/quiz/answer']) !!}
    {!! Form::hidden('pertanyaan_id', $pertanyaan->id) !!}
    {!! Form::hidden('tahun', $tahun) !!}
    {!! Form::hidden('token', $token) !!}
    <div class="row">
        @if($berita!=null)
        <div class="col-md-8">
        @else
        <div class="col-md-12">
        @endif
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <h3 style="color: #19a2ba;">{{$pertanyaan->pedomanPerilaku->nomor_urut." ".strtoupper($pertanyaan->pedomanPerilaku->pedoman_perilaku)}}</h3>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px; border-top: solid 2px #19a2ba;">
                    <div class="col-md-12">
                        <h4 style="margin-top: 20px;"><i class="fa fa-question-circle-o"></i> Quiz</h4>
                    </div>
                </div>
                <small class="text-muted text-warning"><i class="fa fa-circle"></i> {{($pertanyaan->jenis==1) ? 'Pilihan ganda' : 'Benar / Salah'}}</small>
                <div class="row m-t-20">
                    <div class="col-md-12">
                        <span style="font-size: 21px">{!! $pertanyaan->pertanyaan !!}</span>
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-md-12">
                        <?php $pilihan = 'A'?>
                        @foreach($pertanyaan->jawaban->shuffle() as $jawaban)
                        <div class="radio radio-success">
                            <input type="radio" name="jawaban" id="jawaban_{{$jawaban->index}}" value="{{$jawaban->id}}" onclick="javascript:enableNext()">
                            <label for="jawaban_{{$jawaban->index}}" onclick="javascript:enableNext()" style="font-size: 18px;">
                                <span style="font-size: 20px; font-weight: normal;">{{$pilihan++}}.</span>
                                {{$jawaban->jawaban}}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row m-t-20">
                    <div class="col-md-6">
                        <a href="{{url('commitment/pedoman-perilaku/tahun/'.$tahun)}}" type="button" class="btn btn-primary btn-lg pull-left"><i class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                    <div class="col-md-6">
                        <button id="btn_next" type="submit" class="btn btn-primary btn-lg disabled pull-right" disabled>Next <i class="fa fa-chevron-circle-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
        @if($berita!=null)
            <div class="col-md-4">
                {{--<div class="card">--}}
                {{--<img class="img-fluid" src="{{$berita->image->full}}" alt="{{$berita->title}}">--}}
                {{--<div class="card-block">--}}
                {{--<div class="row">--}}
                {{--<div class="col-md-2">--}}
                {{--<img class="img-thumbnail" src="{{asset('assets/images/logo_pln2.png')}}" >--}}
                {{--</div>--}}
                {{--<div class="col-md-10">--}}
                {{--<a href="{{$berita->url}}" target="_blank"><h4>{{$berita->title}}</h4></a>--}}
                {{--<p class="card-text">--}}
                {{--<small class="text-muted">{{$berita->date}} - www.pln.co.id</small>--}}
                {{--</p>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<p class="card-text">{!!  $berita->excerpt  !!} <a href="{{$berita->url}}" class="card-link" target="_blank">Read More</a></p>--}}
                {{--</div>--}}
                {{--</div>--}}

                <div class="card">
                    <img class="img-fluid" src="{{@$berita->image->full}}" alt="{{@$berita->title}}">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-xs-2">
                                <img class="img-thumbnail" src="{{asset('assets/images/logo_pln3.png')}}" >
                            </div>
                            <div class="col-xs-10">
                                <a href="{{@$berita->url}}" target="_blank"><h4>{{@$berita->title}}</h4></a>
                                <p class="card-text">
                                    <small class="text-muted">{{@$berita->date}} - www.pln.co.id</small>
                                </p>
                            </div>
                        </div>
                        <p class="card-text">{!!  @$berita->excerpt  !!} <a href="{{@$berita->url}}" class="card-link" target="_blank">Read More</a></p>
                    </div>
                </div>

            </div>
        @endif


    </div>
    {!! Form::close() !!}
@stop

@section('javascript')
    <script>
        function enableNext(){
            $('#btn_next').removeClass('disabled');
            $('#btn_next').prop("disabled", false);
        }
    </script>

@stop

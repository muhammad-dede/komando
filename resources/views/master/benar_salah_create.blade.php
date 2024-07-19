@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Pertanyaan Benar Salah</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'master-data/pertanyaan/benar-salah','class'=>'form_control']) !!}
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <fieldset class="form-group">
                    <label for="pedoman_perilaku_id">Pedoman Perilaku</label>
                    @if(!isset($pedoman))
                        {!! Form::select('pedoman_perilaku_id', $pedoman_list, null,
                                    ['class'=>'form-control',
                                    'id'=>'pedoman_perilaku_id']) !!}
                    @else
                        {!! Form::text('pedoman', $pedoman->nomor_urut.' '.$pedoman->pedoman_perilaku, ['class'=>'form-control','disabled']) !!}
                        {!! Form::hidden('pedoman_perilaku_id', $pedoman->id) !!}
                        {!! Form::hidden('redirect', 'master-data/pedoman-perilaku/'.$pedoman->id.'/display') !!}
                    @endif
                    {{--<small class="text-muted">Pilih kategori pedoman perilaku--}}
                    {{--</small>--}}
                </fieldset>

                <fieldset class="form-group">
                    <label for="pertanyaan">Pertanyaan</label>
                    <textarea class="form-control" id="pertanyaan" name="pertanyaan" rows="2"></textarea>
                </fieldset>
                <div class="row">
                    <div class="col-md-10">
                        <label>Jawaban</label>
                    </div>
                    <div class="col-md-2">
                        <label></label>
                    </div>
                </div>
                <?php $pilihan = 'A'?>
                {{--@for($x=1;$x<=4;$x++)--}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row" id="row_jawaban_1">
                            <label for="jawaban_a" class="col-sm-1 form-control-label">
                                <h3 class="font-weight-bold pull-right" id="label_pilihan_1">{{$pilihan++}}</h3>
                            </label>

                            <div class="col-sm-2">
                                {!! Form::text('jawaban_1','Benar',['class'=>'form-control', 'id'=>'jawaban_1', 'readonly']) !!}
                            </div>

                            <div class="col-md-2 radio radio-success" id="radio_1">
                                <input type="radio" name="benar" id="benar_1" value="1"
                                       onclick="javascript:pilihBenar('1')">
                                <label for="benar_1" onclick="javascript:pilihBenar('1')">
                                    Benar
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row" id="row_jawaban_2">
                            <label for="jawaban_b" class="col-sm-1 form-control-label">
                                <h3 class="font-weight-bold pull-right" id="label_pilihan_2">{{$pilihan++}}</h3>
                            </label>

                            <div class="col-sm-2">
                                {!! Form::text('jawaban_2','Salah',['class'=>'form-control', 'id'=>'jawaban_2', 'readonly']) !!}
                            </div>

                            <div class="col-md-2 radio radio-success" id="radio_2">
                                <input type="radio" name="benar" id="benar_2" value="1"
                                       onclick="javascript:pilihBenar('2')">
                                <label for="benar_2" onclick="javascript:pilihBenar('2')">
                                    Salah
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                {{--@endfor--}}
                <div class="row">
                    <div class="col-md-12 pull-right">
                        <div class="button-list">
                            <button type="button" class="btn btn-warning btn-lg pull-right"
                                    onclick="window.location.href='{{(!isset($pedoman)) ? url('master-data/pertanyaan') : url('master-data/pedoman-perilaku/'.$pedoman->id.'/display')}}';">
                                <i
                                        class="fa fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg pull-right"><i class="fa fa-save"></i>
                                Save
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>

    <script>
        $(document).ready(function () {
            $("#pedoman_perilaku_id").select2();
        });

        function pilihBenar(id) {
            if ($('#radio_' + id).filter(':checked')) {
                $('#row_jawaban_' + id).addClass('has-success');
                $('#label_pilihan_' + id).addClass('text-success');
                $('#jawaban_' + id).addClass('form-control-success');
                for (var x = 1; x <= 4; x++) {
                    if (x != id) {
                        $('#row_jawaban_' + x).removeClass('has-success');
                        $('#label_pilihan_' + x).removeClass('text-success');
                        $('#jawaban_' + x).removeClass('form-control-success');
                    }
                }
            }
        }
    </script>
@stop
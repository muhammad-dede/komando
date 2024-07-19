@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">Pertanyaan</h4>
@stop

@section('content')

    <div class="row m-b-30">
        <div class="col-xl-12">
            <div class="button-list">
                <a href="{{url('master-data/pertanyaan/pilihan-ganda')}}"
                   class="btn btn-warning-outline btn-rounded waves-effect waves-light">
                        <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span>
                    Pilihan Ganda
                </a>
                <a href="{{url('master-data/pertanyaan/benar-salah')}}"
                   class="btn btn-purple-outline btn-rounded waves-effect waves-light">
                        <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span>
                    Benar / Salah
                </a>
            </div>

        </div>
    </div>
    @foreach($pertanyaan_list as $pertanyaan)
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-block">
                    <p class="card-text">
                        #{{$pertanyaan->id.' Pertanyaan : '.$pertanyaan->pedomanPerilaku->nomor_urut.' '.$pertanyaan->pedomanPerilaku->pedoman_perilaku}}
                    </p>

                    <h4 class="card-title">
                       {{$pertanyaan->pertanyaan}}
                    </h4>

                    <?php $pilihan = 'A';?>
                    @foreach($pertanyaan->jawaban as $jawaban)
                    <div class="row">
                        <div class="col-md-12">
                            <span class="{{($jawaban->benar==1)? 'font-weight-bold text-success' : ''}}">{{$pilihan++}}. {{$jawaban->jawaban}}</span>
                        </div>
                    </div>
                    @endforeach
                    <div class="row m-t-20">
                        <div class="col-md-12 button-list">
                            <a href="#" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit</a>
                            <a href="#" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@stop

@section('javascript')

@stop
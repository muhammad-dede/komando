@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">{{$pedoman->nomor_urut.' '.$pedoman->pedoman_perilaku}}</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                {{--<h4 class="header-title m-t-0">Default Tabs</h4>--}}

                {{--<p class="text-muted m-b-20 font-13">--}}
                {{--Takes the basic nav from above and adds the .nav-tabs class to generate a tabbed interface.--}}
                {{--</p>--}}

                <ul class="nav nav-tabs m-b-10" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                           role="tab" aria-controls="home" aria-expanded="true"><h5>Pedoman Perilaku</h5></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                           role="tab" aria-controls="profile"><h5>Pertanyaan</h5></a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active" id="home"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 20px;">
                                @include(@$pedoman->template)
                            </div>
                            {{--@if($pedoman->tipe==1)--}}
                            {{--<div class="col-sm-6">--}}
                                {{--<div class="card card-block">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-12" align="center">--}}
                                            {{--<img src="{{asset('assets/images/do.png')}}" width="150">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<hr>--}}
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
                                    {{--</ol>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-6" id="box_dont">--}}
                                {{--<div class="card card-block">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-12" align="center">--}}
                                            {{--<img src="{{asset('assets/images/dont.png')}}" width="150">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
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
                                {{--</div>--}}

                            {{--</div>--}}
                            {{--@else--}}
                            {{--<div class="col-md-12 p-20">--}}
                                {{--{!! $pedoman->perilaku()->first()->perilaku !!}--}}
                            {{--</div>--}}
                            {{--@endif--}}
                        </div>

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel"
                         aria-labelledby="profile-tab">
                        <div class="row m-b-20">
                            <div class="col-xl-12">
                                <div class="button-list">
                                    <a href="{{url('master-data/pertanyaan/pilihan-ganda/'.$pedoman->id)}}"
                                       class="btn btn-warning-outline btn-rounded waves-effect waves-light">
                        <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span>
                                        Pilihan Ganda
                                    </a>
                                    <a href="{{url('master-data/pertanyaan/benar-salah/'.$pedoman->id)}}"
                                       class="btn btn-purple-outline btn-rounded waves-effect waves-light">
                        <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span>
                                        Benar / Salah
                                    </a>
                                </div>

                            </div>
                        </div>
                        @if($pedoman->pertanyaan()->where('status','ACTV')->count()==0)
                            <div class="text-muted m-b-30" align="center">Belum ada pertanyaan.</div>
                        @endif
                        @foreach($pedoman->pertanyaan()->where('status','ACTV')->orderBy('id', 'desc')->get() as $pertanyaan)
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card card-block">
                                        <p class="card-title">
                                            #{{$pertanyaan->id}}
                                        </p>

                                        <h4 class="card-title">
                                            {!!$pertanyaan->pertanyaan!!}
                                        </h4>

                                        <?php $pilihan = 'A';?>
                                        @foreach($pertanyaan->jawaban as $jawaban)
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="{{($jawaban->benar==1)? 'font-weight-bold text-success' : ''}}">{{$pilihan++}}
                                                        . {{$jawaban->jawaban}} {!! ($jawaban->benar==1)? '<i class="fa fa-check"></i>' : '' !!}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="row m-t-20">
                                            <div class="col-md-12 button-list">

                                                {!! Form::open(['url'=>'master-data/pertanyaan/delete', 'id'=>'form_delete_'.$pertanyaan->id]) !!}
                                                {!! Form::hidden('redirect', 'master-data/pedoman-perilaku/'.$pedoman->id.'/display') !!}
                                                {!! Form::hidden('pertanyaan_id', $pertanyaan->id) !!}
                                                @if($pertanyaan->jenis=='1')
                                                <a href="{{url('master-data/pertanyaan/pilihan-ganda/'.$pertanyaan->id.'/edit')}}" class="btn btn-warning"><i class="fa fa-pencil"></i>
                                                    Edit</a>
                                                @else
                                                    <a href="{{url('master-data/pertanyaan/benar-salah/'.$pertanyaan->id.'/edit')}}" class="btn btn-warning"><i class="fa fa-pencil"></i>
                                                        Edit</a>
                                                @endif
                                                <button type="button" class="btn btn-danger" onclick="return confirmDelete('{{$pertanyaan->id}}')"><i class="fa fa-trash"></i>
                                                    Delete</button>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="row" id="btn_next">
                    <div class="col-md-12">
                        <a href="{{url('master-data/pedoman-perilaku')}}" class="btn btn-primary btn-lg"><i
                                    class="fa fa-chevron-circle-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
    </div>
    </div>
@stop

@section('javascript')
    <script>
        $( window ).load(function() {


        });

        function confirmDelete(id){
            swal({
                title: "Anda yakin ingin menghapus?",
                text: "Klik tombol Yes untuk menghapus",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonClass: 'btn-primary waves-effect waves-light',
                confirmButtonText: 'Yes',
//                closeOnConfirm: false,
            }, function (isConfirm) {
                if (isConfirm) {
//                    alert(isConfirm);
//                    alert(id);
                    $('#form_delete_'+id).submit();
//                    swal("Terimakasih!", "Anda telah melakukan komitmen 2017.", "success");
                    {{--window.location.href= '{{url('commitment/pedoman-perilaku')}}'--}}
                }
//                else {
//                    swal("Cancelled", "Your imaginary file is safe :)", "error");
//                }
            });
        }
    </script>
@stop

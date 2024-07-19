@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">Buku Pedoman Perilaku</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                {{--<h4 class="m-t-0" style="color:#19a2ba;">{{$pedoman->nomor_urut.' '.strtoupper($pedoman->pedoman_perilaku)}}</h4>--}}
                {{--<hr>--}}
                        @include(@$pedoman->template)
                {{--<p class="text-muted m-b-20 font-13">--}}
                {{--Takes the basic nav from above and adds the .nav-tabs class to generate a tabbed interface.--}}
                {{--</p>--}}

                {{--<div class="row m-t-20">--}}
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
                {{--</div>--}}

                <div class="row" id="btn_next" style="margin-top: 50px;">
                    <div class="col-md-6">
                        <a href="{{url('commitment/buku/'.$prev)}}" class="btn btn-primary btn-lg"><i
                                    class="fa fa-chevron-circle-left"></i> {{($prev=='')?'Back to Index':'Prev'}}</a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{url('commitment/buku/'.$next)}}" class="btn btn-primary btn-lg pull-right">{{($next=='')?'Finish':'Next'}} <i
                                    class="fa fa-chevron-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

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

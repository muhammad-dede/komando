@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">Notification</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row m-t-20">
                    <div class="card card-block col-md-12">
                        <div class="row">
                            <div class="col-md-1 col-xs-4">
                                <img
                                        src="{{asset('assets/images/maintenance.jpg')}}"
                                        alt="Maintenance"
                                        class="img-thumbnail">

                            </div>
                            <div class="col-md-11 col-xs-8">
                                <p class="card-text">
                                    <b>Maintenance Info</b>
                                    <br>
                                    <br>
                                    <span>
Berhubung terjadinya gangguan server pada hari Senin tanggal 4 Desember 2017 sehingga menyebabkan banyak unit yang gagal membuat jadwal CoC Nasional dan banyak pegawai yang belum berhasil melakukan check-in CoC Nasional, maka untuk sementara CoC Nasional akan kami buka sampai proses perbaikan server selesai.
<br>
<br>
Administrator masih dapat membuat jadwal CoC dengan materi nasional dan pegawai yang belum berhasil check-in CoC Nasional dapat melakukan check-in di hari lainnya.
<br>
<br>
                                        Jika ada pertanyaan mengenai informasi ini, silakan menghubungi administrator Komando di unit masing-masing.
                                        <br>
                                        <br>
Demikian pemberitahuan ini kami sampaikan. Mohon maaf atas ketidaknyamannya. Atas perhatiannya kami ucapkan terimakasih.
                                    </span>
                                </p>

                                <p class="card-text">
                                    <small class="text-muted">Posted by
                                        Administrator Kantor Pusat
                                        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                                        {{--{{$comment->created_at->diffForHumans()}}--}}
                                        4 Desember 2017
                                    </small>
                                </p>
                                {{--<hr>--}}
                                {{--<div class="button-list">--}}
                                    {{--<button class="btn waves-effect waves-light btn-pink-outline btn-sm"><i--}}
                                                {{--class="fa fa-thumbs-up"></i></button>--}}
                                    {{--<button class="btn waves-effect waves-light btn-success-outline btn-sm">--}}
                                        {{--<i--}}
                                                {{--class="fa fa-comment"></i></button>--}}
                                    {{--<button class="btn waves-effect waves-light btn-primary-outline btn-sm">--}}
                                    {{--<i--}}
                                    {{--class="fa  fa-share-alt"></i></button>--}}
                                {{--</div>--}}
                            </div>
                        </div>

                    </div>
                </div>


                {{--<div class="row m-t-20">--}}
                    {{--<div class="col-md-6">--}}
                        {{--<a href="{{url('coc')}}" type="button" class="btn btn-primary btn-lg pull-left">--}}
                            {{--<i class="fa fa-chevron-circle-left"></i> Back</a>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-6">--}}
                        {{--<button id="btn_next" type="submit" class="btn btn-primary btn-lg disabled pull-right" disabled>--}}
                            {{--Next <i class="fa fa-chevron-circle-right"></i>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
@stop

@section('javascript')

@stop
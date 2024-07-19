@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">Komitmen Direksi dan Dewan Komisaris {{$tahun}}</h4>
@stop

@section('content')
    <progress class="progress progress-info progress-xs" value="100" max="100">100%</progress>
    {!! Form::open(['url'=>'commitment/direksi-komisaris']) !!}
    {!! Form::hidden('tahun',$tahun) !!}
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <section>
                    <div class="row">
                        <div class="col-lg-12 text-xl-center">
                            <h4 style="color: #005973; margin-bottom:10px;"><b>PERNYATAAN KOMITMEN DIREKSI DAN DEWAN KOMISARIS</b></h4>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">

                            <p class="card-text" style="text-align: justify">
                                Pedoman Perilaku dan Etika Bisnis merupakan panduan untuk berperilaku sesuai dengan harapan  Perusahaan, 
                                sehingga pada akhirnya akan tertanam dan menjadi perilaku khas yang membedakan  PT  PLN (Persero) dengan perusahaan-perusahaan lainnya. 
                                Perusahaan percaya bahwa dengan berperilaku sesuai Core Values AKHLAK (Amanah, Kompeten, Harmonis, Loyal, Adaptif, dan Kolaboratif) 
                                akan semakin memantapkan langkah untuk mencapai visi, karena menjadi pedoman nyata bagi seluruh PLNers dalam penampilan, sikap dan perilaku sehari-hari.
                            </p>

                            <p class="card-text" style="text-align: justify">
                                Kami mewakili PT PLN (Persero) menyatakan bertekad menjadi role model penerapan prinsip-prinsip yang tercantum dalam 
                                Pedoman Perilaku dan Etika Bisnis secara konsisten dalam menjalankan amanah sebagai Dewan Komisaris, Direksi, maupun sebagai pegawai PT PLN (Persero)
                            </p>

                            <div class="row" style="margin-top: 30px;">
                                <div class="col-xs-8 col-md-4 col-lg-4">
                                    <div>
                                        <img src="{{(Auth::user()->foto!='') ? url('user/foto') : asset('assets/images/user.jpg')}}"
                                                {{--<img src="{{asset('assets/images/user.jpg')}}"--}}
                                             class="img-responsive img-rounded img-thumbnail">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-lg-8 col-md-8">
                                    <fieldset class="form-group">
                                        <label for="exampleInputEmail1">Nama</label>
                                        <br>
                                        {{Auth::user()->name}}
                                    </fieldset>
                                    @if(Auth::user()->hasRole('komisaris'))
                                    <fieldset class="form-group">
                                        <label for="exampleInputEmail1">Jabatan</label>
                                        <br>
                                        Dewan Komisaris
                                    </fieldset>
                                    @else
                                    <fieldset class="form-group">
                                        <label for="exampleInputEmail1">NIP</label>
                                        <br>
                                        {{Auth::user()->nip}}
                                    </fieldset>
                                    <fieldset class="form-group">
                                        <label for="exampleInputEmail1">Unit</label>
                                        <br>
                                        {{--{{Auth::user()->ad_company}}--}}
                                        {{@Auth::user()->businessArea->description}}
                                    </fieldset>
                                    <fieldset class="form-group">
                                        <label for="exampleInputEmail1">Jabatan</label>
                                        <br>
                                        {{--{{Auth::user()->ad_title}}--}}
                                        {{@Auth::user()->strukturPosisi()->stext}}
                                    </fieldset>
                                    @endif

                                </div>


                            </div>
                            <div class="form-group clearfix row pull-right">
                                <div class="checkbox checkbox-primary">
                                    <input id="checkbox_setuju" type="checkbox" name="setuju" value="1">
                                    <label for="checkbox_setuju">
                                        Saya setuju dan berkomitmen.
                                    </label>
                                </div>

                            </div>

                        </div>
                        <div class="col-lg-3"></div>
                    </div>
                </section>
                <div class="row m-t-20">
                    <div class="col-md-6">
                        {{--<a href="{{url('commitment/pedoman-perilaku')}}" type="button"--}}
                           {{--class="btn btn-primary btn-lg pull-left"><i class="fa fa-chevron-circle-left"></i> Back</a>--}}
                    </div>
                    <div class="col-md-6">
                        <button id="btn_next" type="submit" class="btn btn-primary btn-lg disabled pull-right" disabled>Komitmen <i
                                    class="fa fa-check-circle"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('javascript')
    <script>
        $('#checkbox_setuju').click(function(){
            if ($('#checkbox_setuju').is(':checked')){
                $('#btn_next').removeClass('disabled');
                $('#btn_next').prop("disabled", false);
            }
            else{
                $('#btn_next').addClass('disabled');
                $('#btn_next').prop("disabled", true);
            }
        });
    </script>

@stop

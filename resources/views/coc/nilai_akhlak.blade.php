@extends('layout')

@section('css')
    <style>
        #progress_values{
            width: 100%;
            background-color: #ddd;
        }

        #bar_values{
            width: 1%;
            height: 5px;
            background-color: #4CAF50;
        }
    </style>
@stop

@section('title')
    {{--  <h4 class="page-title">Values (Nilai)</h4>  --}}
@stop

@section('content')
    <progress class="progress progress-info progress-xs m-t-10" value="32" max="100">32%</progress>
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-md-12 ">
                        <h4 class="card-title">Values (Nilai) &nbsp;&nbsp;<span><img src="{{asset('assets/images/akhlak2.png')}}" width="150" class="img-responsive"></span></h4>
                    </div>
                </div>

                <div class="row m-t-20">
                    <div class="col-md-12 col-xs-12  hidden-sm-down">
                        <div class="card card-block">
                            <div align="center">
                                <img src="{{asset('assets/images/nilai_akhlak.png')}}" align="center">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-12 hidden-sm-up">
                        <div class="card card-block">
                            <div align="center">
                                <img src="{{asset('assets/images/nilai_akhlak_mobile.png')}}" align="center" style="width: 250px;" class="img-responsive">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="m-b-20">
                    <div id="progress_values" class="m-t-30">
                        <div id="bar_values"></div>
                    </div>
                    <div class="checkbox checkbox-primary m-t-30" id="check_values" style="display: none;">
                        <input id="checkbox_do" name="checkbox_do" type="checkbox" value="1">
                        <label for="checkbox_do">
                            Saya sudah membaca dan memahami
                        </label>
                    </div>
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
                {!! Form::open(['url'=>'coc/tata-nilai/'.$coc->id]) !!}
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
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(window).load(function(){
            setTimeout(function(){ move_values(); }, 500);
        });

       $('#checkbox_do').click(function () {
//            if ($('#checkbox_do').is(':checked')) {
                $('#btn_next').fadeToggle();
//            }
        });

        function move_values() {
            var i = 0;
            if (i == 0) {
                i = 1;
                var elem = document.getElementById('bar_values');
                var width = 1;
                var id = setInterval(frame, 40);
                function frame() {
                    if (width >= 100) {
                        clearInterval(id);
                        i = 0;
                        document.getElementById("check_values").style.display = 'block';
                        document.getElementById("bar_values").style.display = 'none';
                    } else {
                        width++;
                        elem.style.width = width + "%";
                    }
                }
            }
        }

    </script>
@stop
@extends('layout')

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    {{--<link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>--}}


@stop

@section('title')
    <h4 class="page-title">Jadwal Liquid</h4>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <table>
                            <tr>
                                <td>
                                    <span class="display-1">17</span>
                                </td>
                                <td style="padding-left: 10px;">
                                    <span style="font-size: 24px">Juni</span><br>
                                    <span style="font-size: 24px" class="text-muted">Rabu</span>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <h4 class="col-md-6 card-title lh-70">{{@Auth::user()->getOrgLevel()->stext}}</h4>
                    <div class="col-md-6 align-right lh-70">
                        <a href="" class="btn btn-warning"><em class="fa fa-pencil"></em> Edit</a>
                    </div>
                </div>
                <a href="" class="btn bg-success color-white btn-block m-b-10">Pelaksanaan Feedback</a>
                <a href="" class="btn bg-red color-white btn-block m-b-10">Pelaksanaan Penyelarasan</a>
                <a href="" class="btn bg-purple color-white btn-block m-b-10">Pengukuran Pertama</a>
                <a href="" class="btn bg-orange color-white colorpicker btn-block m-b-10">Pengukuran Kedua</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card-box">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
    <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/clockpicker/bootstrap-clockpicker.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#calendar').fullCalendar({
                "header": {
                    "left": "prev,next today",
                    "center": "title",
                    "right": "month,agendaWeek,agendaDay"
                },
                "eventLimit": false,
                "firstDay": 1,
                "events": [
                    {
                        "id": 62,
                        "title": "Pelaksaan Feedback",
                        "allDay": 1,
                        "start": "2020-06-01T00:00:00+07:00",
                        "end": "2020-06-08T00:00:00+07:00",
                        "url": "http://localhost:8800/create-liquid",
                        "className": "bg-success"
                    },
                    {
                        "id": 81,
                        "title": "Pelaksanaan Penyelarasan",
                        "allDay": 1,
                        "start": "2020-06-08T00:00:00+07:00",
                        "end": "2020-06-12T00:00:00+07:00",
                        "url": "http://localhost:8800/create-liquid",
                        "className": "bg-red"
                    },
                    {
                        "id": 82,
                        "title": "Pengukuran Pertama",
                        "allDay": 1,
                        "start": "2020-06-18T00:00:00+07:00",
                        "end": "2020-06-22T00:00:00+07:00",
                        "url": "http://localhost:8800/create-liquid",
                        "className": "bg-purple"
                    },
                    {
                        "id": 83,
                        "title": "Pengukuran Kedua",
                        "allDay": 1,
                        "start": "2020-06-25T00:00:00+07:00",
                        "end": "2020-06-28T00:00:00+07:00",
                        "url": "http://localhost:8800/create-liquid",
                        "className": "bg-orange"
                    },
                ]
            });
        });
    </script>

@stop

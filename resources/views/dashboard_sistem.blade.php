@extends('layout')

@section('css')
    <link rel="stylesheet" href="{{url('/assets/css/leaflet.css')}}"/>
    {{--<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"--}}
    {{--integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="--}}
    {{--crossorigin=""/>--}}
    <style>
        #mapid {
            height: 450px;
        }
    </style>
@stop

@section('title')
    <div class="row">
        <div class="col-sm-7">
            <h1 class="mainTitle">{{$sistem_besar->sistem}}</h1>
            <span class="mainDescription">overview &amp; stats </span>
        </div>
        <div class="col-sm-5">
            <!-- start: MINI STATS WITH SPARKLINE -->
            <ul class="mini-stats pull-right">
                <li>
                    <div class="sparkline-2">
                        <span></span>
                    </div>
                    <div class="values">
                        <strong class="text-dark">833</strong>

                        <p class="text-small no-margin">
                            Normal
                        </p>
                    </div>
                </li>
                <li>
                    <div class="sparkline-3">
                        <span></span>
                    </div>
                    <div class="values">
                        <strong class="text-dark">848</strong>

                        <p class="text-small no-margin">
                            Siaga
                        </p>
                    </div>
                </li>
                <li>
                    <div class="sparkline-1">
                        <span></span>
                    </div>
                    <div class="values">
                        <strong class="text-dark">104</strong>

                        <p class="text-small no-margin">
                            Defisit
                        </p>
                    </div>
                </li>
            </ul>
            <!-- end: MINI STATS WITH SPARKLINE -->
        </div>
    </div>
    @stop

    @section('content')
            <!-- start: MAP SECTION -->

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="panel-group accordion" id="accordion">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h5 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                               href="#collapseOne">
                                <i class="icon-arrow"></i>
                                <b>Kondisi Kelistrikan Sistem Besar Saat Beban Puncak Tertinggi</b>
                                (Berdasarkan Daya Mampu Pasok - Januari 2017)
                            </a></h5>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="col-sm-12" id="mapid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row">--}}
        {{--<div class="col-sm-12" id="mapid"></div>--}}
        {{--</div>--}}
    </div>
    <!-- end: MAP SECTION -->
    <!-- start: BP & STATUS OPERASI -->
    <div class="container-fluid container-fullw padding-bottom-10">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-7 col-lg-8">
                        <div class="panel panel-white no-radius" id="visits">
                            <div class="panel-heading border-light">
                                <h4 class="panel-title"> Beban Puncak </h4>
                                <ul class="panel-heading-tabs border-light">
                                    <li>
                                        <div class="pull-right">
                                            <div class="btn-group">
                                                <a class="padding-10" data-toggle="dropdown">
                                                    <i class="ti-more-alt "></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-light" role="menu">
                                                    <li>
                                                        <a href="#">
                                                            Action
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            Another action
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            Something else here
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="rate">
                                            <i class="fa fa-caret-up text-primary"></i><span
                                                    class="value">15</span><span class="percentage">%</span>
                                        </div>
                                    </li>
                                    <li class="panel-tools">
                                        <a data-original-title="Refresh" data-toggle="tooltip" data-placement="top"
                                           class="btn btn-transparent btn-sm panel-refresh" href="#"><i
                                                    class="ti-reload"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <div collapse="visits" class="panel-wrapper">
                                <div class="panel-body">
                                    <div class="height-350">
                                        <canvas id="chart1" class="full-width"></canvas>
                                        <div class="margin-top-20">
                                            <div class="inline pull-left">
                                                <div id="chart1Legend" class="chart-legend"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4">
                        <div class="panel panel-white no-radius">
                            <div class="panel-heading border-light">
                                <h4 class="panel-title"> Status Operasi</h4>
                            </div>
                            <div class="panel-body">
                                <h3 class="inline-block no-margin">26</h3> sistem normal
                                <div class="progress progress-xs no-radius">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                                        <span class="sr-only"> 40% Complete</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h4 class="no-margin">7</h4>

                                        <div class="progress progress-xs no-radius no-margin">
                                            <div class="progress-bar progress-bar-info" role="progressbar"
                                                 aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                 style="width: 60%;">
                                                <span class="sr-only"> 60% Complete</span>
                                            </div>
                                        </div>
                                        Normal
                                    </div>
                                    <div class="col-sm-4">
                                        <h4 class="no-margin">4</h4>

                                        <div class="progress progress-xs no-radius no-margin">
                                            <div class="progress-bar progress-bar-warning" role="progressbar"
                                                 aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                                 style="width: 40%;">
                                                <span class="sr-only"> 40% Complete</span>
                                            </div>
                                        </div>
                                        Siaga
                                    </div>
                                    <div class="col-sm-4">
                                        <h4 class="no-margin">15</h4>

                                        <div class="progress progress-xs no-radius no-margin">
                                            <div class="progress-bar progress-bar-danger" role="progressbar"
                                                 aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"
                                                 style="width: 80%;">
                                                <span class="sr-only"> 80% Complete</span>
                                            </div>
                                        </div>
                                        Defisit
                                    </div>
                                </div>
                                <div class="row margin-top-30">
                                    <div class="col-xs-4 text-center">
                                        <div class="rate">
                                            <i class="fa fa-caret-up text-green"></i><span class="value">26</span><span
                                                    class="percentage">%</span>
                                        </div>
                                        Normal
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <div class="rate">
                                            <i class="fa fa-caret-up text-green"></i><span class="value">62</span><span
                                                    class="percentage">%</span>
                                        </div>
                                        Siaga
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <div class="rate">
                                            <i class="fa fa-caret-down text-red"></i><span class="value">12</span><span
                                                    class="percentage">%</span>
                                        </div>
                                        Defisit
                                    </div>
                                </div>
                                <div class="margin-top-10">
                                    <div class="height-180">
                                        <canvas id="chart2" class="full-width"></canvas>
                                        <div class="inline pull-left legend-xs">
                                            <div id="chart2Legend" class="chart-legend"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: BP & STATUS OPERASI -->
    <!-- start: PEMBANGKIT  -->
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-white no-radius">
                    <div class="panel-heading border-bottom">
                        <h4 class="panel-title">Outage Pembangkit</h4>
                    </div>
                    <div class="panel-body">
                        <div class="text-center">
                            <canvas id="chart6" class="full-width"></canvas>
                        </div>
                        <div class="margin-top-20 text-center legend-xs inline">
                            <div id="chart6Legend" class="chart-legend"></div>
                        </div>
                    </div>
                    {{--<div class="panel-footer">--}}
                    {{--<div class="clearfix padding-5 space5">--}}
                    {{--<div class="col-xs-4 text-center no-padding">--}}
                    {{--<div class="border-right border-dark">--}}
                    {{--<span class="text-bold block text-extra-large">90%</span>--}}
                    {{--<span class="text-light">Satisfied</span>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-4 text-center no-padding">--}}
                    {{--<div class="border-right border-dark">--}}
                    {{--<span class="text-bold block text-extra-large">2%</span>--}}
                    {{--<span class="text-light">Unsatisfied</span>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-4 text-center no-padding">--}}
                    {{--<span class="text-bold block text-extra-large">8%</span>--}}
                    {{--<span class="text-light">NA</span>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-white no-radius">
                    <div class="panel-heading border-bottom">
                        <h4 class="panel-title">Kesiapan Pembangkit</h4>
                    </div>
                    <div class="panel-body">
                        <div class="text-center">
                            <canvas id="chart7" class="full-width"></canvas>
                        </div>
                        <div class="margin-top-20 text-center legend-xs inline">
                            <div id="chart7Legend" class="chart-legend"></div>
                        </div>
                    </div>
                    {{--<div class="panel-footer">--}}
                    {{--<div class="clearfix padding-5 space5">--}}
                    {{--<div class="col-xs-4 text-center no-padding">--}}
                    {{--<div class="border-right border-dark">--}}
                    {{--<span class="text-bold block text-extra-large">90%</span>--}}
                    {{--<span class="text-light">Satisfied</span>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-4 text-center no-padding">--}}
                    {{--<div class="border-right border-dark">--}}
                    {{--<span class="text-bold block text-extra-large">2%</span>--}}
                    {{--<span class="text-light">Unsatisfied</span>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-4 text-center no-padding">--}}
                    {{--<span class="text-bold block text-extra-large">8%</span>--}}
                    {{--<span class="text-light">NA</span>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
    </div>
    <!-- end: PEMBANGKIT -->

@stop

@section('javascript')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{asset('assets/js/leaflet.js')}}"></script>
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
    {{--<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"--}}
    {{--integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="--}}
    {{--crossorigin=""></script>--}}

    <script>
//        google.charts.load('current', {'packages':['gauge']});
        google.charts.load('current', {
            callback: initPage,
            packages: ['gauge']
        });

        jQuery(document).ready(function () {
            Dashboard.init();
        });

        function initPage() {
            //        MAP SISTEM BESAR =============================================================================================

            var mymap = L.map('mapid').setView([{{$sistem_besar->lat}}, {{$sistem_besar->lng}}], 7);

            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                maxZoom: 18,
                scrollWheelZoom: false,
                id: 'mapbox.streets'
            }).addTo(mymap);

            var MarkerIcon = L.Icon.extend({
                options: {
                    {{--shadowUrl: '{{url('/assets/images')}}/leaf-shadow.png',--}}
                iconSize: [24, 24],
//                    iconSize: [32, 32],
//                shadowSize: [50, 64],
                iconAnchor: [13, 25],
//                    iconAnchor: [15, 30],
//                shadowAnchor: [4, 62],
                    popupAnchor: [0, -20]
                }
            });

            var normal = new MarkerIcon({iconUrl: '{{url('/assets/images')}}/marker-green2.png'});
            var siaga = new MarkerIcon({iconUrl: '{{url('/assets/images')}}/marker-yellow2.png'});
            var defisit = new MarkerIcon({iconUrl: '{{url('/assets/images')}}/marker-red2.png'});
            {{--var blank   = new MarkerIcon({iconUrl: '{{url('/assets/images')}}/marker-blank.png'});--}}

            {{--// create popup contents--}}
            {{--var customPopup = "Mozilla Toronto Offices<br/>" +--}}
                    {{--"<a href='{{url('/profile')}}'><img src='http://joshuafrazier.info/images/maptime.gif' alt='maptime logo gif' width='350px'/></a>";--}}

            {{--// specify popup options--}}
            {{--var customOptions =--}}
            {{--{--}}
                {{--'maxWidth': '500',--}}
                {{--'className': 'custom'--}}
            {{--}--}}

            @foreach($sistem_besar->sistems as $wa)

                L.marker([{{$wa->lat}}, {{$wa->lng}}], {icon: normal})
                    .bindPopup(popupWindow('{{$wa->id}}', '{{$wa->sistem}}', 2201, 1847, 1216, 631), {
                        maxWidth: 350
                    })
                    .bindTooltip('{{$wa->sistem}}', {direction: 'bottom'})
                    .on('click', function(){
                        drawChart('{{$wa->id}}', 2201, 1847, 1216);
                    })
                    .addTo(mymap);
            {{--L.marker([{{$wa->lat}}, {{$wa->lng}}], {icon: normal}).bindPopup(customPopup,customOptions).addTo(mymap);--}}

                mymap.on('popupopen', function () {
//                    drawChart();
                    $('#marker_{{$wa->id}}').click(function () {
                        {{--alert('Got to {{url('dashboard/sistem/'.$wa->id)}}');--}}
                    });
                });

            @endforeach

            {{--mymap.on('popupopen', function () {--}}
                {{--@foreach($sistem_besar as $wa)--}}
                {{--drawChart('{{$wa->id}}');--}}
                {{--$('#marker_{{$wa->id}}').click(function () {--}}
                    {{--alert('Got to {{url('dashboard/sistem/'.$wa->id)}}');--}}
                {{--});--}}
                {{--@endforeach--}}
            {{--});--}}


//            var popup = L.popup()
//                    .setLatLng([3.327360, 117.578505])
//                    .setContent('<div id="curve_chart" style="width: 400px; height: 200px"></div>')
//                    .openOn(mymap);

            mymap.scrollWheelZoom.disable();
        }

        function popupWindow(id, nama_sistem, dmn, dmp, bp, cad) {
            var html;
            var color;
            var status;

            if(cad > (dmp*0.3)) {
                status = "NORMAL";
                color = "#b3e551";
            }
            else if(cad < 0){
                status = "DEFISIT";
                color = "#d92971";
            }
            else if(cad < (dmp*0.3)){
                status = "SIAGA";
                color = "#eac13a";
            }

            html = "<div class='panel panel-white' style='width: 350px;'>" +
                    "<div class='panel-heading'>" +
                    "<h4 class='panel-title'><i class='fa fa-circle' style='color:" + color + "'></i> <b>" + nama_sistem + "</b></h4>" +
                    "</div>" +
                    "<div class='panel-body'>" +
                    "<div class='row'>" +
                    "<div class='col-md-7'>" +
                    "<div id='google_chart_"+id+"' align='center'></div>" +
                    "</div>" +
                    "<div class='col-md-5'>" +
                    "<fieldset style='margin: 0px -10px -10px -10px;padding: -10px;'>" +
                    "<legend>" +
                    "Kondisi" +
                    "</legend>" +
                    "<div class='row' style='margin-top: -10px;margin-bottom: -10px;'>" +
                    "<table cellpadding='0' cellspacing='0' border='0' width='100%'>" +
                    "<tr>" +
                    "<td>DMN</td>" +
                    "<td align='right'>"+dmn.format()+" MW</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td>DMP</td>" +
                    "<td align='right'>"+dmp.format()+" MW</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td>BP</td>" +
                    "<td align='right'>"+bp.format()+" MW</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td>Cad</td>" +
                    "<td align='right'>"+cad.format()+" MW</td>" +
                    "</tr>" +
                    "</table>" +
                    "</div>" +
                    "</fieldset>" +
                    "<fieldset style='margin:30px -10px -10px -10px;padding: -10px;'>" +
                    "<legend>" +
                    "Status" +
                    "</legend>" +
                    "<div class='row center' style='margin-top: -10px;margin-bottom: -10px; color: " + color + "; font-weight:bold; font-size:16px; letter-spacing:1px;'>" +
                    status +
                    "</div>" +
                    "</fieldset>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "<div class='panel-footer' style='margin-top: -10px;'>" +
                    "<div class='col-md-12' align='right'><a href='#' id='marker_" + id + "'>More detail <i class='fa fa-chevron-right' style='font-size: 10px;'></i></a></div>" +
                    "</div>" +
                    "</div>";

            return html;
        }

        function drawChart(id, dmn, dmp, bp) {

            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['BP', bp]
            ]);

            var options = {
                width: 400, height: 160,
                max : dmn,
                redFrom: dmp, redTo: dmn,
                yellowFrom: dmp - (dmp*0.3), yellowTo: dmp,
                minorTicks: 5
            };

            var chart = new google.visualization.Gauge(document.getElementById('google_chart_'+id));

            chart.draw(data, options);

//            setInterval(function() {
//                data.setValue(0, 1, 40 + Math.round(60 * Math.random()));
//                chart.draw(data, options);
//            }, 13000);
//            setInterval(function() {
//                data.setValue(1, 1, 40 + Math.round(60 * Math.random()));
//                chart.draw(data, options);
//            }, 5000);
//            setInterval(function() {
//                data.setValue(2, 1, 60 + Math.round(20 * Math.random()));
//                chart.draw(data, options);
//            }, 26000);

//            var data = new google.visualization.DataTable();
//            data.addColumn('string', 'Topping');
//            data.addColumn('number', 'Slices');
//            data.addRows([
//                ['Mushrooms', 3],
//                ['Onions', 1],
//                ['Olives', 1],
//                ['Zucchini', 1],
//                ['Pepperoni', 2]
//            ]);
//
//            var container = document.getElementById('curve_chart_1');
//            var chart = new google.visualization.LineChart(container);
//            chart.draw(data);
        }


    /**
     * Number.prototype.format(n, x)
     *
     * @param integer n: length of decimal
     * @param integer x: length of sections
     */
    Number.prototype.format = function(n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
    };


    </script>
@stop
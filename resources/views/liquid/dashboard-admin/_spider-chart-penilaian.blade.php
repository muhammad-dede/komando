@extends('layout')

@push('styles')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Sweet Alert css -->
    <style>
        .select2 {
            width: 100% !important;
        }

    </style>
@endpush
@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12 col-lg-6">
            <h4 class="page-title">Detail Penilaian</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xs-12 lh-70 align-right">
            <a href="{{ url('dashboard-admin/download-report-liquid') }}" class="btn btn-primary"><em
                        class="fa fa-arrow-left"></em> Kembali</a>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3  col-xs-12">
            <div class="card-box">
                <canvas id="chart-penilaian-resolusi" height="250px"></canvas>

                <table class="table table-bordered">
                    <tr>
                        <th colspan="2">Keterangan</th>
                    </tr>
                    @foreach($resolusi as $item)
                        <tr>
                            <td width="150px">{{ $item['label'] }}</td>
                            <td>{{ $item['resolusi'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script src="{{ asset('vendor/chartjs/dist/Chart.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            let resolusi			= {!! $resolusi->pluck('label')->toJson() !!};
            let avgPenilaianPertama	= {!! $resolusi->pluck('avg_pengukuran_pertama')->toJson() !!};
            let avgPenilaianKedua	= {!! $resolusi->pluck('avg_pengukuran_kedua')->toJson() !!};

            Chart.defaults.global.elements.rectangle.pointStyle = 'circle'

            var marksCanvas = document.getElementById("chart-penilaian-resolusi");
            var marksData = {
                labels: resolusi,
                datasets: [{
                    label: "Penilaian Pertama",
                    backgroundColor: "rgba(45, 156, 219, 0.4)",
                    data: avgPenilaianPertama
                }, {
                    label: "Penilaian Kedua",
                    backgroundColor: "rgba(235, 87, 87, 0.4)",
                    data: avgPenilaianKedua
                }]

            };

            var radarChart = new Chart(marksCanvas, {
                type: 'radar',
                data: marksData,
                options: {
                    scale: {
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            min: 0,
                            max: 10
                        },
                    },
                    legend: {
                        display: true,
                        labels: {
                            usePointStyle: true,
                        },
                    }
                }
            });
        });
    </script>
@endpush

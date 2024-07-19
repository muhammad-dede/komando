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
            <h4 class="page-title">Detail History Penilaian</h4>
        </div>
        <div class="col-md-6 col-lg-6 col-xs-12 lh-70 align-right">
            <a href="{{ url('dashboard-bawahan/liquid-jadwal') }}" class="btn btn-primary"><em
                        class="fa fa-arrow-left"></em> Kembali</a>
        </div>
    </div>
@stop

@section('content')
    <form action="" method="post">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#profil" role="tab"
                                   aria-controls="home" aria-selected="true">Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#kelebihan" role="tab"
                                   aria-controls="profile" aria-selected="false">{!! $kelebihan !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#kekurangan" role="tab"
                                   aria-controls="profile" aria-selected="false">{!! $kekurangan !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#saran" role="tab"
                                   aria-controls="profile" aria-selected="false">{!! $saran !!}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#harapan" role="tab"
                                   aria-controls="profile" aria-selected="false">Harapan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#penilaian" role="tab"
                                   aria-controls="profile" aria-selected="false">Penilaian</a>
                            </li>
                        </ul>
                        <div class="tab-content pad-1rem">
                            <div class="tab-pane fade show active in" id="profil" role="tabpanel"
                                 aria-labelledby="home-tab">
                                @include('liquid.dashboard-bawahan.detail-history-penilaian._profil')
                            </div>
                            <div class="tab-pane  fade" id="kelebihan" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        @include('liquid.dashboard-bawahan.detail-history-penilaian._kelebihan')
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane  fade" id="kekurangan" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        @include('liquid.dashboard-bawahan.detail-history-penilaian._kekurangan')
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane  fade" id="saran" role="tabpanel">
                                @include('liquid.dashboard-bawahan.detail-history-penilaian._saran')
                            </div>
                            <div class="tab-pane  fade" id="harapan" role="tabpanel">
                                @include('liquid.dashboard-bawahan.detail-history-penilaian._harapan')
                            </div>
                            <div class="tab-pane  fade" id="penilaian" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        @include('liquid.dashboard-bawahan.detail-history-penilaian._penilaian')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {

        });

    </script>
@endpush

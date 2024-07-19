@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .select2 {
            width: 100% !important;
        }

    </style>


@stop

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">Show Liquid</h4>
        </div>
        <div class="col-md-6 col-xs-12 lh-70 align-right">
            <a href="{{ url('liquid/'.Request::segment(2).'/edit') }}" class="btn btn-warning"><em class="fa fa-pencil"></em> Edit Liquid</a>
            <a href="{{ url('dashboard-admin/liquid-jadwal') }}" class="btn btn-primary"><em class="fa fa-arrow-right"></em> Back Dashboard</a>
        </div>
    </div>
@stop

@section('content')

    @include('components.flash')

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                @include('components.liquid-tab-show', ['active' => 'peserta'])
                <div class="tab-content comp-tab-content" style="">
					<form action="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), $liquid->id) }}" method="get">
						<div class="row" style="margin-bottom: 12px">
							<div class="col-md-6 col-xs-12 lh-70"></div>
							<div class="col-md-4 col-xs-12 lh-70">
								<div class="lh-70 float-right width-full">
									<input type="text" class="form-control" name="search"
										placeholder="search peserta"
										value="{{ request('search', '') }}">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<button type="submit" class="btn btn-primary width-full">Search</button>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12 col-xs-12">
							@include('liquid.liquid-peserta._tabel_peserta', ['editable' => false])
						</div>
					</div>
            </div>
        </div>
    </div>
@stop

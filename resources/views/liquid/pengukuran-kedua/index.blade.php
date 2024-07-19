@extends('layout')

@section('css')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
	<link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
	<link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
	<div class="row">
		<div class="col-md-6 col-xs-12 lh-70">
			<h4 class="page-title">Pengukuran Kedua</h4>
		</div>
		<div class="col-md-6 col-xs-12 lh-70 align-right">
			<a href="{{ url('dashboard-bawahan/liquid-jadwal') }}" class="btn btn-primary"><em class="fa fa-arrow-left"></em> Back to Dashboard</a>
		</div>
	</div>
@stop

@section('content')
	<div class="row">
		@foreach ($dataAtasan as $index => $data)
			@if (! empty($data))
				<div class="col-md-6">
					<div class="card-box radius-10">
						<div class="row">
							<div class="col-md-3">
								<img src="{{ app_user_avatar(array_get($data, 'atasan.nip')) }}"
									alt="user"
									width="200"
									class="radius-full img-fluid mx-auto d-block">

							</div>
							<div class="col-md-9">
								<table class="mar-b-1rem table table-striped">
									<tr>
										<td class="bold-text">Nama</td>
										<td>:</td>
										<td>{{ array_get($data, 'atasan.nama') }}</td>
									</tr>
									<tr>
										<td class="bold-text">NIP</td>
										<td>:</td>
										<td>{{ array_get($data, 'atasan.nip') }}</td>
									</tr>
									<tr>
										<td class="bold-text">Jabatan</td>
										<td>:</td>
										<td>{{ array_get($data, 'atasan.jabatan') }}</td>
									</tr>
									<tr>
										<td class="bold-text">Unit</td>
										<td>:</td>
										<td>{{array_get($data, 'atasan.unit.code') }} - {{ array_get($data, 'atasan.unit.name') }}</td>
									</tr>
									<tr>
										<td class="bold-text">Status</td>
										<td>:</td>
										<td>
											@if (isset($data['pengukuran_kedua']))
												<span class="badge badge-success">Penilaian selesai</span>
											@else
												<span class="badge badge-danger">Belum diberikan penilaian</span>
											@endif
										</td>
									</tr>
								</table>
								@if (isset($data['pengukuran_kedua']))
									<a href="{{ route('pengukuran-kedua.show', $data['pengukuran_kedua']['id']) }}"
										class="btn btn-warning">
										<em class="fa fa-eye"></em>
										Show Pengukuran Kedua
									</a>
								@else
									@can('pengukuranKedua', $data['peserta'])
									<a href="{{ route('pengukuran-kedua.create').'?liquid_peserta_id='.$data['id_lp'] }}"
										class="btn btn-primary">
										<em class="fa fa-commenting-o"></em>
										Input Pengukuran Kedua
									</a>
									@else
										<div class="alert alert-warning">Pengukuran Kedua Belum Dibuka</div>
									@endcan
								@endif
							</div>
						</div>
					</div>
				</div>
			@endif
		@endforeach
    </div>
@stop

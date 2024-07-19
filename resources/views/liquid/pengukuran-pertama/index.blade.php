@extends('layout')

@section('css')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
	<link href="{{asset('assets/css/image.css')}}" rel="stylesheet" type="text/css"/>
	<link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
	<div class="row">
		<div class="col-md-6 col-xs-12 lh-70">
			<h4 class="page-title">Pengukuran Pertama</h4>
		</div>
		<div class="col-md-6 col-xs-12 lh-70 align-right">
			<a href="{{ route('dashboard-bawahan.liquid-jadwal.index') }}" class="btn btn-primary"><em class="fa fa-arrow-left"></em> Back to Dashboard</a>
		</div>
	</div>
@stop

@section('content')
	<div class="row">
		@foreach ($dataAtasan as $index => $data)
			<div class="col-md-6">
				<div class="card-box radius-10">
					<div class="row">
						<div class="col-md-3">
							<img src="{{array_get($data, 'atasan.foto')}}"
								 alt="user"
								 width="200"
								 class="img-fluid radius-10 mar-0-auto">
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
									<td>{{ array_get($data, 'atasan.unit') }}</td>
								</tr>
								<tr>
									<td class="bold-text">Status</td>
									<td>:</td>
									<td>
										@if ($data['pengukuran_pertama'])
											<span class="badge badge-success">Penilaian selesai</span>
										@else
											<span class="badge badge-danger">Belum diberikan penilaian</span>
										@endif
									</td>
								</tr>
							</table>
							@if (isset($data['pengukuran_pertama']))
								<a href="{{ route('penilaian.show', $data['pengukuran_pertama']['id'].'?liquid_id='.$liquidId) }}"
									class="btn btn-warning">
									<em class="fa fa-eye"></em>
									Show Pengukuran Pertama
								</a>
							@else
								<a href="{{ route('penilaian.create').'?liquid_peserta_id='.$data['id_lp'] }}"
									class="btn btn-primary">
									<em class="fa fa-commenting-o"></em>
									Input Pengukuran Pertama
								</a>
							@endif
						</div>
					</div>
				</div>
			</div>
		@endforeach
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable();
        });
    </script>
@stop

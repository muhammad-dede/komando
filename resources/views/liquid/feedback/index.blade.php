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
			<h4 class="page-title">Feedback Untuk Atasan</h4>
		</div>
		<div class="col-md-6 col-xs-12 lh-70 align-right">
			<a href="{{route('dashboard-bawahan.liquid-jadwal.index')}}" class="btn btn-primary"><em class="fa fa-arrow-left"></em> Back to Dashboard</a>
		</div>
	</div>
@stop

@section('content')
	<div class="row">
		@forelse ($dataAtasan as $liquidPeserta)
			<div class="col-md-6">
				<div class="card-box radius-10">
					<div class="row">
						<div class="col-md-4">
							<img src="{{ app_user_avatar($liquidPeserta->snapshot_nip_atasan) }}"
								 alt="user"
								 width="200"
								 class="radius-full img-fluid mx-auto d-block">
						</div>
						<div class="col-md-8">
							<table class="mar-b-1rem table table-striped">
								<tr>
									<td class="bold-text">Nama</td>
									<td>:</td>
									<td>{{ $liquidPeserta->snapshot_nama_atasan }}</td>
								</tr>
								<tr>
									<td class="bold-text">NIP</td>
									<td>:</td>
									<td>{{ $liquidPeserta->snapshot_nip_atasan }}</td>
								</tr>
								<tr>
									<td class="bold-text">Jabatan</td>
									<td>:</td>
									<td>{{ $liquidPeserta->snapshot_jabatan2_atasan }}</td>
								</tr>
								<tr>
									<td class="bold-text">Unit</td>
									<td>:</td>
									<td>{{ $liquidPeserta->snapshot_unit_code }} - {{ $liquidPeserta->snapshot_unit_name }}</td>
								</tr>
								<tr>
									<td class="bold-text">Status</td>
									<td>:</td>
									<td>
										@if ($liquidPeserta->feedback)
											<span class="badge badge-success">Feedback selesai</span>
										@else
											<span class="badge badge-danger">Belum diberikan feedback</span>
										@endif
									</td>
								</tr>
							</table>
							@can('inputFeedback', $liquidPeserta)
								<a
									href="{{ $liquidPeserta->href }}"
									class="{{ $liquidPeserta->class }}"
								>
									<em class="fa fa-commenting-o"></em>
									Input Feedback
								</a>
							@elsecan('updateFeedback', $liquidPeserta)
								<a
									href="{{ route('feedback.edit', $liquidPeserta->feedback->id) }}"
									class="btn btn-warning"
								>
									<em class="fa fa-pencil"></em>
									Edit Feedback
								</a>
							@elsecan('cantFeedback', $liquidPeserta)
								<a class="btn btn-secondary disabled">
									<em class="fa fa-commenting-o"></em>
									Input Feedback
								</a>
							@endcan
						</div>

						<div class="col-md-12" style="margin-top: 10px">
							@if ($liquidPeserta->message)
								{!! $liquidPeserta->message !!}
							@endif
						</div>
					</div>
				</div>
			</div>
		@empty
			<div class="alert alert-warning">
				Anda tidak terdaftar sebagai peserta di Liquid #{{ $liquid->id }}
			</div>
		@endforelse
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

@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">Master Data {!! $kelebihan !!} dan {!! $kekurangan !!}</h4>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="lh-70 float-right">
				<form action="{{ route('master-data.kelebihan-kekurangan.create') }}" method="get">
					<button type="submit"
						class="btn btn-blue"
						style="text-decoration: none">
						<em class="fa fa-plus"></em>
						Tambah Master Data
					</button>
				</form>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="datatable" class="table table-striped table-bordered">
                    <thead class="thead-blue">
                    <tr>
                        <th width="50" style="text-align: center">No.</th>
                        <th style="text-align: center">Master Data</th>
                        <th style="text-align: center">Jumlah {!! $kelebihan !!} dan {!! $kekurangan !!}</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
						@foreach ($data as $index => $item)
						<tr>
							<td>{{ $index+1 }}</td>
							<td align="center">{{ $item['judul'] }}</td>
							<td align="center">{{ $item['childs'] }}</td>
							<td align="center">
								@if ($item['status'] === 'AKTIF')
									<button class="btn btn-green-small">Aktif</button>
								@else
									<button class="btn btn-warning-small">Tidak Aktif</button>
								@endif
							</td>
							<td align="center">
								<a href="{{ route('master-data.kelebihan-kekurangan.show', $item['id']) }}">
									<em class="fa fa-eye fa-2x"></em>
								</a>
							</td>
						</tr>
						@endforeach
                    </tbody>
                </table>
            </div>
        </div>
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

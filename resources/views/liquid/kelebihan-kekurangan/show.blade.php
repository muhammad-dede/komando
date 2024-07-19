@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">{!! $kelebihan !!} dan {!! $kekurangan !!}</h4>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="lh-70 float-right">
                <a href="{{ url('master-data/kelebihan-kekurangan/') }}" class="btn btn-blue-negative"><em class="fa fa-arrow-left"></em> Back</a>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-blue">
                    <tr>
                        <th>Judul</th>
                        <th>{!! $kelebihan !!}</th>
                        <th>{!! $kekurangan !!}</th>
                        <th>Aksi</th>
                    </tr>
					</thead>
                    <tbody>
						<tr>
							<td rowspan="{{ $data->details()->count() }}">
								<div class="font-500">{{ $data->title }}:</div>
								<div>
									{!! $data->deskripsi !!}
								</div>
							</td>
							<td>
								<div>
									{!! isset($data->details[0]) ? $data->details[0]->deskripsi_kelebihan : '-' !!}
								</div>
							</td>
							<td>
								<div>
									{!! isset($data->details[0]) ? $data->details[0]->deskripsi_kekurangan : '-' !!}
								</div>
							</td>
							<td rowspan="{{ $data->details()->count() }}" style="min-width: 100px;">
								<form action="{{ route('master-data.kelebihan-kekurangan.destroy', $data->id) }}" method="post">
									<a href="{{ route('master-data.kelebihan-kekurangan.edit', $data->id) }}" class="btn btn-warning">
										<em class="fa fa-pencil fa-2x"></em>
									</a>
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="delete" />
									<button type="submit" class="btn btn-danger" name="delete-button"
										onclick="return confirm('Anda yakin untuk menghapus data ini?')">
										<em class="fa fa-trash-o fa-2x"></em>
									</button>
								</form>
							</td>
						</tr>
							@foreach ($data->details as $index => $child)
							@if ($index > 0)
								<tr>
									<td>
										<div>
											{!! $child->deskripsi_kelebihan !!}
										</div>
									</td>
									<td>
										<div>
											{!! $child->deskripsi_kekurangan !!}
										</div>
									</td>
								</tr>
							@endif
							@endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
@stop

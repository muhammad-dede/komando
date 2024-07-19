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
    <link href="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('vendor/fileuploader-2.2/dist/font/font-fileuploader.css') }}" media="all" rel="stylesheet">
	<link href="{{ asset('vendor/fileuploader-2.2/dist/jquery.fileuploader.min.css') }}" media="all" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="{{ asset('vendor/light-gallery/dist/css/lightgallery.min.css') }}" />
	<style>
		.select2 {
			width: 100% !important;
		}
		.fileuploader-input-button {
			background: #039cfd !important;
		}
		.label-upload {
			position: absolute;
			top: 48px;
			font-size: 11px;
			font-style: italic;
			color: #789bec;
			font-weight: bold;
		}
	</style>
@endpush


@section('title')
	@include('components.flash')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h4 class="page-title">Input Activity Log</h4>
        </div>
    </div>
@stop

@section('content')
	<form action="{{ route('activity-log.update', $data->id) }}" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-12 col-xs-12">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<div class="form-group">
												<label>Pilih Resolusi <span
															class="text-danger">*</span></label>
												{!! Form::select('resolusi[]', collect($resolusi)->pluck('resolusi', 'id'), array_first($data->resolusi),
												['class'=>'form-control select2',
												'required' => 'required',
												'id'=>'business_area']) !!}
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 col-xs-12">
									<div class="form-group">
										<label>Nama Kegiatan<span class="text-danger">*</span></label>
										<div>
											<div class="input-group">
												<input type="text" class="form-control" name="nama_kegiatan"
													value="{{ old('nama_kegiatan', $data->nama_kegiatan) }}"
													autocomplete="off" required/>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="form-group">
										<label>Tanggal Mulai Pelaksanaan <span class="text-danger">*</span></label>
										<div>
											<div class="input-group">
												<input type="text" class="form-control tanggal"
													name="start_date"
													value="{{ old('start_date', $data->start_date->format('d-m-Y')) }}"
													autocomplete="off"/>
												<span class="input-group-addon bg-custom b-0"><em
													class="icon-calender"></em></span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="form-group">
										<label>Tanggal Selesai Pelaksanaan <span class="text-danger">*</span></label>
										<div>
											<div class="input-group">
												<input type="text" class="form-control tanggal"
													name="end_date"
													   value="{{ old('end_date', $data->end_date->format('d-m-Y')) }}"
													autocomplete="off"/>
												<span class="input-group-addon bg-custom b-0"><em
													class="icon-calender"></em></span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 col-xs-12">
									<div class="form-group">
										<label>Tempat Kegiatan<span class="text-danger">*</span></label>
										<div>
											<div class="input-group">
												<input type="text" class="form-control" name="tempat_kegiatan"
													value="{{ old('tempat_kegiatan', $data->tempat_kegiatan) }}"
													autocomplete="off"/>
												<span class="input-group-addon bg-custom b-0"><em
													class="icon-location-pin"></em></span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 col-xs-12">
									<div class="form-group">
										<label for="pernr_leader" class="form-control-label">Deskripsi <span
													class="text-danger">*</span></label>
										<div>
											<textarea name="deskripsi" rows="20"
												class="form-control form-control-danger deskripsi mar-r-1rem"
												placeholder="Masukan Deskripsi Program">{!! old('deskripsi', $data->keterangan) !!}</textarea>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="form-group">
										<label for="pernr_leader" class="form-control-label">Bagikan Dokumentasi Aktivitas (opsional)</label>
										<input type="file" name="dokumen">
									</div>
								</div>
								<div class="col-md-6 col-xs-12">
									<br>
									<table class="table table table-striped table-bordered">
										<thead>
										<tr>
											<th>Nama File</th>
											<th>Ukuran File</th>
											<th class="align-center">Aksi</th>
										</tr>
										</thead>
										<tbody>
										@foreach($data->getMedia() as $media)
											<tr>
												<td>
													@if($media->getExtensionAttribute() == 'pdf')
														<a href="{{ $media->getUrl() }}" target="_blank">{{ $media->file_name }}</a>
													@else
														<img src="{{ $media->getUrl() }}" alt="" class="img-thumbnail img-responsive">
													@endif
												</td>
												<td>{{ $media->human_readable_size }}</td>
												<td align="center">
													<a href="{{ route('media.destroy', $media) }}"
													   class="badge badge-danger hapus" data-toggle="tooltip" title="hapus"><em
																class="fa fa-trash-o fa-2x"></em></a>
												</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row m-t-20">
							<div class="col-md-6 col-xs-12">
							</div>
							<div class="col-md-6 pull-right col-xs-12">
								<div class="button-list">
									<div class="button-list">
										<button type="submit" id="submit" class="btn btn-primary btn-lg pull-right"><em
											class="fa fa-save"></em>
											Update
										</button>
									</div>
									<a href="{{ route('activity-log.index') }}"  class="btn btn-warning btn-lg pull-right"><em
										class="fa fa-times"></em>
										Cancel
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div id="wrapper-galeri-kegiatan"></div>
@stop

@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <!-- Sweet Alert js -->
    <script src="{{asset('assets/plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendor/fileuploader-2.2/dist/jquery.fileuploader.min.js')}}"></script>
	<script src="{{ asset('vendor/light-gallery/dist/js/lightgallery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
			$('input[name="dokumen"]').fileuploader({
				addMore: true,
				fileMaxSize: 5,
				extensions: ['pdf','jpg','png','jpeg'],
				thumbnails: {
					removeConfirmation: false
				}
			});
			$(".show-galeri-kegiatan").click(function () {
				$('#wrapper-galeri-kegiatan').lightGallery({
					dynamic: true,
					dynamicEl: [
						{
							"src": "{{ url('assets/images/users/avatar-1.jpg') }}",
							'thumb': "{{ url('assets/images/users/avatar-1.jpg') }}",
						},
						{
							"src": "{{ url('assets/images/users/avatar-1.jpg') }}",
							'thumb': "{{ url('assets/images/users/avatar-1.jpg') }}",
						}]
				})

			});

            $(".select2").select2();
            $('#datatable').DataTable();
            tinymce.init({
                mode: "textareas",
                editor_selector: "deskripsi",
                height: 200,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste code'
                ],
                toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
            });
        });
        jQuery('.tanggal').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            orientation: "left"
        });
    </script>
@endpush

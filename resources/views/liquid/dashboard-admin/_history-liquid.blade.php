@push('styles')
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
	<link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
	<style>
		.rotate.up i.fa.fa-chevron-down {
            -webkit-transform: rotate(180deg);
            -moz-transform: rotate(180deg);
            -ms-transform: rotate(180deg);
            -o-transform: rotate(180deg);
            transform: rotate(180deg);
        }
	</style>
@endpush

@php
	ini_set('max_execution_time', 600);
	$liquids = $liquidService->getHistoryInformation_forpeserta(
		request('unit_code', auth()->user()->business_area),
		request('divisi', auth()->user()->getKodeDivisiPusat()),
		$params
	);
	$liquidJabatan = \Illuminate\Support\Facades\Cache::get('liquid_information_history_jabatan_key_'.auth()->user()->id);
@endphp

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="card-box">
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="home-tab" data-toggle="tab" href="#peserta-liquid" role="tab"
					aria-selected="true">Peserta Liquid</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="profile-tab" data-toggle="tab" href="#self-service" role="tab" aria-selected="false">{{ empty($usulan_atasan) ? 'Daftar Usulan Atasan' : $usulan_atasan }}</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="profile-tab" data-toggle="tab" href="#bawahan-kurang-dari-3" role="tab" aria-selected="false">Atasan yang memiliki bawahan < 3</a>
				</li>
			</ul>
			<div class="tab-content comp-tab-content" id="myTabContent">
            	<div class="tab-pane fade show active in" id="peserta-liquid" role="tabpanel">
					<div class="title-top mar-b-2rem">
						Peserta Liquid
						@if(is_unit_pusat(request('unit_code', auth()->user()->getKodeUnit())))
							{{ \Illuminate\Support\Facades\DB::table('v_divisi_pusat')->where('objid', request('divisi', auth()->user()->getKodeDivisi()))->value('stext') }}
						@endif
						<div class="pull-right">
							<a class="btn btn-success" href="{{ route('dashboard-admin.liquid-peserta.download', ['unit_code' => request('unit_code', auth()->user()->business_area), 'divisi' => request('divisi', auth()->user()->getKodeDivisiPusat())]) }}">
								<i aria-hidden="true" class="fa fa-download"></i>
								Export Xls
							</a>
						</div>
					</div>
					@foreach($liquidJabatan as $jabatanGroup)
						@foreach ($jabatanGroup as $jabatan)
							<div class="card">
								<div class="card-header">
									<h2 class="mb-0">
										<button class="btn btn-link rotate" type="button" data-toggle="collapse"
											id="headingOne"
											data-target="#{{ $jabatan }}"
											aria-expanded="true">
											<h3>
												{{ trans(sprintf('enum.%s.%s', \App\Enum\LiquidJabatan::class, $jabatan ?: 'uncategorized')) }}
												<span id="dasboar_admin_jabatan_{{ $jabatan }}" style="font-size: 15px;color: red;"></span>
												<i class="fa fa-chevron-down" aria-hidden="true"></i>
											</h3>
										</button>
									</h2>
								</div>
							</div>
							<div id="{{ $jabatan }}" class="collapse show" aria-labelledby="headingOne">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered" id="datatable_{{ $jabatan }}">
											<thead class="thead-blue">
												<tr>
													<th class="color-white vertical-middle">Unit</th>
													<th class="color-white vertical-middle">Foto</th>
													<th class="color-white vertical-middle">Nama Atasan</th>
													<th class="color-white vertical-middle">NIP</th>
													<th class="color-white vertical-middle">Jabatan</th>
													<th class="color-white vertical-middle">Jumlah Bawahan</th>
													<th class="color-white vertical-middle">Feedback Bawahan</th>
													<th class="color-white vertical-middle">Penyelarasan</th>
													<th class="color-white vertical-middle">Pengukuran Pertama</th>
													<th class="color-white vertical-middle">Activity Log</th>
													<th class="color-white vertical-middle">Pengukuran Kedua</th>
													<th class="color-white vertical-middle">Aktifkan Pengukuran Kedua</th>
													<th class="color-white vertical-middle">Jadwal Saat Ini</th>
													@if (Auth::user()->can('liquid_info_detil_pelaksannan'))
														<th class="align-center title-top color-white vertical-middle" style="min-width: 50px;">Aksi</th>
													@endif
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						@endforeach
					@endforeach
				</div>

				<div class="tab-pane fade" id="self-service" role="tabpanel">
					<div class="col-12">
						@include('liquid.dashboard-admin._widget-self-service')
					</div>
				</div>

				<div class="tab-pane fade" id="bawahan-kurang-dari-3" role="tabpanel">
					@include('liquid.dashboard-admin._widget-atasan-less-than-3')
				</div>
			</div>
		</div>
	</div>
</div>

@foreach ($liquids as $liquid)
	@foreach ($liquid as $jabatanAtasan => $dataPerAtasan)
		@foreach($dataPerAtasan as $idAtasan => $atasan)
			@include('liquid.dashboard-admin._modal-bawahan')
			@include('liquid.dashboard-admin._modal-feedback-bawahan')
			@include('liquid.dashboard-admin._modal-pengukuran-1')
			@include('liquid.dashboard-admin._modal-pengukuran-2')
			@include('liquid.dashboard-admin._modal-activity-log')
		@endforeach
	@endforeach
@endforeach

@push('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script>
        $(document).ready(function () {
			$('.datatable-modal').DataTable();

			dataTableWidgetAtasanLessThan3 = $(`#table_atasan_less_than_3`).DataTable({
				processing: true,
				serverSide: true,
				bPaginate: true,
				columnDefs: [
					{ width: 200, targets: 0 }
				],
				ajax: {
					url: "{{ route('data_table.liquid.history.lessThan.3') }}",
					data: function (d) {
						let urlParams = new URLSearchParams(window.location.search);

						let unitCode = urlParams.get('unit_code'),
							divisi = urlParams.get('divisi'),
							year = urlParams.get('year'),
							startedDate = urlParams.get('start_date'),
							endDate = urlParams.get('end_date');

						if (unitCode !== null) {
							d.unit_code = unitCode;
						}

						if (divisi !== null) {
							d.divisi = divisi;
						}

						if (startedDate !== null) {
							d.startedDate = startedDate;
						}

						if (endDate !== null) {
							d.endDate = endDate;
						}

						d.kelompok_jabatan = "";
						d.year = year;
					},
					dataType: "json",
					type: "GET"
				},
				columns: [
					{"data":"unit", "class" : "dt-body-center"},
					{"data":"foto", "class" : "dt-body-center"},
					{"data":"nama_atasan"},
					{"data":"nip", "class" : "dt-body-center"},
					{"data":"jabatan"},
					{"data":"jumlah_bawahan", "class" : "dt-body-center"},
				],
			});

			var cols = [
				{"data":"unit"},
				{"data":"foto"},
				{"data":"nama_atasan"},
				{"data":"nip"},
				{"data":"jabatan"},
				{"data":"jumlah_bawahan"},
				{"data":"feedback_bawahan"},
				{"data":"penyelarasan"},
				{"data":"pengukuran_pertama"},
				{"data":"act_log"},
				{"data":"pengukuran_kedua"},
				{"data":"activate_pengukuran_kedua", "orderable": false},
				{"data":"jadwal_current", "orderable": false},
				{"data":"detail_view", "orderable": false}
			];

			@if(! Auth::user()->can('liquid_info_detil_pelaksannan'))
				cols.splice(13, 1);
			@endif

			@foreach($liquidJabatan as $jabatanGroup)
				@foreach($jabatanGroup as $jabatan)
					var setNotif = 0;
					$({!! json_encode('#datatable_'.$jabatan) !!}).DataTable({
					processing: true,
					serverSide: true,
					bPaginate: true,
					columnDefs: [
						{ width: 200, targets: 0 }
					],
					ajax: {
						url: "{{ route('data_table.liquid.history') }}",
						data: function (d) {
							let urlParams = new URLSearchParams(window.location.search);

							let unitCode = urlParams.get('unit_code'),
								divisi = urlParams.get('divisi'),
								startedDate = urlParams.get('start_date'),
								year = urlParams.get('year'),
								endDate = urlParams.get('end_date');

							if (unitCode !== null) {
								d.unit_code = unitCode;
							}

							if (divisi !== null) {
								d.divisi = divisi;
							}

							if (startedDate !== null) {
								d.startedDate = startedDate;
							}

							if (endDate !== null) {
								d.endDate = endDate;
							}

							d.kelompok_jabatan = {!! json_encode($jabatan) !!};
							d.year = year;
						},
						dataType: "json",
						type: "GET"
					},
					columns: cols,
					"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
						if(aData.valid_dibawah_3 != "true"){
							$('td', nRow).css('background-color', 'yellow');
							$(`#dasboar_admin_jabatan_{{ $jabatan }}`).text('Ada Atasan yang mempunyai bawahan < 3');
							$(`#btn_more_less_than_3`).css("display","");
						}
                	},
				});
				@endforeach
			@endforeach

			@foreach ($liquids as $liquid)
				@foreach ($liquid as $jabatanAtasan => $dataPerAtasan)
					@foreach($dataPerAtasan as $idAtasan => $atasan)
						//bulk delete
						$('#modal-bawahan{{ $atasan["atasan_snapshot"]["nip"] }} input[name="pesertaIds[]"]').on('change', function(){
							let countSelected = $('#modal-bawahan{{ $atasan["atasan_snapshot"]["nip"] }} input[name="pesertaIds[]"]:checked').length;
							if (countSelected > 0) {
								$('#modal-bawahan{{ $atasan["atasan_snapshot"]["nip"] }} [data-counter-peserta-terpilih]').html(countSelected).parent().removeClass('disabled')
							} else {
								$('#modal-bawahan{{ $atasan["atasan_snapshot"]["nip"] }} [data-counter-peserta-terpilih]').html('').parent().addClass('disabled')
							}
						});
					@endforeach
				@endforeach
			@endforeach

            $("select[name='bawahan[]'").select2({
                minimumInputLength: 3,
                ajax: {
                    url: '{{ route('api.pegawai.index') }}',
                    dataType: 'json',
                    delay: 800,
                }
            });
        });
    </script>
@endpush


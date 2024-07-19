<div class="modal fade" id="modal-activity-log{{ $atasan['atasan_snapshot']['nip'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header border-unset">
                <span class="title title-top" id="exampleModalLabel">Activity Log Book Atasan {{ ucwords(strtolower($atasan['atasan_snapshot']['nama'])) }}</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table tabel-border datatable-modal" border="1">
                        <thead class="thead-blue">
                        <tr>
                            <th>Resolusi</th>
							<th>Nama Kegiatan/Perbaikan</th>
							<th>Tanggal Pelaksanaan</th>
                        </tr>
                        </thead>
                        <tbody>
							@foreach($atasan['activity_log_book'] as $log)
							<tr>
								<td>
									<ul>
										@foreach($log->getResolusi() as $resolusi)
											<li>{{ $resolusi }}</li>
										@endforeach
									</ul>
								</td>
								<td>{{ $log->nama_kegiatan }}</td>
								<td>
									<div> <strong>{{ \Carbon\Carbon::parse($log->start_date)->format('d-m-Y') }}</strong> </div>
									s/d
									<div> <strong>{{ \Carbon\Carbon::parse($log->end_date)->format('d-m-Y') }}</strong> </div>
								</td>
							</tr>
							@endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-unset">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><em class="fa fa-times"></em>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

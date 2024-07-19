<div class="modal fade" id="feedback{{ $atasan['atasan_snapshot']['nip'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header border-unset">
                <span class="title title-top" id="exampleModalLabel">Feedback Bawahan</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table tabel-border datatable-modal" border="1">
                        <thead class="thead-blue">
                        <tr>
                            <th>Nama Bawahan</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Status Feedback</th>
                            @if(auth()->user()->can('liquid_send_notification_bawahan'))
                                <th>Aksi</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(collect($atasan['peserta'])->sortBy('nama') as $dataBawahan)
                            <tr>
                                <td>{{ $dataBawahan['nama'] }}</td>
								<td>{{ $dataBawahan['nip'] }}</td>
								<td>{{ $dataBawahan['jabatan'] }}</td>
                                <td>
                                    @if ($dataBawahan['has_feedback'])
                                        <span class="badge badge-success" data-toggle="tooltip" title="Done">Done</span>
                                    @else
                                        <span class="badge badge-danger" data-toggle="tooltip" title="Not Yet">Not Yet</span>
                                    @endif
                                </td>
                                @if (auth()->user()->can('liquid_send_notification_bawahan'))
                                    <td>
                                        <form action="{{ route('dashboard-admin.liquid-peserta.notify', $dataBawahan['liquid_peserta_id']) }}" method="get">
                                            <input type="hidden" name="jenis_kegiatan" value="Feedback">
                                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Kirim Notifikasi">
                                                <em class="fa fa-bell"></em>
                                            </button>
                                        </form>
                                    </td>
                                @endif
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

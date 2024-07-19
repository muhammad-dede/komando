<div class="modal fade" id="modal-bawahan{{ $atasan['atasan_snapshot']['nip'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header border-unset">
                <span class="title title-top">Daftar Bawahan</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card">
                    <div class="card-header bg-blue">
                        <div class="title-top color-white">Tambah Bawahan</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('liquid.peserta.store', $atasan['liquid_id']) }}" id="formTambahBawahan">
                            {!! csrf_field() !!}
                            {!! method_field('POST') !!}
                            <input type="hidden" name="atasan_id" value="{{ $idAtasan }}">
                            <div class="form-group">
                                {!!
                                    Form::select(
                                        'bawahan[]',
                                        [],
                                        null,
                                        [
                                            'multiple' => 'multiple',
                                            'class' => 'select2 form-control'
                                        ]
                                    )
                                !!}
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                                Tambah
                            </button>
                        </form>
                    </div>
                </div>

                <br>
                <br>

                <div class="table-responsive">
                    <table class="table tabel-border datatable-modal" border="1">
                        <thead class="thead-blue">
                        <tr>
                            <th>Nama Bawahan</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            @if(auth()->user()->can('liquid_edit_peserta_bawahan'))
                                <th>Aksi</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
						@forelse (collect($atasan['peserta'])->sortBy('nama') as $pernrBawahan => $dataBawahan)
							<tr>
								<td>{{ $dataBawahan['nama'] }}</td>
								<td>{{ $dataBawahan['nip'] }}</td>
								<td>{{ $dataBawahan['jabatan'] }}</td>
								@if (auth()->user()->can('liquid_edit_peserta_bawahan'))
									<td>
										<div class="form-check">
											<input type="checkbox" class="form-check-input" form="formBulkDeletePeserta{{ $idAtasan }}" name="pesertaIds[]" value="{{ $dataBawahan['id'] }}">
										</div>
									</td>
								@endif
							</tr>
						@empty
							<tr>
								<td> - </td>
							</tr>
						@endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-unset">

                @if (auth()->user()->can('liquid_edit_peserta_bawahan'))
                <form
                        id="formBulkDeletePeserta{{ $idAtasan }}"
                        method="POST" action="{{ route('liquid.peserta.destroy', [$atasan['liquid_id'], 0]) }}">
                    {!! csrf_field() !!}
                    {!! method_field('DELETE') !!}
                    <button
                            type="submit"
                            name="action"
                            value="bulk"
                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                            class="btn btn-danger mar-b-1rem disabled">
                        Hapus <span data-counter-peserta-terpilih></span> Peserta Terpilih
                    </button>
                </form>
                @endif

                <button type="button" class="btn btn-warning" data-dismiss="modal"><em class="fa fa-times"></em>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

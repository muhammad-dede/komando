@push('styles')
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

<?php

$i = 1;
if (!isset($editable)) {
    $editable = false;
}
?>
@php($no=1)
@foreach($listPeserta as $jabatanAtasan => $listAtasan)
    <div class="card-header" id="headingOne">
        <h2 class="mb-0">
            <button class="btn btn-link rotate" type="button" data-toggle="collapse" data-target="#{{ $no }}"
                    aria-expanded="true">
                <h3>
                    {{ trans(sprintf('enum.%s.%s', \App\Enum\LiquidJabatan::class, $jabatanAtasan ?: 'uncategorized')) }}
                    @php($jmlBawahan=0)
                    @foreach(collect($listAtasan)->sortBy('nama') as $atasan)
                        @if($atasan['jml_bawahan'] < 3)
                            @php($jmlBawahan++)
                        @endif
                    @endforeach
                    <span style="font-size: 15px;color: red;">{{ ($jmlBawahan == 0) ? '' : '('.$jmlBawahan.' Atasan yang memiliki kurang dari 3 bawahan)' }}</span>
                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                </h3>
            </button>
        </h2>
    </div>
    <div id="{{ $no }}" class="collapse show" aria-labelledby="headingOne">
        <div class="card-body">
    @php($atasan_list=1)
    @foreach(collect($listAtasan)->sortBy('nama') as $atasan)
        <div class="card">
            <div class="card-header {{ ($atasan['jml_bawahan'] < 3) ? 'bg-yellow' : 'bg-blue color-white' }} card-header-blue" style="display: -webkit-box;display: -ms-flexbox;display: flex;">
                <span style="width: 5%;" class="lh-35 align-center"> {{ $i++ }} </span>
                <span style="width: 40%;" class="lh-35"> {{ $atasan['nama'] }}</span>
                <span style="width: 30%;" class="lh-35">{{ $atasan['nip'] }}</span>
                <span style="width: 25%;" class="lh-35">{{ $atasan['jabatan'] }}
                    @if($editable)
                    <form method="POST"
                          action="{{ route('liquid.peserta.destroy', [$liquid, $atasan['pernr']]) }}">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}
                        <button type="submit"
                                name="action"
								value="atasan"
								onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                class="btn btn-danger float-right">
                            <em class="fa fa-trash-o"></em>
                        </button>
                    </form>
                    <a href=""
                       class="btn {{ ($atasan['jml_bawahan'] < 3) ? 'btn-primary' : 'btn-warning'}} float-right"
                       data-toggle="modal"
                       data-role="buttonGantiAtasan"
                       data-atasan_id="{{ $atasan['pernr'] }}"
                       data-target="#modalAtasanPengganti">
                        <em class="fa fa-pencil"></em> Ubah Atasan
                    </a>
                    @endif
                </span>
            </div>
            <div class="card-body" id="bawahan_{{ $atasan_list}}">
                @foreach($atasan['peserta'] as $jabatanBawahan => $listBawahan)
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 40%;">{{ trans(sprintf('enum.%s.%s', \App\Enum\LiquidJabatan::class, $jabatanBawahan ?: 'uncategorized')) }}</th>
                            <th style="width: 20%;">NIP</th>
                            <th style="width: 30%;">Jabatan</th>
                            @if($editable)
                            <th style="width: 10%;" class="align-center" colspan="2">Aksi</th>
                            @endif
                        </tr>
                        </thead>
						<tbody>
							@foreach(collect($listBawahan)->sortBy('nama') as $bawahan)
								<tr class="selector">
									<td class="child-selector">{{ $bawahan['nama'] }}</td>
									<td class="child-selector">{{ $bawahan['nip'] }}</td>
									<td class="child-selector">{{ $bawahan['jabatan'] }}</td>
									@if($editable)
									<td class="child-selector" align="center">
										<form method="POST"
											action="{{ route('liquid.peserta.destroy', [$liquid, $bawahan['liquid_peserta_id']]) }}">
											{!! csrf_field() !!}
											{!! method_field('DELETE') !!}
											<button type="submit"
												onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
												class="badge badge-danger border-unset">
												<em class="fa fa-trash-o fa-2x"></em>
											</button>
										</form>
									</td>
									<td>
										<div class="form-check">
											<input type="checkbox" class="form-check-input" form="formBulkDeletePeserta" name="pesertaIds[]" value="{{ $bawahan['liquid_peserta_id'] }}">
										</div>
									</td>
									@endif
								</tr>
							@endforeach
                        </tbody>
                    </table>
                @endforeach

                @if($editable)
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn mar-b-1rem float-right" data-toggle="modal"
                                data-target="#modalPilihBawahan" data-role="tambahBawahan"
                                data-atasan_id="{{ $atasan['pernr'] }}">
                            <em class="fa fa-plus"></em>
                            Tambah Bawahan
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>
    @php($atasan_list++)
    @endforeach
        </div>
    </div>
    @php($no++)
@endforeach

@push('scripts')
    <script src="{{ asset('vendor/jquery-searcheable/dist/jquery.searchable-1.1.0.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-searcheable/dist/jquery.searchable-ie-1.1.0.min.js') }}"></script>
    <script>
        $( document ).ready(function() {
            $(".rotate").click(function () {
                $(this).toggleClass("up");
            })
        });

    </script>
@endpush

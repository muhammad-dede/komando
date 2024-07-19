<style>
    #modalKekurangan > div > div > div.modal-body > div.image > img {
        height: 100%;
        width: 100%;
    }
</style>

<div class="modal fade" id="modalKekurangan" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="title title-top" style="font-size: 20px !important">
                    <i class="fa fa-thumbs-down"></i> Pilih {!! $kekurangan !!} Atasan
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($posters->isExist->ofi)
                    <div class="image" href="{{ $posters->ofi }}">
                        <img src="{{ $posters->ofi }}" alt="" class="img-responsive img-fluid">
                    </div>
                    <br>
                @else
                <div class="alert alert-warning" style="color: #606060 !important">
                    <ul style="padding-left: 20px">
                        <li>Berikan masukan yang membangun ya</li>
                        <li>Silahkan pilih 3 {!! $kekurangan !!} yang perlu ditingkatkan dari atasan Anda</li>
                    </ul>
                </div>
                @endif

                <table data-role="datatable" class="table table-striped table-bordered" width="100%">
                    <thead class="thead-orange">
                    <tr>
                        <th>&nbsp;</th>
                        <th style="color: #606060 !important">Kategori</th>
                        <th style="color: #606060 !important">{!! $kekurangan !!}</th>
                        <th style="color: #606060 !important">Keterangan/Alasan harus terdiri dari minimal {{ $wordCount }} kata <span class="text-danger">*</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=0)
                    @foreach ($dataKK->details as $data)
                        <tr>
                            <td align="center">
                                <input type="checkbox"
                                       name="boxes_kekurangan[]"
                                       data-label="{{ $data->deskripsi_kekurangan }}"
                                       data-role="option"
                                       {{ array_key_exists($data->id, $selectedKekurangan) ? 'checked': '' }}
                                       value="{{ $data->id }}"
                                       id="boxes_kekurangan_{{ $data->id }}"
                                       {{-- data-group="{{ str_slug($data->category_string) }}" --}}
                                       onChange="actDisableAlasanKekurangan({{ $data->id }})">
                            </td>
                            <td align="center" style="vertical-align: middle">{{ $data->category_string }}</td>
                            <td align="left">{{ $data->deskripsi_kekurangan }}</td>
                            <td>
                                <textarea
                                    {{ array_key_exists($data->id, $selectedKekurangan) ? '': 'disabled' }}
                                    id="texts_area_kekurangan_{{ $data->id }}" name="boxes_alasan_kekurangan[{{ $data->id }}]" class="form-control spell-checking" style="width: 400px"
                                >{{ array_key_exists($data->id, $selectedKekurangan) ? $feedback->alasan_kekurangan[$data->id] : '' }}</textarea>
                                <span class="spell-invalid" style="display: none;color: red">Rekomendasi Perbaikan:</span>
                                <br>
                                <span class="spell-invalid checked" style="display: none"></span>
                            </td>
                        </tr>
                        @if(array_key_exists($data->id, $selectedKekurangan))
                            @php($i++)
                        @endif
                    @endforeach
                    <!-- <tr data-role="customOption">
                        <td align="center">
                            <input type="checkbox"
                                   name="boxes_kekurangan[__OTHER__]"
                                   {{ array_key_exists('__OTHER__', $selectedKekurangan) ? 'checked': '' }}
                                   data-label="{{ $feedback->kekurangan_lainnya }}"
                                   data-role="option">
                        </td>
                        <td align="left">
                            <label for="">Lainnya</label>
                            <div>
                                <textarea
                                    {{ array_key_exists('__OTHER__', $selectedKekurangan) ? '': 'disabled' }}
                                    name="new_kekurangan" class="form-control" style="width: 600px">{{ $feedback->kekurangan_lainnya }}</textarea>
                            </div>
                        </td>
                    </tr> -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><em class="fa fa-times"></em>
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-role="save">
                    Pilih
                </button>
            </div>
        </div>
    </div>
</div>

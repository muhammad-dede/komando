<style>
    #modalKelebihan > div > div > div.modal-body > div.image > img {
        height: 100%;
        width: 100%;
    }
</style>

<div class="modal fade" id="modalKelebihan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="title title-top" style="font-size: 20px !important">
                    <i class="fa fa-thumbs-up"></i> Pilih {!! $kelebihan !!} Atasan
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @if ($posters->isExist->strength)
                    <div class="image" href="{{ $posters->strength }}">
                        <img src="{{ $posters->strength }}" alt="" class="img-responsive img-fluid">
                    </div>
                    <br>
                @else
                <div class="alert alert-success" style="color: #606060 !important">
                    <ul style="padding-left: 20px">
                        <li>Pilih perilaku yang bisa dicontoh ya</li>
                        <li>Silahkan pilih 3 {!! $kelebihan !!} yang menonjol pada atasan Anda</li>
                    </ul>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" width="100%" data-role="datatable">
                        <thead class="thead-green">
                            <tr>
                                <th>&nbsp;</th>
                                <th>Kategori</th>
                                <th>{!! $kelebihan !!}</th>
                                <th>Keterangan/Alasan harus terdiri dari minimal {{ $wordCount }} kata <span class="text-danger">*</span></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $i = 0;
                                $merged = 1;
                                $oldCategory = null;
                            @endphp
                            @foreach ($dataKK->details as $data)
                                @php
                                    if ($oldCategory === $data->category_string) {
                                        $merged += 1;
                                    } else {
                                        $oldCategory = $data->category_string;
                                    }
                                @endphp
                                <tr>
                                    <td align="center">
                                        <input type="checkbox"
                                            name="boxes_kelebihan[]"
                                            data-label="{{ $data->deskripsi_kelebihan }}"
                                            data-role="option"
                                            {{ array_key_exists($data->id, $selectedKelebihan) ? 'checked': '' }}
                                            value="{{ $data->id }}"
                                            id="boxes_kelebihan_{{ $data->id }}"
                                            {{-- data-group="{{ str_slug($data->category_string) }}" --}}
                                            onChange="actDisableAlasanKelebihan({{ $data->id }})" />
                                    </td>
                                    <td align="center" style="vertical-align: middle">{{ $data->category_string }}</td>
                                    <td align="left">{{ $data->deskripsi_kelebihan }}</td>
                                    <td>
                                        <textarea
                                            {{ array_key_exists($data->id, $selectedKelebihan) ? '': 'disabled' }}
                                            id="texts_area_kelebihan_{{ $data->id }}" name="boxes_alasan_kelebihan[{{ $data->id }}]" class="form-control spell-checking" style="width: 400px"
                                        >{{ array_key_exists($data->id, $selectedKelebihan) ? $feedback->alasan_kelebihan[$data->id] : '' }}</textarea>
                                        <span class="spell-invalid" style="display: none">Rekomendasi Perbaikan:</span>
                                        <br>
                                        <span class="spell-invalid checked" style="display: none;color: red"></span>
                                    </td>
                                </tr>
                                @if(array_key_exists($data->id, $selectedKelebihan))
                                    @php($i++)
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

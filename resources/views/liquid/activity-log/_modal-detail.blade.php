<div class="modal fade" id="activity{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header border-unset">
                <span class="title title-top">Detail Resolusi</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table tabel-border" border="1">
                    <tr>
                        <td>Resolusi</td>
                        <td>
                            <ul>
                                @foreach ($item->getResolusi() as $res)
                                    <li>{{ $res }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama Kegiatan</td>
                        <td>{{ $item->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pelaksanaan</td>
                        <td>
                            <div> <strong>{{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}</strong> </div>
                            s/d
                            <div> <strong>{{ \Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}</strong> </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Tempat Pelaksanaan</td>
                        <td>{{ $item->tempat_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td>Deskripsi Kegiatan</td>
                        <td>
                            {!! $item->keterangan !!}
                        </td>
                    </tr>
                    <tr>
                        <td>Galeri Kegiatan</td>
                        <td>
                            @php($count = count($item->getMediaByType(['png', 'jpg', 'png'])))
                            @if($count > 0)
                                <button
                                        data-modal="activity{{ $item->id }}"
                                        data-galeri="wrapper-galeri-{{ $item->id }}"
                                        data-id="{{ $item->id }}"
                                        class='btn btn-primary show-galeri-kegiatan'>
                                    {{ $count }}
                                    foto
                                </button>
                            @else
                                <b>belum ada galeri foto</b>
                            @endif
                            <br>
                            <div class="title-pdf">list file pdf <em class="fa fa-download"></em>
                            </div>
                            @php
                                $pdf = $item->getMediaByType('pdf');
                            @endphp
                            @if(count($pdf) > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($pdf as $file)
                                    <li class="list-group-item"><a href="{{ $file->getUrl() }}" target="_blank">{{ $file->file_name }}</a></li>
                                    @endforeach
                                </ul>
                            @else
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">tidak ada file yang di upload</li>
                                </ul>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer border-unset">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><em class="fa fa-times"></em>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

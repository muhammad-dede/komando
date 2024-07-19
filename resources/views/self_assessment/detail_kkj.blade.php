<div>
    <table border="0" width="100%">
        <tr>
            <td style="padding: 5px;" width="200">Sebutan Jabatan</td>
            <td style="padding: 5px;">: <b>{{ $jabatan->sebutan_jabatan }}</b></td>
        </tr>
        <tr>
            <td style="padding: 5px;">Organisasi</td>
            <td style="padding: 5px;">: {{ $jabatan->organisasi }}</td>
        </tr>
        <tr>
            <td style="padding: 5px;">Jenjang Jabatan</td>
            <td style="padding: 5px;">: {{ $jabatan->jenjang_jabatan }}</td>
        </tr>
        <tr>
            <td style="padding: 5px;">Stream Business</td>
            <td style="padding: 5px;">: {{ $jabatan->stream_business }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>

<div class="card">
    <h5 class="card-header">Kompetensi Bidang</h5>
    <div class="card-block">
        <table class="table table-hover">
            @php
                $x=1;
            @endphp
            <thead>
            <tr>
                <th>No</th>
                <th width="150">Kode</th>
                <th>Deskripsi</th>
                <th width="100">Level</th>
            </tr>
            </thead>
            <tbody>
            @foreach($jabatan->kompetensi()->orderBy('kode_kompetensi','asc')->get() as $kompetensi)
            <tr>
                <td>{{ $x++ }}</td>
                <td>
                    <b>{{ @$kompetensi->kompetensi->kode }}</b>
                    
                    <div id="level_{{ $kompetensi->id }}_detail" class="rating-sm" style="margin-top: 10px;"></div>
                    {{-- @push('skrip') --}}
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $('#level_{{ $kompetensi->id }}_detail').raty({
                                readOnly: true,
                                number: {{ $jml_level }},
                                score: {{ @$kompetensi->level }},
                                starOff: 'fa fa-star-o text-muted',
                                starOn: 'fa fa-star text-danger',
                                hints: [{!! $hint !!}]
                            });
                        });
                        </script>
                    {{-- @endpush --}}
                
                </td>
                <td>
                    <p><b>{{ @$kompetensi->kompetensi->judul_kompetensi }} / <i>{{ @$kompetensi->kompetensi->judul_en }}</i></b></p>
                    <p>{{ @$kompetensi->kompetensi->deskripsi }}</p>
                </td>
                <td align="center" style="text-align: center;vertical-align: middle;">
                    <span class="label label-success" style="font-weight: bold; font-size:25px;" title="Level {{ @$kompetensi->level }}">{{ @$kompetensi->level }}</span>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
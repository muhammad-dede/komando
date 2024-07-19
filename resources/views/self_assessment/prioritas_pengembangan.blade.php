<div>
    <table border="0" width="100%">
        <tr>
            <td style="padding-top: 5px;padding-bottom: 5px;" width="150" rowspan="5"> <img src="{{ app_user_avatar($peserta->nip_pegawai) }}" alt="user" class="img-fluid img-thumbnail" > </td>
            <td style="padding-top: 5px;padding-bottom: 5px;padding-left: 30px;" width="200" >Nama / NIP </td>
            <td style="padding-top: 5px;padding-bottom: 5px;">: <b>{{ $peserta->nama_pegawai }} / {{ $peserta->nip_pegawai }}</b></td>
        </tr>
        <tr>
            <td style="padding-top: 5px;padding-bottom: 5px;padding-left: 30px;" width="200">Sebutan Jabatan</td>
            <td style="padding-top: 5px;padding-bottom: 5px;">: {{ $jabatan->sebutan_jabatan }}</td>
        </tr>
        <tr>
            <td style="padding-top: 5px;padding-bottom: 5px;padding-left: 30px;">Organisasi</td>
            <td style="padding-top: 5px;padding-bottom: 5px;">: {{ $jabatan->organisasi }}</td>
        </tr>
        <tr>
            <td style="padding-top: 5px;padding-bottom: 5px;padding-left: 30px;">Jenjang Jabatan</td>
            <td style="padding-top: 5px;padding-bottom: 5px;">: {{ $jabatan->jenjang_jabatan }}</td>
        </tr>
        <tr>
            <td style="padding-top: 5px;padding-bottom: 5px;padding-left: 30px;">Stream Business</td>
            <td style="padding-top: 5px;padding-bottom: 5px;">: {{ $jabatan->stream_business }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>

<div class="card">

    <h5 class="card-header">Prioritas Pengembangan</h5>
    <div class="card-block">
        <table class="table table-hover">
            @php
                $x=1;
            @endphp
            <thead>
            <tr>
                <th>No</th>
                <th>Judul Kompetensi</th>
                <th>Gap KKJ</th>
                <th>Usulan Pengembangan</th>
                <th width='50'>Prioritas</th>
                <th width='100'></th>
            </tr>
            </thead>
            <tbody>
            @foreach($jabatan->kompetensi()->orderBy('kode_kompetensi','asc')->get() as $kompetensi)
            @php
                $assessment_pegawai = $peserta->getPenilaianPegawai(@$kompetensi->kompetensi->kode);
            @endphp
            <tr>
                <td>{{ $x++ }}</td>
                <td>
                    {{ @$kompetensi->kompetensi->judul_kompetensi }}
                    <br>
                    <small class="text-muted"><i>{{ @$kompetensi->kompetensi->judul_en }}</i></small>
                </td>
                <td align="center">
                    @if($assessment_pegawai!=null)
                        @if($assessment_pegawai->gap_level<0)
                            <span class="text-danger"><b>{{ @$assessment_pegawai->gap_level }}</b></span>
                        @elseif($assessment_pegawai->gap_level>0)
                            +{{ @$assessment_pegawai->gap_level }}
                        @else
                            {{ @$assessment_pegawai->gap_level }}
                        @endif
                    @endif
                    {{-- {!! ($assessment_pegawai!=null)?($assessment_pegawai->gap_level<0)?'<span class="text-danger"><b>'.@$assessment_pegawai->gap_level.'</b></span>':@$assessment_pegawai->gap_level:'' !!} --}}
                </td>
                <td>{{ ($assessment_pegawai!=null)?@$assessment_pegawai->usulan_pengembangan:'' }}</td>
                <td style="text-align: center; vertical-align:middle;">
                    @if($assessment_pegawai!=null && $assessment_pegawai->prioritas==1)
                        <i class="fa fa-check-circle text-success" style="font-size: 24px;"></i>
                    @endif
                </td>
                <td style="text-align: center; vertical-align:middle;">
                    @if($assessment_pegawai!=null)
                        <a href="{{ url('self-assessment/set-prioritas-pengembangan/'.$assessment_pegawai->id) }}" class="btn btn-success waves-effect waves-light">Set Prioritas</a>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    
</div>
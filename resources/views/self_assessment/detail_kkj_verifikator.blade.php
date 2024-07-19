<div>
    <table border="0" width="100%">
        <tr>
            <td style="padding-top: 5px;padding-bottom: 5px;" width="150" rowspan="6"> <img src="{{ app_user_avatar($peserta->nip_pegawai) }}" alt="user" class="img-fluid img-thumbnail" > </td>
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
            <td style="padding-top: 5px;padding-bottom: 5px;padding-left: 30px;">Periode</td>
            <td style="padding-top: 5px;padding-bottom: 5px;">: {{ $bulan_periode }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</div>

<div class="card">

    <ul class="nav nav-tabs m-b-10" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="kompetensi-tab" data-toggle="tab" href="#kompetensi"
               role="tab" aria-controls="kompetensi" aria-expanded="true" style="font-weight: bold;">Kompetensi Bidang</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pengembangan-tab" data-toggle="tab" href="#pengembangan"
               role="tab" aria-controls="pengembangan" style="font-weight: bold;">Usulan Pengembangan</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" id="hardskill-tab" data-toggle="tab" href="#hardskill"
               role="tab" aria-controls="hardskill" style="font-weight: bold;">Hardskill</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" id="dahan-tab" data-toggle="tab" href="#dahan"
               role="tab" aria-controls="dahan" style="font-weight: bold;">Dahan Profesi</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" id="skor-profesi-tab" data-toggle="tab" href="#skor-profesi"
               role="tab" aria-controls="skor-profesi" style="font-weight: bold;">Skor Profesi</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" id="skor-dahan-tab" data-toggle="tab" href="#skor-dahan"
               role="tab" aria-controls="skor-dahan" style="font-weight: bold;">Skor Dahan Profesi</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div role="tabpanel" class="tab-pane fade in active" id="kompetensi"
             aria-labelledby="kompetensi-tab">
            
            <div class="card-block">
                <table class="table table-hover">
                    @php
                        $x=1;
                    @endphp
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Kompetensi</th>
                        <th>KKJ</th>
                        <th>Hasil Pengukuran</th>
                        <th>Gap</th>
                        <th>Eviden</th>
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
                            {{-- <b>{{ @$kompetensi->kompetensi->kode }}</b> --}}
                            {{ @$kompetensi->kompetensi->judul_kompetensi }}
                            <br>
                            <small class="text-muted"><i>{{ @$kompetensi->kompetensi->judul_en }}</i></small>
                        </td>
                        <td align="center">{{ @$kompetensi->level }}</td>
                        <td align="center">{{ ($assessment_pegawai!=null)?@$assessment_pegawai->level_final:'' }}</td>
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
                        <td>@if(@$assessment_pegawai->evidence!=null)<a href="{{ asset('self-assessment/'.@$assessment_pegawai->evidence) }}" target="blank"><i class="fa fa-download"></i> Download</a>@endif</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
             
        </div>
        <div class="tab-pane fade" id="pengembangan" role="tabpanel"
             aria-labelledby="pengembangan-tab">
            
            <div class="card-block">
                <table class="table table-hover">
                    @php
                        $x=1;
                    @endphp
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Kompetensi</th>
                        <th>Usulan Pengembangan</th>
                        <th width='100'>Prioritas</th>
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
                        <td>{{ ($assessment_pegawai!=null)?@$assessment_pegawai->usulan_pengembangan:'' }}</td>
                        <td>
                            @if($assessment_pegawai!=null && $assessment_pegawai->prioritas==1)
                                <i class="fa fa-check-circle text-success" style="font-size: 24px;"></i>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade" id="hardskill" role="tabpanel"
             aria-labelledby="hardskill-tab">
            
            <div class="card-block">
                <h6 class="header">Hardskill yang paling dikuasai :</h6>
                {{-- <h5 class="card-text">Hardskill yang paling dikuasai</h5> --}}
                <div style="margin: 20px;">
                    {{-- <textarea class="form-control" name="hardskill_read_{{ @$peserta->id }}" id="hardskill_read_{{ @$peserta->id }}" rows="3" readonly>{{ @$peserta->hardskill }}</textarea> --}}
                    <pre>{{ @$peserta->hardskill }}</pre>
                </div>
            </div>
            
        </div>
        <div class="tab-pane fade" id="dahan" role="tabpanel"
             aria-labelledby="dahan-tab">
            
            <div class="card-block">
                <table class="table table-hover">
                    @php
                        $x=1;
                    @endphp
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Kompetensi</th>
                        <th>Profesi</th>
                        <th>Dahan Profesi</th>
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
                        <td>
                            {{ @$kompetensi->kompetensi->profesi }}
                        </td>
                        <td>
                            {{ @$kompetensi->kompetensi->dahan_profesi }}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade" id="skor-profesi" role="tabpanel"
             aria-labelledby="skor-profesi-tab">
            
            <div class="card-block">
                <table class="table table-hover">
                    @php
                        $x=1;
                    @endphp
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Profesi</th>
                        <th>Hasil Pengukuran</th>
                        <th>KKJ</th>
                        <th>Skor</th>
                        <th>Gap</th>
                        <th>Rekomendasi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list_profesi as $profesi)
                    <tr>
                        <td>{{ $x++ }}</td>
                        <td>
                            {{ @$profesi->profesi }}
                        </td>
                        <td>
                            {{ @$total_nilai_profesi[$profesi->profesi_id] }}
                        </td>
                        <td>
                            {{ @$total_nilai_kkj_profesi[$profesi->profesi_id] }}
                        </td>
                        <td>
                            {{ number_format(@$skor_profesi[$profesi->profesi_id],2,',','.') }}
                        </td>
                        <td>
                            {{ number_format(@$skor_gap_profesi[$profesi->profesi_id],2,',','.') }}
                        </td>
                        <td>
                            @if(@$rekomendasi_profesi[$profesi->profesi_id] == 'Exceed')
                                <span class="label label-success" style="font-size: 12px;">{{ @$rekomendasi_profesi[$profesi->profesi_id] }}</span>
                            @elseif(@$rekomendasi_profesi[$profesi->profesi_id] == 'Meet')
                                <span class="label label-primary" style="font-size: 12px;">{{ @$rekomendasi_profesi[$profesi->profesi_id] }}</span>
                            @elseif(@$rekomendasi_profesi[$profesi->profesi_id] == 'Below')
                                <span class="label label-danger" style="font-size: 12px;">{{ @$rekomendasi_profesi[$profesi->profesi_id] }}</span>
                            @else
                                <span class="label label-danger" style="font-size: 12px;">{{ @$rekomendasi_profesi[$profesi->profesi_id] }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade" id="skor-dahan" role="tabpanel"
             aria-labelledby="skor-dahan-tab">
            
            <div class="card-block">
                <table class="table table-hover">
                    @php
                        $x=1;
                    @endphp
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Dahan Profesi</th>
                        <th>Hasil Pengukuran</th>
                        <th>KKJ</th>
                        <th>Skor</th>
                        <th>Gap</th>
                        <th>Rekomendasi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list_dahan as $dahan)
                    <tr>
                        <td>{{ $x++ }}</td>
                        <td>
                            {{ @$dahan->dahan_profesi }}
                        </td>
                        <td>
                            {{ @$total_nilai_dahan[$dahan->dahan_profesi_id] }}
                        </td>
                        <td>
                            {{ @$total_nilai_kkj_dahan[$dahan->dahan_profesi_id] }}
                        </td>
                        <td>
                            {{ number_format(@$skor_dahan[$dahan->dahan_profesi_id],2,',','.') }}
                        </td>
                        <td>
                            {{ number_format(@$skor_gap_dahan[$dahan->dahan_profesi_id],2,',','.') }}
                        </td>
                        <td>
                            @if(@$rekomendasi_dahan[$dahan->dahan_profesi_id] == 'Exceed')
                                <span class="label label-success" style="font-size: 12px;">{{ @$rekomendasi_dahan[$dahan->dahan_profesi_id] }}</span>
                            @elseif(@$rekomendasi_dahan[$dahan->dahan_profesi_id] == 'Meet')
                                <span class="label label-primary" style="font-size: 12px;">{{ @$rekomendasi_dahan[$dahan->dahan_profesi_id] }}</span>
                            @elseif(@$rekomendasi_dahan[$dahan->dahan_profesi_id] == 'Below')
                                <span class="label label-danger" style="font-size: 12px;">{{ @$rekomendasi_dahan[$dahan->dahan_profesi_id] }}</span>
                            @else
                                <span class="label label-danger" style="font-size: 12px;">{{ @$rekomendasi_dahan[$dahan->dahan_profesi_id] }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        
    </div>
    
</div>
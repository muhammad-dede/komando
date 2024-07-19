{!! Form::open(['url'=>'self-assessment/update-kompetensi', 'files'=>true]) !!}
{!! Form::hidden('peserta_id',@$peserta->id,['id'=>'peserta_id']) !!}
{!! Form::hidden('assessment_id',@$assessment->id,['id'=>'assessment_id']) !!}
{!! Form::hidden('kode_kompetensi',@$kompetensi->kode,['id'=>'kode_kompetensi']) !!}
<div class="card">
    <h5 class="card-header">{{ $kompetensi->judul_kompetensi }} &nbsp;&nbsp;&nbsp; ({{ $kompetensi->kode }})</h5>
    <div class="card-block" id="block_kompetensi">
        <div>
            <table border="0" width="100%">
                <tr>
                    <td style="padding: 5px;"><b>{{ $kompetensi->judul_kompetensi }}</b> / <b><i>{{ $kompetensi->judul_en }}</i></b></td>
                </tr>
                <tr>
                    <td style="padding: 5px;">{{ $kompetensi->deskripsi }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
        <div class="alert alert-info alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <strong>Petunjuk :</strong> Silakan pilih level profisiensi kompetensi yang sesuai dengan kondisi Anda sekarang.
        </div>
        <table class="table table-hover" id="tabel_kompetensi">
            <thead>
                <tr>
                    <th width="150">Level</th>
                    <th>Deskripsi</th>
                    <th>Pilih</th>
                </tr>
            </thead>
            <tbody>
                @php
                 //dd($assessment->tanggal_approve)   
                @endphp
                @foreach($kompetensi->levelKompetensi()->orderBy('level','asc')->get() as $level)

                <tr @if($peserta->status_assessment!="Disetujui")onmouseover="this.style.cursor='pointer'" onclick="$('#level_{{ $level->id }}').prop('checked', true)"@endif id="tr_kompetensi_{{ $level->level }}">
                    <td align="center" style="text-align: center;vertical-align: middle;">
                        {{-- <div id="div_kompetensi_{{ $level->level }}"></div> --}}
                        {{-- <b>Level {{ $level->level }}</b> --}}
                        <div>
                            <span class="label label-success" style="font-weight: bold; font-size:25px;" title="Level {{ $level->level }}"><small>Lv.</small>{{ $level->level }}</span>
                        </div>

                        <div id="level_update_{{ $level->level }}" class="rating-sm" style="margin-top: 10px;"></div>
                        {{-- @push('skrip') --}}
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#level_update_{{ $level->level }}').raty({
                                    readOnly: true,
                                    number: {{ $jml_level }},
                                    score:  {{ $level->level }},
                                    starOff: 'fa fa-star-o text-muted',
                                    starOn: 'fa fa-star text-danger',
                                    hints: [{!! $hint !!}]
                                });
                            });
                            </script>
                        {{-- @endpush --}}
                    </td>
                    <td>
                        <p>{{ $level->perilaku }}</p>
                        <p>Contoh : {{ $level->contoh }}</p>
                    </td>
                    <td align="center" style="text-align: center;vertical-align: middle;">
                        <div class="radio radio-single radio-primary">
                            @if($assessment!=null && $score==0)
                                <input type="radio" id="level_{{ $level->id }}" value="{{ $level->level }}" name="level" aria-label="Level {{ $level->level }}" {{ ($assessment->level_final==$level->level)?'checked':'' }} {{ ($peserta->status_assessment=="Disetujui")?'disabled':'' }}>
                            @else
                                <input type="radio" id="level_{{ $level->id }}" value="{{ $level->level }}" name="level" aria-label="Level {{ $level->level }}" {{ ($score==$level->level)?'checked':'' }} {{ ($peserta->status_assessment=="Disetujui")?'disabled':'' }}>
                            @endif
                            <label></label>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card-block">
    <h4 class="card-text">Evidence</h4>
    @if($peserta->status_assessment!="Disetujui")
    <div>
        <small class="text-muted">Ukuran file maksimal 2MB.</small>
        {!! Form::file('evidence', ['class'=>'form-control', 'id'=>'evidence']) !!}
        
    </div>
    @endif
    @if($assessment!=null && $assessment->evidence!=null)
    <div class="m-t-10">
        <a href="{{ asset('self-assessment/'.$assessment->evidence) }}" target="blank"><i class="fa fa-download"></i> {{ @$assessment->evidence }}</a>
    </div>
    @elseif($assessment!=null && $assessment->evidence==null)
    <div class="m-t-10">
        No evidence
    </div>
    @endif
</div>
<div class="card-block">
    <h4 class="card-text">Usulan Pengembangan</h4>
    <small><b>Contoh:</b> Sertifikasi, Diklat, Penugasan, Magang. Masukkan hanya 1 usulan pengembangan.  <b>Info:</b> <a href="https://digilearn.pln.co.id" target="blank" style="font-weight: bold">Klik di sini</a> untuk mengakses Digital Learning PLN (digilearn.pln.co.id) </small>
    <div>
        <input type="text" class="form-control" name="usulan_pengembangan" id="usulan_pengembangan" {{ ($peserta->status_assessment=="Disetujui")?'readonly':'' }} value="{{ @$assessment->usulan_pengembangan }}">
    </div>
</div>
<div class="card-block">
    <h4 class="card-text">Keterangan</h4>
    <div>
        <textarea class="form-control" name="keterangan" id="keterangan" rows="3" {{ ($peserta->status_assessment=="Disetujui")?'readonly':'' }}>{{ @$assessment->keterangan }}</textarea>
    </div>
</div>

<div class="modal-footer m-t-20">
    @if($peserta->status_assessment!="Disetujui")
    <button type="submit" class="btn btn-success waves-effect waves-light"><i
                class="fa fa-save"></i>
        Update
    </button>
    @endif
    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"><i
                class="fa fa-times"></i> Close
    </button>
</div>
{!! Form::close() !!}
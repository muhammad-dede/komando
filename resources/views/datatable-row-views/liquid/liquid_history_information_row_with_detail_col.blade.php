<tr>
    <td id="unit">
        {{ $data['atasan_snapshot']['business_area'] }}
        -
        {{ \App\Models\Liquid\BusinessArea::where('business_area', $data['atasan_snapshot']['business_area'])->value('description') }}
    </td>
    <td id="foto" class="align-center">
        <img class="img-fluid radius-full align-center img-thumbnail img-50"
             src="{{ app_user_avatar($data['atasan_snapshot']['nip']) }}" alt="">
    </td>
    <td id="nama_atasan">
        {{ $data['atasan_snapshot']['nama'] }}
        @if($data['force_pengukuran_kedua'])
            &nbsp;
            <i class="fa fa-info-circle"
               data-toggle="tooltip"
               data-placement="right"
               title="Peserta dapat melakukan pengukuran kedua"></i>
        @endif
    </td>
    <td id="nip">
        {{ $data['atasan_snapshot']['nip'] }}
    </td>
    <td id="jabatan">
        {{ $data['atasan_snapshot']['jabatan'] }}
    </td>
    <td id="jumlah_bawahan">
        <a data-toggle="modal" data-target="#modal-bawahan{{ $data['atasan_snapshot']['nip'] }}" class="btn btn-sm btn-info">
            {{ $data['peserta_count'] }} <em class="fa fa-search"></em>
        </a>
    </td>
    <td id="feedback_bawahan">
        <a data-toggle="modal" data-target="#feedback{{ $data['atasan_snapshot']['nip'] }}" class="btn btn-sm btn-info">
            {{ $data['feedback_count']. '/' .$data['peserta_count'] }} <em class="fa fa-search"></em>
        </a>
    </td>
    <td id="penyelarasan">
        @if ($data['has_penyelarasan'] === true)
            <span class="badge badge-success" data-toggle="tooltip" title="Done">Done</span>
        @else
            <span class="badge badge-danger" data-toggle="tooltip" title="Not Yet">Not Yet</span>
        @endif
    </td>
    <td id="pengukuran_pertama">
        <a data-toggle="modal" data-target="#pengukuran-satu{{ $data['atasan_snapshot']['nip'] }}" class="btn btn-sm btn-info">
            {{ $data['pengukuran_pertama_count']. '/' .$data['peserta_count'] }} <em class="fa fa-search"></em>
        </a>
    </td>
    <td id="act_log">
        <a data-toggle="modal" data-target="#modal-activity-log{{ $data['atasan_snapshot']['nip'] }}" class="btn btn-sm btn-info">
            {{ count($data['activity_log_book']) }} <em class="fa fa-search"></em>
        </a>
    </td>
    <td id="pengukuran_kedua">
        <a data-toggle="modal" data-target="#pengukuran-dua{{ $data['atasan_snapshot']['nip'] }}" class="btn btn-sm btn-info">
            {{ $data['pengukuran_kedua_count']. '/' .$data['peserta_count'] }} <em class="fa fa-search"></em>
        </a>
    </td>
    <td id="activate_pengukuran_kedua">
        @if($data['liquid_status'] != \App\Enum\LiquidStatus::PENGUKURAN_KEDUA)
            <form method="POST" action="{{ route('pengukuran-kedua.toggle', [$data['liquid_id'], $data['pernr']]) }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                @if($data['force_pengukuran_kedua'])
                    <input type="hidden" name="force_pengukuran_kedua" value="0">
                    <button type="submit" class="btn btn-warning-outline btn-xs"
                            onclick="return confirm('Apakah Anda yakin ingin menutup pengukuran kedua untuk atasan ini?')">
                        Tutup Pengukuran
                    </button>
                @else
                    <input type="hidden" name="force_pengukuran_kedua" value="1">
                    <button type="submit" class="btn btn-primary-outline btn-xs"
                            onclick="return confirm('Apakah Anda yakin ingin membuka pengukuran kedua untuk atasan ini?')">
                        Buka Pengukuran
                    </button>
                @endif
            </form>
        @endif
    </td>
    <td id="jadwal_current">
        <span class="badge badge-primary" data-toggle="tooltip" title="Sedang Mengisi Feedback">
            {{ $data['liquid_status'] }}
        </span>
    </td>
    <td id="valid_dibawah_3">{{ ($data['peserta_count'] < 3) ? 'false' : 'true' }}</td>
    <td align="center" id="detail_view">
        <a href="{{ url('dashboard-admin/history-penilaian/show'.'/'.$data['liquid_id'].'/'.$data['pernr']) }}"
        class="badge badge-primary"
        title="Lihat Detail">
            <em class="fa fa-eye fa-2x"></em>
        </a>
    </td>
</tr>

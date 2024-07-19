<tr>
    <td id="unit">
        {{ $data['atasan_snapshot']['business_area'] }}
        -
        {{ \App\Models\Liquid\BusinessArea::where('business_area', $data['atasan_snapshot']['business_area'])->value('description') }}
    </td>
    <td id="foto" style="text-align: center">
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
    <td id="jumlah_bawahan" style="text-align: center" class="color-blue-link" data-toggle="modal" data-target="#modal-bawahan{{ $data['atasan_snapshot']['nip'] }}">
        <a data-toggle="modal"
           data-target="#modal-bawahan{{ $data['atasan_snapshot']['nip'] }}">
            {{ $data['peserta_count'] }}
        </a>
    </td>
</tr>

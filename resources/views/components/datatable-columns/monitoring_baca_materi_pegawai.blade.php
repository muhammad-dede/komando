@php

@endphp
<tr>
    <td id="nama">{{ $data->name }}</td>
    <td id="nip">{{ $data->nip }}</td>
    <td id="unit">{{ $data->business_area }} - {{ @$data->businessArea->description }}</td>
    <td id="bidang">{{ $data->bidang }}</td>
    <td id="jabatan">{{ $data->jabatan }}</td>
    <td id="baca_materi">{{ @$data->hasReadMateri($materi_id)->tanggal_jam }}</td>
</tr>

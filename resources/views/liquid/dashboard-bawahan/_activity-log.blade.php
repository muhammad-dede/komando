<div class="row m-t-20">
    <div class="col-md-12">
        <div class="card-box">
            <div class="title-top mar-b-1rem">Activity Log Book Atasan</div>
            <table id="table-activity" class="table table-striped table-bordered">
                <thead class="thead-blue">
                <tr>
                    <th class="color-white vertical-middle" style="min-width: 170px;">Nama</th>
                    <th class="color-white vertical-middle">Nip</th>
                    <th class="color-white vertical-middle">Jabatan</th>
                    <th class="color-white vertical-middle">Unit</th>
                    <th class="color-white vertical-middle" style="width: 80px;">Nama Kegiatan/Perbaikan</th>
                    <th class="color-white vertical-middle" style="min-width: 120px;">Tanggal Pelaksanaan</th>
                    <th class="color-white vertical-middle">Tempat Pelaksanaan</th>
                    <th class="color-white vertical-middle">Deskripsi Kegiatan</th>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ array_get($log->snapshot_atasan, 'nama') }}</td>
                        <td>{{ array_get($log->snapshot_atasan, 'nip') }}</td>
                        <td>{{ array_get($log->snapshot_atasan, 'jabatan') }}</td>
                        <td>{{ array_get($log->snapshot_atasan, 'unit.code') }} - {{ array_get($log->snapshot_atasan, 'unit.name') }}</td>
                        <td>{{ $log->nama_kegiatan }}</td>
                        <td>
                            <div> <strong>{{ \Carbon\Carbon::parse($log->start_date)->format('d-m-Y') }}</strong> </div>
                            s/d
                            <div> <strong>{{ \Carbon\Carbon::parse($log->end_date)->format('d-m-Y') }}</strong> </div>
                        </td>
                        <td>{{ $log->tempat_kegiatan }}</td>
                        <td>{!! $log->keterangan !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

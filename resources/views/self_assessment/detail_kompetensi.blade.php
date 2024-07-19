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

<div class="card">
    <h5 class="card-header">{{ $kompetensi->kode }}</h5>
    <div class="card-block">
        <div class="alert alert-warning alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <strong>Petunjuk :</strong> Silakan pilih level kompetensi yang sesuai dengan kondisi Anda sekarang.
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Deskripsi</th>
                    {{-- <th>Pilih</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($kompetensi->levelKompetensi()->orderBy('level','asc')->get() as $level)
                <tr>
                    <td>{{ $level->level }}</td>
                    <td>
                        <p>{{ $level->perilaku }}</p>
                        <p>Contoh : {{ $level->contoh }}</p>
                    </td>
                    {{-- <td>
                        <input class="form-control" type="radio" name="level" value="1">
                    </td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
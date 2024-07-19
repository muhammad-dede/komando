<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="5" style="text-align: center;"><h1><b>ATASAN YANG MEMILIKI BAWAHAN DIBAWAH 3</b></h1></th>
            </tr>
            <th></th>
            <tr>
                <th align="center" style="text-align:center;border:1px solid #000000;background:#7d7d7d;color:#FFFFFF;">No</th>
                <th align="center" style="text-align:center;border:1px solid #000000;background:#7d7d7d;color:#FFFFFF;">Nama</th>
                <th align="center" style="text-align:center;border:1px solid #000000;background:#7d7d7d;color:#FFFFFF;">NIP</th>
                <th align="center" style="text-align:center;border:1px solid #000000;background:#7d7d7d;color:#FFFFFF;">Jabatan</th>
                <th align="center" style="text-align:center;border:1px solid #000000;background:#7d7d7d;color:#FFFFFF;">Bawahan</th>
            </tr>
        </thead>
        <tbody>
        @php
            $no=1;
        @endphp
        @if(!empty($listPeserta))
            @foreach($listPeserta as $jabatanAtasan => $listAtasan)
                @foreach(collect($listAtasan)->sortBy('nama') as $atasan)
                    <tr>
                        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ $no++ }}</td>
                        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ $atasan['nama'] }}</td>
                        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ $atasan['nip'] }}</td>
                        <td valign="middle" style="text-align:center;border:1px solid #000000;">{{ trans(sprintf('enum.%s.%s', \App\Enum\LiquidJabatan::class, $jabatanAtasan ?: 'uncategorized')) }}</td>
                        <td valign="left" style="text-align:left;border:1px solid #000000;">
                            @foreach($atasan['peserta'] as $jabatanBawahan => $listBawahan)
                                @php $noBawahan = 0; @endphp
                                @foreach(collect($listBawahan)->sortBy('nama') as $bawahan)
                                    @php $noBawahan++; @endphp
                                    {{ $noBawahan.". ".$bawahan['nama']." / ".$bawahan['nip']." / ".$bawahan['jabatan']." \n" }}<br>
                                @endforeach
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @else
            <tr>
                <td colspan="5" align="left" style="border:solid;">Tidak ada Atasan yang memiliki bawahaan dibawah 3</td>
            </tr>
        @endif
        </tbody>
    </table>
</body>
</html>

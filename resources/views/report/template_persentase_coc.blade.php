<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table>
    <tr>
        <td></td>
        <td><h4>PT PLN (PERSERO)</h4></td>
    </tr>
    <tr>
        <td></td>
        <td><h4>{{$cc_selected->description}}</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>{{$tgl_awal->format('d F Y')}} - {{$tgl_akhir->format('d F Y')}}</td>
    </tr>
    <tr>
        <td></td>
        <td>Persentase CoC {{$jenis_coc}}</td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">NIP</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">ADMIN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">UNIT</th>
        @if($cc_selected->company_code == '1000')
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">DIVISI</th>
        @endif
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH COC OPEN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH COC</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH RENCANA PESERTA</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH DISPENSASI PESERTA</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH PESERTA</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH CHECK IN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">% CHECK IN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">JUMLAH BACA MATERI</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">% BACA MATERI</th>

    </tr>

    {{--<tbody>--}}
    <?php
    $x=1;
    $total_coc_open = 0;
    $total_coc = 0;
    $total_peserta = 0;
    $total_peserta_dispensasi = 0;
    $total_peserta_real = 0;
    $total_checkin = 0;
    $total_baca = 0;
    ?>
    @foreach($users as $user)
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$user->nip}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$user->name}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$user->business_area.' - '.$user->businessArea->description}}</td>
            @if($cc_selected->company_code == '1000')
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$user->getDivisi()}}</td>
            @endif
            <?php
            $coc_open = $user->coc()
                ->where('status', 'OPEN')
                ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
                ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
                ->where('jenis_coc_id', $jenis_coc_id)
                ->get();
            $jml_coc_open = $coc_open->count();
            ?>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$jml_coc_open}}</td>
            <?php
            $coc = $user->coc()
                    ->where('status', 'COMP')
                    ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
                    ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
                    ->where('jenis_coc_id', $jenis_coc_id)
                    ->get();
            $jml_coc = $coc->count();
            ?>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$jml_coc}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                <?php
                $jml_peserta = $coc->sum('jml_peserta');
                ?>
                {{$jml_peserta}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                <?php
                $jml_peserta_dispensasi = $coc->sum('jml_peserta_dispensasi');
                ?>
                {{$jml_peserta_dispensasi}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                <?php
                $jml_peserta_real = $jml_peserta - $jml_peserta_dispensasi;
                ?>
                {{$jml_peserta_real}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                <?php
                $jml_checkin = \App\Coc::getJumlahCheckin($user->id, $tgl_awal, $tgl_akhir, $jenis_coc_id);
                ?>
                {{$jml_checkin}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                @if($jml_peserta_real!=0)
                    {{number_format($jml_checkin/$jml_peserta_real*100,2)}}%
                @else
                    0%
                @endif
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                <?php
                $jml_baca = \App\Coc::getJumlahBacaMateri($user->id, $tgl_awal, $tgl_akhir, $jenis_coc_id);
                ?>
                {{$jml_baca}}
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                @if($jml_peserta_real!=0)
                    {{number_format($jml_baca/$jml_peserta_real*100,2)}}%
                @else
                    0%
                @endif
            </td>
        </tr>
        <?php
        $x++;
        $total_coc_open = $total_coc_open + $jml_coc_open;
        $total_coc = $total_coc + $jml_coc;
        $total_peserta = $total_peserta + $jml_peserta;
        $total_peserta_dispensasi = $total_peserta_dispensasi + $jml_peserta_dispensasi;
        $total_peserta_real = $total_peserta_real + $jml_peserta_real;
        $total_checkin = $total_checkin + $jml_checkin;
        $total_baca = $total_baca + $jml_baca;
        ?>
    @endforeach

    {{--</tbody>--}}

    {{--<tfoot>--}}
    <tr>
        <td height="25" valign="middle" style="border: 1px solid #000;" align="center"></td>
        <th height="25" valign="middle" style="border: 1px solid #000; text-align: center" ></th>
        <th height="25" valign="middle" style="border: 1px solid #000; text-align: center" ></th>
        @if($cc_selected->company_code == '1000')
        <th height="25" valign="middle" style="border: 1px solid #000; text-align: center" ></th>
        @endif
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >TOTAL</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_coc_open}}</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_coc}}</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_peserta}}</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_peserta_dispensasi}}</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_peserta_real}}</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_checkin}}</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >
            @if($total_peserta_real!=0)
                {{number_format($total_checkin/$total_peserta_real*100,2)}}%
            @else
                0%
            @endif
        </th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >{{$total_baca}}</th>
        <th height="25" valign="middle" style="border: 1px solid #000;text-align: center" >
            @if($total_peserta_real!=0)
                {{number_format($total_baca/$total_peserta_real*100,2)}}%
            @else
                0%
            @endif
        </th>
    </tr>
    {{--</tfoot>--}}
</table>
</body>
</html>

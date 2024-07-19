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
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Tema Utama</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Level Organisasi</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Jenjang Jabatan</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Judul CoC</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Narasumber</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Unit/Area/Rayon</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Target Pelaksanaan CoC</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Realisasi</th>
    </tr>
    <?php
    $x = 1;
    ?>
    @foreach($realisasi_list as $realisasi)
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x++}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$realisasi->coc->tema->tema}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{($realisasi->level!='')? 'Level '.$realisasi->level:''}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$realisasi->jenjangJabatan->jenjang_jabatan}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$realisasi->coc->judul}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$realisasi->leader->cname}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$realisasi->company_code}} - {{@$realisasi->companyCode->description}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{@$realisasi->coc->tanggal_jam->format('Y-m-d')}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{@$realisasi->realisasi->format('Y-m-d')}}</td>

            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{@$coc->pemateri->cname}}</td>--}}
            {{--<td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{@$coc->pemateri->nip}}</td>--}}
            {{--<td height="25" valign="middle"--}}
                            {{--style="border: 1px solid #000;">{{@$coc->pemateri->strukturPosisi->stext}}</td>--}}
            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{@$coc->company_code}} - {{@$coc->companyCode->description}}</td>--}}
            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{@$coc->business_area}} - {{@$coc->companyCode->description}}</td>--}}

            {{--<td height="25" valign="middle" style="border: 1px solid #000;" align="center">--}}
                {{--{{@$coc->attendants->count()}}--}}
            {{--</td>--}}
            {{--<td height="25" valign="middle"--}}
                {{--style="border: 1px solid #000;" align="center">--}}
                {{--{{@$coc->tanggal_jam->format('Y-m-d h:i')}}--}}
            {{--</td>--}}
            {{--<td height="25" valign="middle"--}}
            {{--style="border: 1px solid #000;" align="center">{{$attendant->check_in->format('Y-m-d H:i')}}</td>--}}
            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{$attendant->coc->lokasi}}</td>--}}
        </tr>
    @endforeach
</table>
</body>
</html>

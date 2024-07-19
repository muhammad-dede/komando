<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Tema</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Start Date</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">End Date</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Jumlah CoC</th>
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Judul</th>--}}
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Pemateri</th>--}}
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">NIP</th>--}}
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Jabatan</th>--}}
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Lokasi</th>--}}
        {{--<th height="25" width="30" valign="middle" align="center" style="border: 1px solid #000;">Sistem</th>--}}
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;">Waktu</th>--}}
        {{--<th height="25" width="20" valign="middle" align="center" style="border: 1px solid #000;">DMP (MW)</th>--}}
        {{--<th height="25" width="20" valign="middle" align="center" style="border: 1px solid #000;">Captive Power (MW)</th>--}}
        {{--<th height="25" width="20" valign="middle" align="center" style="border: 1px solid #000;">Beban Puncak (MW)</th>--}}
        {{--<th height="25" width="20" valign="middle" align="center" style="border: 1px solid #000;">Cadangan (MW)</th>--}}
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;">Status</th>--}}
        {{--<th height="25" width="50" valign="middle" align="center" style="border: 1px solid #000;">Unit Tidak Siap</th>--}}
    </tr>
    <?php
    $x = 1;
    ?>
    @foreach($tema_coc as $tema)
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$x++}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$tema->tema->tema}}</td>
            <td height="25" valign="middle"
                style="border: 1px solid #000;" align="center">{{$tema->start_date->format('Y-m-d')}}</td>
            <td height="25" valign="middle"
                style="border: 1px solid #000;" align="center">{{$tema->end_date->format('Y-m-d')}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$tema->tema->coc->count()}}</td>

            {{--<td><a href="{{url('coc/tema/'.$tema->id)}}">{{$tema->tema->tema}}</a></td>--}}
            {{--<td class="hidden-xs" align="center">{{$tema->start_date->format('Y-m-d')}}</td>--}}
            {{--<td class="hidden-xs" align="center">{{$tema->end_date->format('Y-m-d')}}</td>--}}
            {{--<td class="hidden-xs" align="center">{{$tema->tema->coc->count()}}</td>--}}

            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{$attendant->coc->judul}}</td>--}}
            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{@$attendant->coc->pemateri->cname}}</td>--}}
            {{--<td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{@$attendant->coc->pemateri->nip}}</td>--}}
            {{--<td height="25" valign="middle"--}}
                {{--style="border: 1px solid #000;">{{@$attendant->coc->pemateri->strukturPosisi->stext}}</td>--}}
            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{$attendant->coc->lokasi}}</td>--}}
        </tr>
        {{--<tr>--}}
        {{--<td height="25" align="center" valign="middle" style="border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;">{{$x++}}</td>--}}
        {{--<td height="25" align="center" valign="middle" style="border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;">{{$sistem->id}}</td>--}}
        {{--<td height="25" align="center" valign="middle" style="border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;">{{$sistem->jenisSistem->jenis}}</td>--}}
        {{--<td height="25" rowspan="2" valign="middle" style="border: 1px solid #000;">{{$sistem->sistem}}</td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;">Siang</td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--</tr>--}}
        {{--<tr>--}}
        {{--<td height="25" valign="middle" style="border-bottom: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;"></td>--}}
        {{--<td height="25" align="center" valign="middle" style="border-bottom: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;">{{$sistem->id}}</td>--}}
        {{--<td height="25" align="center" valign="middle" style="border-bottom: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;">{{$sistem->jenisSistem->jenis}}</td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;">Malam</td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--<td height="25" valign="middle" style="border: 1px solid #000;"></td>--}}
        {{--</tr>--}}
    @endforeach
</table>
</body>
</html>

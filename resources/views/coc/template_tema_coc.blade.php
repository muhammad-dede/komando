<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table>
    <tr>
        <td>Tema</td>
        <td><h2>{{$tema_coc->tema->tema}}</h2></td>
    </tr>
    <tr>
        <td>Periode</td>
        <td>{{$tema_coc->start_date->format('Y-m-d')}} - {{$tema_coc->end_date->format('Y-m-d')}}</td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Judul</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Penulis Materi</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">CoC Leader</th>
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">NIP</th>--}}
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Jabatan</th>--}}
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Company Code</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Business Area</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Jumlah Peserta</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Tanggal CoC</th>
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Check-In</th>--}}
        {{--<th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Lokasi</th>--}}
    </tr>
    <?php
    $x = 1;
    ?>
    @foreach($tema_coc->tema->coc()->orderBy('id', 'desc')->get() as $coc)
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x++}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$coc->judul}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$coc->materi->penulis->cname}} ({{@$coc->materi->penulis->nip}} / {{@$coc->materi->penulis->strukturPosisi->stext}})</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$coc->pemateri->cname}} ({{@$coc->pemateri->nip}} / {{@$coc->leader->strukturPosisi->stext}})</td>
            {{--<td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{@$coc->pemateri->nip}}</td>--}}
            {{--<td height="25" valign="middle"--}}
{{--                style="border: 1px solid #000;">{{@$coc->pemateri->strukturPosisi->stext}}</td>--}}
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$coc->company_code}} - {{@$coc->companyCode->description}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$coc->business_area}} - {{@$coc->businessArea->description}}</td>

            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                {{@$coc->attendants->count()}}
            </td>
            <td height="25" valign="middle"
                style="border: 1px solid #000;" align="center">
                    {{@$coc->tanggal_jam->format('Y-m-d h:i')}}
            </td>
            {{--<td height="25" valign="middle"--}}
                {{--style="border: 1px solid #000;" align="center">{{$attendant->check_in->format('Y-m-d H:i')}}</td>--}}
            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{$attendant->coc->lokasi}}</td>--}}
        </tr>
    @endforeach
</table>
</body>
</html>

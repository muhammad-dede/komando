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
        <td><h4>{{$company_code->description}}</h4></td>
    </tr>
    <tr>
        <td></td>
        <td><h4>Status : {{$status_coc}}</h4></td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Business Area</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Judul CoC</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Tema</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Jenis</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">CoC Leader</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">NIP Leader</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Jabatan Leader</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Unit/Bidang</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Lokasi</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Tanggal Jam</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Peserta</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">% Peserta</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Admin CoC</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">NIP Admin CoC</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Jabatan Admin CoC</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Status</th>
    </tr>
    <?php
    $x = 1;
    ?>
    @foreach($coc_list as $coc)
        <tr>
            <td height="35" valign="middle" style="border: 1px solid #000;" align="center">{{$x++}}</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">{{$coc->business_area}} - {{$coc->businessArea->description}}</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">{{$coc->judul}}</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">{{$coc->tema->tema}}</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">{{@$coc->jenis->jenis}}</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">@if($coc->realisasi!=null)
                    {{@$coc->realisasi->leader->cname}}
                @else
                    {{@$coc->leader->cname}}
                @endif</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">@if($coc->realisasi!=null)
                    {{@$coc->realisasi->leader->nip}}
                @else
                    {{@$coc->leader->nip}}
                @endif</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">@if($coc->realisasi!=null)
                    {{@$coc->realisasi->leader->strukturPosisi->stext}}
                @else
                    {{@$coc->leader->strukturPosisi->stext}}
                @endif</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">{{@$coc->organisasi->stext}}</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">{{@$coc->lokasi}}</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">@if(@$coc->realisasi!=null)
                    {{@$coc->realisasi->realisasi->format('Y-m-d H:i')}}
                @else
                    {{@$coc->tanggal_jam->format('Y-m-d H:i')}}
                @endif</td>
            <td height="35" valign="middle" style="border: 1px solid #000;" align="center">{{$coc->attendants->count()}}/{{($coc->jml_peserta - $coc->jml_peserta_dispensasi)}}</td>
            <td height="35" valign="middle" style="border: 1px solid #000;" align="center">{{number_format(($coc->attendants->count()/($coc->jml_peserta - $coc->jml_peserta_dispensasi))*100, 2)}}%</td>
            <td height="35" valign="middle" style="border: 1px solid #000;">
                {{@$coc->admin->name}}
            </td>
            <td height="35" valign="middle" style="border: 1px solid #000;">
                {{@$coc->admin->nip}}
            </td>
            <td height="35" valign="middle" style="border: 1px solid #000;">
                {{@$coc->admin->strukturPosisi()->stext}}
            </td>
            <td height="35" valign="middle" style="border: 1px solid #000;" align="center">@if($coc->status=='OPEN')
                    <span class="label label-success">{{$coc->status}}</span>
                @elseif($coc->status=='CANC')
                    <span class="label label-danger">{{$coc->status}}</span>
                @else
                    <span class="label label-primary">{{$coc->status}}</span>
                @endif</td>
        </tr>
    @endforeach
</table>
</body>
</html>

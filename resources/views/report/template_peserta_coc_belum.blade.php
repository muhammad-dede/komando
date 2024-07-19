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
        <td>Judul CoC</td>
        <td><h4>{{strtoupper($coc->judul)}}</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>Tanggal - Jam</td>
        <td>{{strtoupper($coc->tanggal_jam->format('d F Y'))}} - {{$coc->tanggal_jam->format('H:i')}}</td>
    </tr>
    <tr>
        <td></td>
        <td>Leader</td>
        <td>
            @if($coc->realisasi!=null)
                {{strtoupper($coc->realisasi->leader->nip.' - '.$coc->realisasi->leader->name)}}
            @else
                {{strtoupper($coc->leader->nip.' - '.$coc->leader->name)}}
            @endif
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Lokasi</td>
        <td>
                {{strtoupper($coc->lokasi)}}
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Jml Peserta Dispensasi</td>
        <td>
                {{$coc->jml_peserta_dispensasi}}
        </td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">NIP</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">NAME</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">BUSINESS AREA</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">BIDANG</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">JABATAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">STATUS</th>
    </tr>
    <?php
    $x=1;
    ?>
    @foreach($absence_list as $user)
    <tr>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;">{{$x++}}</td>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">{{@$user->nip}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{strtoupper(@$user->name)}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{@$user->business_area}} - {{strtoupper(@$user->businessArea->description)}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{@$user->bidang}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{@$user->jabatan}}</td>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;background-color: #ff4359" width="20">BELUM CHECK-IN</td>
    </tr>
    @endforeach
    
</table>
</body>
</html>

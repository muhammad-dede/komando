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
        <td>Materi CoC</td>
        <td><h4>{{strtoupper($materi->judul)}}</h4></td>
    </tr>
    <tr>
        <td></td>
        <td>Tanggal</td>
        <td>{{strtoupper($materi->tanggal->format('d F Y'))}}</td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">NAME</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">NIP</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">BUSINESS AREA</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">BIDANG</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">JABATAN</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">BACA MATERI</th>
    </tr>
    <?php
    $x=1;
    ?>
    @foreach($absence_list as $user)
    <tr>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;">{{$x++}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{strtoupper(@$arr_pegawai[$user]->name)}}</td>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">{{@$arr_pegawai[$user]->nip}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{@$arr_pegawai[$user]->business_area}} - {{strtoupper(@$arr_pegawai[$user]->businessArea->description)}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{@$arr_pegawai[$user]->bidang}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{@$arr_pegawai[$user]->jabatan}}</td>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;background-color: #ff4359" width="20">BELUM BACA</td>
    </tr>
    @endforeach
    
</table>
</body>
</html>

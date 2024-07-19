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
        {{-- <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">STATUS</th> --}}
        {{-- <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">CHECK IN</th> --}}
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">BACA MATERI</th>
    </tr>
    <?php
    $x=1;
    ?>
    @foreach($reader_list as $reader)
    <tr>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;">{{$x++}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{strtoupper(@$arr_pegawai[$reader->user_id]->name)}}</td>
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">{{@$arr_pegawai[$reader->user_id]->nip}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{@$arr_pegawai[$reader->user_id]->business_area}} - {{strtoupper(@$arr_pegawai[$reader->user_id]->businessArea->description)}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{@$arr_pegawai[$reader->user_id]->bidang}}</td>
        <td height="25" valign="middle" align="left" style="border: 1px solid #000;" width="30">{{@$arr_pegawai[$reader->user_id]->jabatan}}</td>
        {{-- <td height="25" valign="middle" align="center" style="border: 1px solid #000;background-color: #45cd5e" width="20">{{strtoupper(@$user->statusCheckin->status)}}</td> --}}
        {{-- <td height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">{{@$user->check_in->format('Y-m-d H:i')}}</td> --}}
        <td height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">{{$reader->tanggal_jam->format('Y-m-d H:i')}}</td>
    </tr>
    @endforeach
    
</table>
</body>
</html>

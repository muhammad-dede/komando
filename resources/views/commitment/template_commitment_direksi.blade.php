<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table>
    <tr>
        <td>Komitmen</td>
        <td><h4>Direksi</h4></td>
    </tr>
    <tr>
        <td>Periode</td>
        <td align="left"><h4>{{$tahun}}</h4></td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Nama Pegawai</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">NIP</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="35">Jabatan</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Tahun</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Commitment</th>
    </tr>
    <?php
    $x=1;
    ?>
    @foreach($user_list as $user)
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x++}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{strtoupper($user->name)}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$user->nip}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$user->strukturPosisi()->stext}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$tahun}}</td>
            <?php
            $commit = $user->getKomitmenTahun($tahun);
            ?>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{($commit!=null)? $commit->created_at->format('Y-m-d H:i') : ''}}</td>
        </tr>
    @endforeach
</table>
</body>
</html>

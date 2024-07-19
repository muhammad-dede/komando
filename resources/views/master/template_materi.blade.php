<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table>
    <tr>
        <td>Report</td>
        <td><h4>Materi CoC</h4></td>
    </tr>
    <tr>
        <td>Periode</td>
        <td align="left"><h4>{{$tahun}}</h4></td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="50">Judul</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Penulis</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Tema</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Jenis Materi</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Cluster Materi</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Company Code</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Business Area</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Organisasi</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="10">Like</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="10">Dislike</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="10">5 stars</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="10">4 stars</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="10">3 stars</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="10">2 stars</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="10">1 star</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="10">Review</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="10">Rate</th>
    </tr>
    <?php
    $x=1;
    ?>
    @foreach($materi_list as $materi)
        @php
            $rate = $materi->getArrJmlRate();
            $avg = $rate['avg'];
            $total_review = $rate['total'];
            $arr_rate = $rate['arr_rate'];
        @endphp
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x++}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{strtoupper($materi->judul)}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{($materi->energize_day=='1')?'Energize Day':@$materi->penulis->cname}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$materi->tema->tema}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$materi->jenisMateri->jenis}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">
                @if($materi->energize_day=='1') Energize Day
                @elseif($materi->rubrik_transformasi=='1') Rubrik Transformasi
                @elseif($materi->jenis_materi_id=='1') Nasional
                @else Local
                @endif
            </td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$materi->companyCode->description}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$materi->businessArea->description}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$materi->strukturOrganisasi->stext}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{@$materi->getJumlahLike('number')}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{@$materi->getJumlahDislike('number')}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{number_format($arr_rate[5])}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{number_format($arr_rate[4])}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{number_format($arr_rate[3])}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{number_format($arr_rate[2])}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{number_format($arr_rate[1])}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{number_format($total_review)}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{number_format($avg,1)}}</td>
        </tr>
    @endforeach
</table>
</body>
</html>

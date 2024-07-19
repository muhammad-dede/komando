<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Company Code</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Unit</th>
        @for($y=env('START_YEAR',2018);$y<=date('Y');$y++)
            <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Komitmen {{$y}}</th>
        @endfor
    </tr>
    <?php
    $x=1;
    ?>
    @foreach($ccList as $cc)
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x++}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$cc->company_code}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{$cc->description}}</td>
            <?php
                $arr_komit = $arr_komitmen[$cc->company_code];
            ?>
            @for($y=env('START_YEAR',2018);$y<=date('Y');$y++)
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{number_format($arr_komit[$y],0,',','.')}}</td>
            @endfor
        </tr>
    @endforeach
</table>
</body>
</html>

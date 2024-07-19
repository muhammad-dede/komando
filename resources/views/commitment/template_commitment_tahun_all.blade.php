<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<table>
    {{--<tr>--}}
        {{--<td>Co.Code</td>--}}
        {{--<td><h4>{{$business_area->company_code}} - {{$business_area->companyCode->description}}</h4></td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td>Bus.Area</td>--}}
        {{--<td><h4>{{$business_area->business_area}} - {{$business_area->description}}</h4></td>--}}
    {{--</tr>--}}
    <tr>
        <td>Periode</td>
        <td align="left"><h4>{{$tahun}}</h4></td>
    </tr>
</table>
<table border="1">
    <tr>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;">No</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Company Code</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Business Area</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="30">Nama Pegawai</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">NIP</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="35">Jabatan</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="40">Bidang</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Tahun</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Progress</th>
        <th height="25" valign="middle" align="center" style="border: 1px solid #000;" width="20">Commitment</th>
    </tr>
    <?php
    $x=1;
    //$jml_perilaku = App\PedomanPerilaku::all()->count();
    //$jml_perilaku = env('JML_PEDOMAN', 14);
    if($tahun<2019){
        $jml_perilaku = 18;
    }
    else{
        $jml_perilaku = env('JML_PEDOMAN', 14);
    }
    ?>
    @foreach($pegawai_list as $pegawai)
        <tr>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$x++}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{strtoupper($pegawai->bukrs.' - '.$pegawai->businessArea->companyCode->description)}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{strtoupper($pegawai->gsber.' - '.$pegawai->businessArea->description)}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;">{{strtoupper($pegawai->sname)}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{@$pegawai->pa0032->nip}}</td>
            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{@$user->strukturPosisi()->objid}} - {{@$user->strukturPosisi()->stext}}</td>--}}
{{--            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$user->strukturPosisi()->stext}}</td>--}}
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$pegawai->jabatan->strukturPosisi->stext}}</td>
            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{@$user->strukturOrganisasi()->objid}} - {{@$user->strukturOrganisasi()->stext}}</td>--}}
            <td height="25" valign="middle" style="border: 1px solid #000;">{{@$pegawai->jabatan->strukturPosisi->stxt2}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">{{$tahun}}</td>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                @if($pegawai->pa0032->user!=null)
                    <?php
                    $jml_jawaban = @$pegawai->pa0032->user->perilakuPegawaiTahun($tahun)->count();
                    $persen = ($jml_jawaban/$jml_perilaku)*100;
                    if($persen>100) $persen = 100;
                    ?>
                    {{number_format($persen,2)}}%
                @else
                    0%
                @endif
            </td>
            <?php
                if($pegawai->pa0032->user!=null){
                    $commit = $pegawai->pa0032->user->getKomitmenTahun($tahun);
                }
                else{
                    $commit = null;
                }
            ?>
            <td height="25" valign="middle" style="border: 1px solid #000;" align="center">
                @if($pegawai->pa0032->user!=null)
                    {{($commit!=null)? $commit->created_at->format('Y-m-d H:i') : ''}}
                @else
                    -
                @endif

            </td>


            {{--<td height="25" valign="middle" style="border: 1px solid #000;">{{$coc->judul}}</td>--}}
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
        </tr>
        {{--<tr>--}}
            {{--<td>{{$x++}}</td>--}}
            {{--<td>{{$user->name}}</td>--}}
            {{--<td>{{$user->ad_employee_number}}</td>--}}
            {{--<td>{{@$user->strukturPosisi()->objid}} - {{@$user->strukturPosisi()->stext}}</td>--}}
            {{--<td>{{@$user->strukturOrganisasi()->objid}} - {{@$user->strukturOrganisasi()->stext}}</td>--}}
            {{--<td align="center">{{$user->perilakuPegawai()->count()/$jml_perilaku*100}}%</td>--}}
            {{--<td>{{($user->getKomitmenTahunini()!=null)? $user->getKomitmenTahunini()->created_at->diffForHumans() : ''}}</td>--}}
        {{--</tr>--}}
    @endforeach
</table>
</body>
</html>

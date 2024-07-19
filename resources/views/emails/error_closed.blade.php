@extends('layout_email')

@section('content')
        <p><b>Kepada Yth. Bapak/Ibu {{$kepada}},</b></p>
        <br>
        <p>
            Dengan ini kami sampaikan laporan permasalahan Aplikasi KOMANDO telah selesai dengan rincian sebagai berikut:
        </p>
        <table width="800" style="margin-left: 50px;">
            <tr>
                <td width="150">ID</td>
                <td width="20">:</td>
{{--                <td><a href="{{url('/report/problem/'.$problem->id)}}"><b>{{str_pad($problem->id,10,'0',STR_PAD_LEFT)}}</b></a></td>--}}
                <td><a href="{{url('/notification/'.$notif_id)}}"><b>{{str_pad($problem->id,10,'0',STR_PAD_LEFT)}}</b></a></td>
            </tr><tr>
                {{--<td>Requested by</td>--}}
                <td>Status</td>
                <td>:</td>
                {{--                <td>{{$dari->name}} ({{$dari->ad_employee_number}})</td>--}}
                <td>{{@$status->status}}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                {{--<td>Requested by</td>--}}
                <td>Nama</td>
                <td>:</td>
{{--                <td>{{$dari->name}} ({{$dari->ad_employee_number}})</td>--}}
                <td>{{$problem->nama}}</td>
            </tr>
            <tr>
                {{--<td>Requested by</td>--}}
                <td>NIP</td>
                <td>:</td>
                {{--                <td>{{$dari->name}} ({{$dari->ad_employee_number}})</td>--}}
                <td>{{$problem->nip}}</td>
            </tr>
            <tr>
                <td>Unit</td>
                <td>:</td>
                <td>{{$problem->unit}}</td>
            </tr>
            {{--<tr>--}}
                {{--<td>Username / Email</td>--}}
                {{--<td>:</td>--}}
                {{--<td>{{$problem->username}} {{$problem->email}}</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>Kategori Masalah / Server</td>--}}
                {{--<td>:</td>--}}
                {{--<td>{{$dari->name}} ({{$dari->ad_employee_number}})</td>--}}
            {{--</tr>--}}
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td width="150">Company Code</td>
                <td width="20">:</td>
                <td>{{@$company_code->company_code}} - {{@$company_code->description}}</td>
            </tr>
            <tr>
                <td>Business Area</td>
                <td>:</td>
                <td>{{@$business_area->business_area}} - {{@$business_area->description}}</td>
            </tr>
            {{--<tr>--}}
                {{--<td>Tanggal Kejadian</td>--}}
                {{--<td>:</td>--}}
                {{--<td>{{$problem->tgl_kejadian}} </td>--}}
            {{--</tr>--}}
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Deskripsi Masalah</td>
                <td>:</td>
                <td>{!! $problem->deskripsi !!}</td>
            </tr>
            <tr>
                <td>Cause</td>
                <td>:</td>
                <td>{!! $problem->cause !!}</td>
            </tr>
            <tr>
                <td>Resolution</td>
                <td>:</td>
                <td>{!! $problem->resolution !!}</td>
            </tr>
            <tr>
                <td>Konfirmasi</td>
                <td>:</td>
                <td>{!! $problem->konfirmasi !!}</td>
            </tr>
            {{--<tr>--}}
                {{--<td width="150">Company Code</td>--}}
                {{--<td width="20">:</td>--}}
                {{--<td>{{$ae2->company_code}} - {{$ae2->companyCode->description}}</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>Business Area</td>--}}
                {{--<td>:</td>--}}
                {{--<td>{{$ae2->business_area}} - {{$ae2->businessArea->description}}</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>&nbsp;</td>--}}
                {{--<td>&nbsp;</td>--}}
                {{--<td>&nbsp;</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>Hasil Review</td>--}}
                {{--<td>:</td>--}}
                {{--<td style="color: red;"><b>Ditolak (Rejected)</b></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>Alasan</td>--}}
                {{--<td>:</td>--}}
                {{--<td>{{$alasan}}</td>--}}
            {{--</tr>--}}
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" align="center">
{{--                    <a href="{{url($notif->url)}}">--}}
                    {{--<a href="{{url('/report/problem/'.$problem->id)}}">--}}
                    <a href="{{url('/notification/'.$notif_id)}}">
                    <div style="width:100px; height:25px; font-size:large; color: #ffffff; background-color:#0d94ca; text-align: center;"><b>Detail &raquo;</b></div></a>
                    {{--<input type="button" value="Review &raquo;" onclick="window.location.href='{{url($notif->url)}}'">--}}
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <p>
            {{--<a href="{{url('/report/problem/'.$problem->id)}}">Klik di sini</a> atau klik tombol 'Detail' untuk melihat rincian permasalahan.--}}
            <a href="{{url('/notification/'.$notif_id)}}">Klik di sini</a> atau klik tombol 'Detail' untuk melihat rincian permasalahan.
        </p>
        <p>
            Demikian pemberitahuan ini kami sampaikan. Atas perhatiannya kami ucapkan terimakasih.
        </p>
        <br>
        <br>
        Salam,<br>
        <b>budaya.pln.co.id</b>
        <br>
        <br>
@stop
@extends('layout_email')

@section('content')
        <p><b>Kepada Yth. Bapak/Ibu {{$kepada}},</b></p>
        <br>
        <p>
            Dengan ini kami sampaikan laporan permasalahan Interface KOMANDO - SAP dengan rincian sebagai berikut:
        </p>
        <table width="800" style="margin-left: 50px;">
            <tr>
                <td width="150">Tanggal Interface</td>
                <td width="20">:</td>
                <td>{{$tanggal}}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Interface Log</td>
                <td>:</td>
                <td>
                    @foreach($error_msg as $msg)
                        - {{$msg}}<br>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
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
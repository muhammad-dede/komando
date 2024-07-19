@extends('layout')

@section('css')

@stop

@section('title')
    <h4 class="page-title">Dashboard Budaya</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <?php
                $url = 'https://bigdata.pln.co.id/trusted';
                $server = 'https://bigdata.pln.co.id';
                $data = array('username' => 'div.hst');
                $params = ':embed=yes&:toolbar=no';
                $views = "views/DashboardBudaya/DashboardSiteVisit?:iid=1";
                
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data)
                    ),
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_name' => false
                    ),
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                if ($result === FALSE) { }
                 
                $workbook = $url.'/'.$result.'/'.$views.'?'.$params;
                ?>
                
                <iframe src="<?php echo $workbook ?>" width="100%" height="640vm" style="background: #FFFFFF; border: 0 solid;"></iframe>                
            </div>
        </div>
    </div>
@stop

@section('javascript')

@stop
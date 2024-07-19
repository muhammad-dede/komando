<div class="row">
    <div class="col-md-2 col-lg-2 col-xs-12">
        <img src="{{ app_user_avatar($detail['atasan']['nip']) }}"
             class="img-thumbnail img-fluid img-responsive" alt="">
    </div>
    <div class="col-md-10 col-lg-10 col-xs-12">
        <table class="table-penilaian table table-striped">
            <tr>
                <td>Nama</td>
                <td width="10px">:</td>
                <td>{{ $detail['atasan']['nama'] }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $detail['atasan']['nip'] }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $detail['atasan']['jabatan'] }}</td>
            </tr>
            <tr>
                <td>Unit</td>
                <td>:</td>
                <td>{{ $detail['unit']->business_area }} - {{ $detail['unit']->description }}</td>
            </tr>
		</table>
		
		@include('liquid.dashboard-admin._tracking')
    </div>
</div>

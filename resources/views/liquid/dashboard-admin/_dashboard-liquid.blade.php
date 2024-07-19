@push('styles')
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .select2 {
            width: 100% !important;
        }

		.row {
			justify-content: center;
		}
    </style>
@endpush

@php
    $informations = $liquidService->getGeneralInformation(
        request('unit_code', $user->business_area),
        request('divisi', $user->getKodeDivisiPusat()),
        $params
    );
@endphp
<div class="row dashboard-admin-top">
    <div class="col-sm-3 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="angka">{{ $informations['atasans'] }}</div>
                        <div class="sub-title">Jumlah Atasan</div>
                    </div>
                    <div class="col-md-6 align-center">
                        <img class="height-full" src="{{ asset('assets/images/card-acuan.svg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
	</div>
	<div class="col-sm-3 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="angka">{{ $informations['bawahans'] }}</div>
                        <div class="sub-title">Jumlah Bawahan</div>
                    </div>
                    <div class="col-md-6 align-center">
                        <img class="height-full" src="{{ asset('assets/images/card-bawahan.svg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="angka">{{ $informations['persentase_feedback'] }}%</div>
                        <div class="sub-title">Persentase Feedback</div>
                    </div>
                    <div class="col-md-6 align-center">
                        <img class="height-full" src="{{ asset('assets/images/card-liquid.svg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<div class="row dashboard-admin-top">
	<div class="col-sm-3 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="angka">{{ $informations['persentase_penyelarasan'] }}%</div>
                        <div class="sub-title">Persentase Penyelarasan</div>
                    </div>
                    <div class="col-md-6 align-center">
                        <img class="height-full" src="{{ asset('assets/images/card-liquid.svg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <span class="angka">{{ $informations['persentase_pengukuran_pertama'] }}%</span>
                        <div class="sub-title">Persentase Pengukuran I</div>
                    </div>
                    <div class="col-md-6 align-center">
                        <img class="height-full" src="{{ asset('assets/images/card-reminder.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
	</div>
	<div class="col-sm-3 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <span class="angka">{{ $informations['persentase_pengukuran_kedua'] }}%</span>
                        <div class="sub-title">Persentase Pengukuran II</div>
                    </div>
                    <div class="col-md-6 align-center">
                        <img class="height-full" src="{{ asset('assets/images/card-reminder.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>


@push('scripts')
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();
        });
    </script>
@endpush

<div class="card-box">
    <div class="row">
        <div class="col-md-6 align-center">
            <button class="btn btn-blue-large">Hasil Feedback {!! $kelebihan !!}</button>
            <span class="badge badge-success align-center mar-1rem">
				<img src="{{ asset('assets/images/mdi_vote.png') }}" alt="">
			</span> Jumlah Bawahan : {{
				$dataChart['voter']
					.'/'.$dataChart['sum_all_voter']
			}}

            <div class="card">
                <div class="card-body">
                    @include('liquid.dashboard-atasan._chart-feedback-kelebihan')
                </div>
            </div>
        </div>
        <div class="col-md-6 align-center">
            <button class="btn btn-blue-large bg-red">Hasil Feedback {!! $kekurangan !!}</button>
            <span class="badge badge-success align-center mar-1rem">
				<img src="{{ asset('assets/images/mdi_vote.png') }}" alt="">
			</span> Jumlah Bawahan : {{
				$dataChart['voter']
					.'/'.$dataChart['sum_all_voter']
			}}

            <div class="card">
                <div class="card-body">
                    @include('liquid.dashboard-atasan._chart-feedback-kekurangan')
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('vendor/chartjs/dist/Chart.bundle.min.js') }}"></script>

@endpush

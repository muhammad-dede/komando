<div class="card-box">
    <ul class="nav nav-tabs comp-tab" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#saran" role="tab"  aria-selected="true">{!! $saran !!}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#harapan" role="tab"  aria-selected="false">Harapan</a>
        </li>
    </ul>
    <div class="tab-content comp-tab-content" id="myTabContent">
        <div class="tab-pane fade show active in" id="saran" role="tabpanel">
			@if (isset($feedbacks))
				@foreach ($feedbacks as $feedback)
					<div class="row">
						<div class="col-md-1">
							<img class="img img-circle img-thumbnail" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="">
						</div>
						<div class="col-md-11">
							<span class="btn btn-blue-sm">Anonymous</span> <span class="day">
								{{ \Carbon\Carbon::parse($feedback['created_at'])->diffForHumans() }}
							</span>
							<div class="desc">
								{!! $feedback['saran'] !!}
							</div>
						</div>
					</div>
				@endforeach
			@else
				<h4>Belum ada daftar saran dari bawahan.</h4>
			@endif
        </div>
        <div class="tab-pane fade" id="harapan" role="tabpanel">
			@if (isset($feedbacks))
				@foreach ($feedbacks as $feedback)
					<div class="row">
						<div class="col-md-1">
							<img class="img img-circle img-thumbnail" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="">
						</div>
						<div class="col-md-11">
							<span class="btn btn-blue-sm">Anonymous</span> <span class="day">
								{{ \Carbon\Carbon::parse($feedback['created_at'])->diffForHumans() }}
							</span>
							<div class="desc">
								{!! $feedback['harapan'] !!}
							</div>
						</div>
					</div>
				@endforeach
			@else
				<h4>Belum ada daftar harapan dari bawahan.</h4>
			@endif
        </div>
    </div>
</div>

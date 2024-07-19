@foreach ($detail['saran'] as $saran)
	<div class="row mar-b-1rem">
		<div class="col-md-1 col-xs-12">
			<img src="{{ app_user_avatar("anonim") }}" class="img-circle img-fluid" alt="">
		</div>
		<div class="col-md-11 col-xs-12">
			<span class="title-blue display-unset">Anonim </span> <span class="date">&nbsp;</span>
			<div class="desc mar-t-1rem">
				{!! $saran !!}
			</div>
		</div>
	</div>
@endforeach

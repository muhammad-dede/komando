<div class="table-responsive">
    <table class="table table-striped table-bordered datatable" id="datatable_kelebihan">
        <thead>
        <tr>
            <th scope="col" width="5">No</th>
            <th scope="col">{!! $kelebihan !!}</th>
            <th scope="col">Jumlah Voter</th>
        </tr>
        </thead>
        <tbody>
			@php($index = 0)
			@foreach ($detail['kelebihan'] as $label => $vote)
				<tr>
					<td>{{ $index += 1 }}</td>
					<td>
						{{ $label }}
					</td>
					<td>{{ $vote }}</td>
				</tr>
			@endforeach
        </tbody>
    </table>
</div>

@inject('periode', 'App\Services\PeriodeService')
<select class="select2 form-control form-control-danger"
        name="periode"
        tabindex="-1"
        aria-hidden="true">
    <option selected="selected" disabled>Periode</option>
    @foreach($periode->periode() as $data)
        <option value="{{ $data['tahun'] }}"  {{ request('periode', date('Y')) == $data['tahun'] ? 'selected' : '' }}>{{ $data['tahun'] }}</option>
    @endforeach
</select>

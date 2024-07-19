@inject('liquidService', 'App\Services\LiquidService')
<select class="select2 form-control"
        name="company_code"
        tabindex="-1"
        aria-hidden="true">
    <option {{ request('company_code', auth()->user()->company_code) == '' ? 'selected="selected"' : '' }} value="">Semua Company</option>
    @foreach ($liquidService->listUnitKerjaCompany_HTDArea(auth()->user()) as $key => $unit)
        <option value="{{ $unit->company_code }}" {{ request('company_code', auth()->user()->company_code) == $unit->company_code ? 'selected' : '' }}>
            {{ $unit->description }}
        </option>
    @endforeach
</select>

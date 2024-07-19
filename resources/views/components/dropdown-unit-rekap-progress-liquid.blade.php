@inject('liquidService', 'App\Services\LiquidService')

<div id="dropdown-rekap-progress-liquid-unit" style="display: {{ ($companyCode=='1000') ? 'none':'' }}">
    <select class="select2 form-control form-control-danger"
            name="unit_code"
            tabindex="-1"
            aria-hidden="true">
        <option {{ request('unit_code') == "" ? 'selected' : '' }} value="" data-company="all">Semua Unit</option>
        @foreach ($liquidService->listUnitKerja_2(auth()->user()) as $unit)
            <option data-company="{{ $unit->company_code }}" value="{{ $unit->business_area }}" {{ request('unit_code', auth()->user()->business_area) == $unit->business_area ? 'selected' : '' }}>
                {{ $unit->description }}
            </option>
        @endforeach
    </select>
</div>
<div id="dropdown-rekap-progress-liquid-divisi" style="display: {{ ($companyCode!='1000') ? 'none':'' }}">
    <select
        class="select2 form-control form-control-danger"
        {{-- onChange="window.document.location.href=this.options[this.selectedIndex].dataset.url;" --}}
        name="divisi"
        tabindex="-1"
        aria-hidden="true"
    >
        <option selected="selected" value="1">Semua Divisi</option>
        @foreach ($liquidService->listDivisiPusat() as $id => $label)
            <option
                value="{{ $id }}"
                data-url="{{ url()->current() }}?unit_code={{ request('unit_code', $user->business_area) }}&divisi={{ $id }}"
                {{ request('divisi', $user->getKodeDivisi()) == $id ? 'selected' : '' }}
            >
            {{ $label }}
            </option>
        @endforeach
    </select>
</div>
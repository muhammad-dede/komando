@inject('liquidService', 'App\Services\LiquidService')
<select
    class="select2 form-control form-control-danger"
    {{-- onChange="window.document.location.href=this.options[this.selectedIndex].dataset.url;" --}}
    name="unit_code"
    tabindex="-1"
    aria-hidden="true"
>
    <option selected="selected" disabled>Filter Unit</option>
    @foreach ($liquidService->listUnitKerja(auth()->user()) as $code)
        <option
            value="{{ $code->business_area }}"
            data-url="{{ url()->current() }}?unit_code={{ $code->business_area }}&divisi={{ request('divisi', auth()->user()->getKodeDivisi()) }}"
            {{ request('unit_code', auth()->user()->getKodeUnit()) == $code->business_area ? 'selected' : '' }}
        >
            {{ $code->description }}
        </option>
    @endforeach
</select>

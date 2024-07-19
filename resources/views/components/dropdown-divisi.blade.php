@inject('liquidService', 'App\Services\LiquidService')
<select
    class="select2 form-control form-control-danger"
    {{-- onChange="window.document.location.href=this.options[this.selectedIndex].dataset.url;" --}}
    name="divisi"
    tabindex="-1"
    aria-hidden="true"
>
    <option selected="selected" disabled>Filter Divisi</option>
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

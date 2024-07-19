<form action="{{ url()->current() }}" method="get">
    <div class="row" style="padding: 1rem 0">
        <div class="col-md-3 col-xs-12">
            <h4 class="">{{ $title }}</h4>
        </div>
        <div class="col-md-1 col-xs-12">
            @include('components.dropdown-periode')
        </div>
        <div class="col-md-3 col-xs-12 ">
            @if(auth()->user()->can(\App\Enum\LiquidPermission::VIEW_ALL_UNIT))
                @include('components.dropdown-company')
            @elseif(auth()->user()->hasRole(App\Enum\RolesEnum::ADMIN_HTD))
                @include('components.dropdown-company-admin-htd-area')
            @else
                @include('components.dropdown-company-readonly')
            @endif
        </div>
        <div class="col-md-2 col-xs-12 ">
            @if(auth()->user()->can(\App\Enum\LiquidPermission::VIEW_ALL_UNIT))
                @include('components.dropdown-unit-rekap-progress-liquid')
            @elseif(auth()->user()->can(\App\Enum\LiquidPermission::VIEW_UNIT_INDUK))
                @include('components.dropdown-unit-rekap-progress-liquid')
            @elseif(auth()->user()->can(\App\Enum\LiquidPermission::VIEW_UNIT_PELAKSANA))
                @include('components.dropdown-unit-rekap-progress-liquid')
            @else
                @include('components.dropdown-unit-report-liquid-readonly')
            @endif
        </div>
        <div class="col-md-2 col-xs-12 ">
            @include('components.dropdown-jenjang-jabatan')
        </div>
        <div class="col-md-1 col-xs-12">
            <button type="submit" name="search" value="1" class="btn btn-primary width-full">Search</button>
        </div>
    </div>
</form>

@push('styles')
    <style>
        .select2-container .select2-results__option.hidden {
            display: none;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(function(){
            $(".select2").select2({
                templateResult: function (data, container) {
                    if (data.element) {
                        $(container).addClass($(data.element).attr("class"));
                    }
                    return data.text;
                }
            });

            let unitCode = $('select[name="unit_code"]').val();
            let defaultCompnay = $('select[name="company_code"]').val();

            $('select[name="company_code"]').on('change', function (e) {
                let company = $(e.currentTarget).val();
                if (company == '') {
                    $('select[name="unit_code"] option').removeClass('hidden');
                    $(`#dropdown-rekap-progress-liquid-unit`).css("display", "");
                    $(`#dropdown-rekap-progress-liquid-divisi`).css("display", "none");
                } else if(company == '1000') {
                    // $('select[name="unit_code"] option').removeClass('hidden');
                    $(`#dropdown-rekap-progress-liquid-unit`).css("display", "none");
                    $(`#dropdown-rekap-progress-liquid-divisi`).css("display", "");
                } else {
                    $(`#dropdown-rekap-progress-liquid-unit`).css("display", "");
                    $(`#dropdown-rekap-progress-liquid-divisi`).css("display", "none");
                    $('select[name="unit_code"] option').removeClass('hidden');
                    $('select[name="unit_code"] option').each(function (elm) {
                        if ($(this).data('company') == 'all') {
                            return true;
                        }

                        if ($(this).data('company') != company) {
                            $(this).addClass('hidden');
                        }
                    });
                    if (company == defaultCompnay) {
                        $('select[name="unit_code"]').val(unitCode).trigger('change');
                    } else {
                        $('select[name="unit_code"]').val("").trigger('change');
                    }
                }

            }).trigger('change');
        });
    </script>
@endpush

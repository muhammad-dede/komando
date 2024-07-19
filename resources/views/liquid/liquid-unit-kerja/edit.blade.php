@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/card.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/css/badge.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/tree-multiselect/jquery.tree-multiselect.min.css') }}" rel="stylesheet">
    <style>
        #overlay{
            position: fixed;
            top: 0;
            display: none;
            z-index: 100;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 10000;
            left: 0;
        }
        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }
        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }
        .collect {
            font-size: 24px;
            margin-left: 1rem;
            color: #ffffff;
        }
    </style>


@stop

@section('title')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h4 class="page-title">Edit Liquid #{{ $liquid->id }}</h4>
        </div>
    </div>
@stop

@section('content')

    <div id="overlay">
        <div class="cv-spinner">
            <div class="spinner"></div>
            <div class="collect">collecting peserta liquid</div>
        </div>
    </div>
    @include('components.flash')

    <div class="row card-box">
        <div class="col-md-12">
            @include('components.liquid-tab', ['active' => 'unit-kerja'])
            <div class="tab-content comp-tab-content">

                @if($liquid->isPublished())
                    <div class="alert alert-warning">
                        Unit kerja tidak bisa diedit jika Liquid status = {{ $liquid->status }}.
                    </div>
                @endif

                <form id="submit" action="{{ route('liquid.unit-kerja.update', $liquid) }}" method="POST">
                    {!! csrf_field() !!}
                    {!! method_field('PUT') !!}
                    <div class="col-xs-12 mar-b-1rem">
                        <select id="unitKerja" multiple="multiple" data-count="{{ $businessAreas->count() }}">
                            @foreach($businessAreas as $area)
                                <option
                                        value="{{ $area->getKey() }}"
                                        data-section="{{ $area->companyCode->description }}"
                                        @if(in_array($area->business_area, $selected))
                                        selected="selected"
                                        @endif
                                >
                                    {{ $area->description }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                    <div  class="edit-selected hidden-el">
                        <ul>
                        @foreach($businessAreas as $area)
                            @if(in_array($area->business_area, $selected))
                                <li>{{ $area->description }}</li>
                            @endif
                        @endforeach
                        </ul>
                    </div>

                    <div class="col-xs-12">
                        @if($liquid->isPublished())
                            <a href="{{ route('liquid.peserta.edit', $liquid) }}" class="btn btn-primary btn-lg pull-right">
                                <i aria-hidden="true" class="fa fa-arrow-right"></i> Next
                            </a>
                        @else
                            <button class="btn btn-primary save btn-lg pull-right">
                                <i aria-hidden="true" class="fa fa-arrow-right"></i> Next
                            </button>
                        @endif
                        <a href="{{ url('liquid/'.Request::segment(2).'/edit') }}" class="mar-r-1rem btn btn-warning btn-lg pull-right">
                            <i aria-hidden="true" class="fa fa-arrow-left"></i> Previous
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/tree-multiselect/jquery.tree-multiselect.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            function checkValue(value,arr){
                var status = 'Not exist';

                for(var i=0; i<arr.length; i++){
                    var name = arr[i];
                    if(name == value){
                        status = 'Exist';
                        break;
                    }
                }

                return status;
            }

            $( "button.btn.btn-primary " ).click(function(e) {
                e.preventDefault();
                var dataEdit = []
                $( ".edit-selected ul li" ).each(function( index ) {
                    var editText = $( this ).text()
                    var test = []
                    $( ".tree-multiselect .selected.ui-sortable .item" ).each(function( index ) {
                        var getText = $( this ).find('.hidden-el').text()
                        test.push(getText)
                    });
                    if(checkValue(editText,test) != 'Exist') {
                        dataEdit.push(editText)
                    }
                    test = []
                });
                console.log(dataEdit)
                if (dataEdit != 0) {
                    swal({
                            title: "Anda yakin?",
                            text: "anda menghapus Unit Kerja "+dataEdit+", atasan yang berasal dari unit kerja tersebut juga akan di hapus dari daftar peserta",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $('#overlay').css('display', 'unset')
                                $( "#submit" ).submit();
                            }
                        });
                } else {
                    $('#overlay').css('display', 'unset')
                    $( "#submit" ).submit();
                }
            });
            $( ".edit-selected ul li" ).each(function( index ) {
                var getText = $( this ).text()
                console.log(getText)
            });
            var options = {
                @if($liquid->isPublished())
                sortable: false,
                searchable: false,
                freeze: true,
                enableSelectAll: false,
                @else
                sortable: true,
                searchable: true,
                freeze: false,
                enableSelectAll: true,
                @endif
                selectAllText: 'Select All',
                unselectAllText: 'Unselect All'
            };
            $("select#unitKerja").treeMultiselect(options);
        });
    </script>
@endpush

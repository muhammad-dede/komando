@extends('layout')

@section('css')
    <link href="{{asset('assets/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('title')
    <h4 class="page-title">Display Role</h4>
@stop

@section('content')
    {!! Form::open(['url'=>'user-management/role/'.$role->id, 'class'=>'form-control']) !!}
    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label for="role_id"
                                   class="col-sm-7 form-control-label"><span class="pull-right">ID</span></label>
                            <div class="col-sm-5">
                                {!! Form::text('role_id',str_pad($role->id,15,'0', STR_PAD_LEFT),['class'=>'form-control', 'id'=>'role_id', 'readonly']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">{!! Form::text('status',$role->status,['class'=>'form-control', 'id'=>'status', 'readonly']) !!}</div>
                    <div class="col-md-2">
                        <div class="form-group row form-inline">
                            {{--<div class="button-list">--}}
                            <button id="btn_next" type="submit" class="btn btn-primary pull-left w-sm">
                                <i class="fa fa-save"></i> Save
                            </button>
                            &nbsp;&nbsp;
                            <a href="{{action('RoleController@index')}}" type="button"
                               class="btn btn-secondary pull-left w-sm">
                                <i class="fa fa-times"></i> Cancel</a>
                            {{--</div>--}}
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs m-b-10 m-t-20" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general"
                           role="tab" aria-controls="general" aria-expanded="true">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="permission-tab" data-toggle="tab" href="#permission"
                           role="tab" aria-controls="permission">Permission</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade in active" id="general"
                         aria-labelledby="general-tab">
                        <fieldset class="form-group">
                            <label for="name">Name</label>
                            {!! Form::text('name',$role->name, ['class'=>'form-control', 'id'=>'name']) !!}
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="display_name">Display Name</label>
                            {!! Form::text('display_name',$role->display_name, ['class'=>'form-control', 'id'=>'display_name']) !!}
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="description">Description</label>
                            {!! Form::text('description',$role->description, ['class'=>'form-control', 'id'=>'description']) !!}
                        </fieldset>
                    </div>
                    <div class="tab-pane fade" id="permission" role="tabpanel"
                         aria-labelledby="permission-tab">
                        @foreach($modules as $module)
                            <div class="row m-t-30">
                                <div class="col-md-12">
                                <h4 class="header-title m-t-0">{{$module->display_name}}</h4>
                                <select name="permission_{{$module->name}}[]" class="multi-select" multiple=""
                                        id="permission_{{$module->name}}">
                                    @foreach($module->permissions as $permission)
                                        <option value="{{$permission->id}}" {{($role->hasPermission($permission->name))? 'selected' : '' }}>{{$permission->display_name}}</option>
                                    @endforeach
                                </select>
                                <hr>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>
    </div>
    {!! Form::close() !!}

@stop

@section('javascript')
    <script type="text/javascript" src="{{asset('assets/plugins/multiselect/js/jquery.multi-select.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/plugins/jquery-quicksearch/jquery.quicksearch.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>

    <script>
        $(document).ready(function () {

            @foreach($modules as $module)
            //advance multiselect start
            $('#permission_{{$module->name}}').multiSelect({
                selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                afterInit: function (ms) {
                    var that = this,
                            $selectableSearch = that.$selectableUl.prev(),
                            $selectionSearch = that.$selectionUl.prev(),
                            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                            .on('keydown', function (e) {
                                if (e.which === 40) {
                                    that.$selectableUl.focus();
                                    return false;
                                }
                            });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                            .on('keydown', function (e) {
                                if (e.which == 40) {
                                    that.$selectionUl.focus();
                                    return false;
                                }
                            });
                },
                afterSelect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });
            @endforeach

            // Select2
            $(".select2").select2();

        });
    </script>
@stop
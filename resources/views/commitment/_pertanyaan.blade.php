@extends('layout')

@section('css')

@stop

@section('title')
    {{--<div class="btn-group pull-right m-t-15">--}}
    {{--<button type="button" class="btn btn-custom dropdown-toggle waves-effect waves-light"--}}
    {{--data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i--}}
    {{--class="fa fa-cog"></i></span></button>--}}
    {{--<div class="dropdown-menu">--}}
    {{--<a class="dropdown-item" href="#">Action</a>--}}
    {{--<a class="dropdown-item" href="#">Another action</a>--}}
    {{--<a class="dropdown-item" href="#">Something else here</a>--}}
    {{--<div class="dropdown-divider"></div>--}}
    {{--<a class="dropdown-item" href="#">Separated link</a>--}}
    {{--</div>--}}

    {{--</div>--}}
    <h4 class="page-title">Kepemimpinan</h4>
@stop

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="card-box">

                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <h4 class="header-title m-t-0">Pertanyaan</h4>

                        <p class="text-muted font-13 m-b-30">
                            Silakan jawab pertanyaan berikut ini.
                        </p>

                        <form id="basic-form" action="{{url('commitment')}}">
                            <div>
                                <h3>Pertanyaan 1</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 text-xl-center">
                                            <label>
                                                Poin di bawah ini adalah objek-objek yang harus dihindari dalam Komitmen
                                                Kepemimpinan kecuali?
                                            </label>
                                        </div>
                                    </div>
                                    <!-- end row -->
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 text-xl-center">
                                            <div class="m-t-30">
                                                {{--<h6 class="m-b-20 text-muted">Radios</h6>--}}
                                                <label class="c-input c-radio">
                                                    <input id="radio11" name="radio" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 1
                                                </label>
                                                <label class="c-input c-radio">
                                                    <input id="radio21" name="radio" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 2
                                                </label>
                                                <label class="c-input c-radio">
                                                    <input id="radio11" name="radio" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 3
                                                </label>
                                                <label class="c-input c-radio">
                                                    <input id="radio21" name="radio" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 4
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- end row -->
                                    {{--<div class="row">--}}
                                    {{--<div class="col-xs-12 col-sm-6">--}}
                                    {{--<div class="form-group clearfix row">--}}
                                    {{--<label class="col-lg-12 control-label ">(<span--}}
                                    {{--class="text-danger">*</span>) Mandatory</label>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<!-- end row -->--}}


                                </section>
                                <h3>Pertanyaan 2</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 text-xl-center">
                                            <label>
                                                Poin di bawah ini adalah objek-objek yang harus dihindari dalam Komitmen
                                                Kepemimpinan kecuali?
                                            </label>
                                        </div>
                                    </div>
                                    <!-- end row -->
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 text-xl-center">
                                            <div class="m-t-30">
                                                {{--<h6 class="m-b-20 text-muted">Radios</h6>--}}
                                                <label class="c-input c-radio">
                                                    <input id="radio11" name="radio2" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 1
                                                </label>
                                                <label class="c-input c-radio">
                                                    <input id="radio21" name="radio2" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 2
                                                </label>
                                                <label class="c-input c-radio">
                                                    <input id="radio11" name="radio2" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 3
                                                </label>
                                                <label class="c-input c-radio">
                                                    <input id="radio21" name="radio2" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 4
                                                </label>
                                            </div>

                                        </div>
                                    </div>

                                </section>
                                <h3>Pertanyaan 3</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 text-xl-center">
                                            <label>
                                                Poin di bawah ini adalah objek-objek yang harus dihindari dalam Komitmen
                                                Kepemimpinan kecuali?
                                            </label>
                                        </div>
                                    </div>
                                    <!-- end row -->
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 text-xl-center">
                                            <div class="m-t-30">
                                                {{--<h6 class="m-b-20 text-muted">Radios</h6>--}}
                                                <label class="c-input c-radio">
                                                    <input id="radio11" name="radio3" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 1
                                                </label>
                                                <label class="c-input c-radio">
                                                    <input id="radio21" name="radio3" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 2
                                                </label>
                                                <label class="c-input c-radio">
                                                    <input id="radio11" name="radio3" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 3
                                                </label>
                                                <label class="c-input c-radio">
                                                    <input id="radio21" name="radio3" type="radio">
                                                    <span class="c-indicator"></span>
                                                    Perilaku 4
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </section>
                                <h3>Finish</h3>
                                <section>

                                    <div class="row">
                                        <div class="col-lg-12 text-xl-center">
                                            <h4>Pernyataan Komitmen</h4>
                                        </div>
                                    </div>
                                    <div class="row m-t-10">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-6">

                                            <p class="card-text">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duo Reges:
                                                constructio interrete. An vero,
                                                inquit, quisquam potest probare, quod perceptfum, quod. Isto modo, ne si
                                                avia quidem eius nata non
                                                esset. Quae cum ita sint, effectum est nihil esse malum, quod turpe non
                                                sit. Cur tantas regiones
                                                barbarorum pedibus obiit, tot maria transmisit? Praeclare hoc quidem.
                                                Itaque hic ipse iam pridem est
                                                reiectus;
                                            </p>

                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <fieldset class="form-group">
                                                        <label for="exampleInputEmail1">Nama</label>
                                                        <br>
                                                        {{Auth::user()->name}}
                                                    </fieldset>
                                                    <fieldset class="form-group">
                                                        <label for="exampleInputEmail1">NIP</label>
                                                        <br>
                                                        {{Auth::user()->ad_employee_number}}
                                                    </fieldset>
                                                    <fieldset class="form-group">
                                                        <label for="exampleInputEmail1">Unit</label>
                                                        <br>
                                                        {{Auth::user()->ad_company}}
                                                    </fieldset>
                                                    <fieldset class="form-group">
                                                        <label for="exampleInputEmail1">Jabatan</label>
                                                        <br>
                                                        {{Auth::user()->ad_title}}
                                                    </fieldset>

                                                </div>
                                                <div class="col-lg-4">
                                                    <div>
                                                        <img src="{{asset('assets/images/user.jpg')}}"
                                                             class="img-responsive img-rounded img-thumbnail">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-group clearfix row pull-right">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox-h" type="checkbox">
                                                    <label for="checkbox-h">
                                                        I agree with the Terms and Conditions.
                                                    </label>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-lg-3"></div>
                                    </div>
                                </section>
                            </div>
                        </form>

                    </div>

                </div>
                <!-- end row -->
            </div>
        </div>
    </div>


    @stop

    @section('javascript')
            <!--wizard initialization-->
{{--    <script src="{{asset('assets/pages/jquery.wizard-init.js')}}" type="text/javascript"></script>--}}
    <script>

        !function ($) {
            "use strict";

            var FormWizard = function () {
            };

            FormWizard.prototype.createBasic = function ($form_container) {
                $form_container.children("div").steps({
                    headerTag: "h3",
                    bodyTag: "section",
                    transitionEffect: "slideLeft",
                    onFinishing: function (event, currentIndex) {
                        //NOTE: Here you can do form validation and return true or false based on your validation logic
//                        console.log("Form has been validated!");
                        return true;
                    },
                    onFinished: function (event, currentIndex) {
                        //NOTE: Submit the form, if all validation passed.
//                        console.log("Form can be submitted using submit method. E.g. $('#basic-form').submit()");
                        swal({
                            title: "Terimakasih",
                            text: "Anda telah melakukan komitmen 2017",
                            type: "success",
                            showCancelButton: false,
//                cancelButtonClass: 'btn-secondary waves-effect',
                            confirmButtonClass: 'btn-primary waves-effect waves-light',
                            confirmButtonText: 'Ok',
//                closeOnConfirm: false,
                        }, function (isConfirm) {
                            if (isConfirm) {
                        $("#basic-form").submit();
                                {{--window.location.href='{{url('commitment')}}';--}}
                            }
//                else {
//                    swal("Cancelled", "Your imaginary file is safe :)", "error");
//                }
                        });
//                        $("#basic-form").submit();
                        {{--window.location.href='{{url('commitment')}}';--}}
                    }
                });
                return $form_container;
            },
                    FormWizard.prototype.init = function () {
                        //initialzing various forms

                        //basic form
                        this.createBasic($("#basic-form"));

                    },
                //init
                    $.FormWizard = new FormWizard, $.FormWizard.Constructor = FormWizard
        }(window.jQuery),

//initializing
                function ($) {
                    "use strict";
                    $.FormWizard.init()
                }(window.jQuery);
    </script>
@stop
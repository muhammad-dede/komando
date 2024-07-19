/**
* Theme: Uplon Admin Template
* Author: Coderthemes
* Form wizard page
*/

!function($) {
    "use strict";

    var FormWizard = function() {};

    FormWizard.prototype.createBasic = function($form_container) {
        $form_container.children("div").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            onFinishing: function (event, currentIndex) { 
                //NOTE: Here you can do form validation and return true or false based on your validation logic
                console.log("Form has been validated!");
                return true; 
            }, 
            onFinished: function (event, currentIndex) {
               //NOTE: Submit the form, if all validation passed.
                console.log("Form can be submitted using submit method. E.g. $('#basic-form').submit()"); 
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
//                    swal("Terimakasih!", "Anda telah melakukan komitmen 2017.", "success");
                        window.location.href= 'http://coc.dev/commitment'
                    }
//                else {
//                    swal("Cancelled", "Your imaginary file is safe :)", "error");
//                }
                });
                $("#basic-form").submit();


            }
        });
        return $form_container;
    },
    //creates form with validation
    FormWizard.prototype.createValidatorForm = function($form_container) {
        $form_container.validate({
            errorPlacement: function errorPlacement(error, element) {
                element.after(error);
            }
        });
        $form_container.children("div").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                $form_container.validate().settings.ignore = ":disabled,:hidden";
                return $form_container.valid();
            },
            onFinishing: function (event, currentIndex) {
                $form_container.validate().settings.ignore = ":disabled";
                return $form_container.valid();
            },
            onFinished: function (event, currentIndex) {
                alert("Submitted!");
            }
        });

        return $form_container;
    },
    //creates vertical form
    FormWizard.prototype.createVertical = function($form_container) {
        $form_container.validate({
            errorPlacement: function errorPlacement(error, element) {
                element.after(error);
            }
        });
        $form_container.steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "fade",
            stepsOrientation: "vertical",
            onStepChanging: function (event, currentIndex, newIndex) {
                // console.log('index : '+currentIndex);
                // ritual
                if(currentIndex==1) {
                    /*var values = $("input[name='perilaku[]']")
                                    .map(function(){
                                        if ($(this).is(':checked')) {
                                            return $(this).val();
                                        }
                                    }).get();
                    // console.log(values.length);
                    if(values.length<2){
                        // console.log('perilaku < 2');
                        $("#label_perilaku").html('Pilih 2 perilaku.');
                        return false;
                    }
                    if(values.length>2){
                        // console.log('perilaku < 2');
                        $("#label_perilaku").html('Pilih maksimal 2 perilaku.');
                        return false;
                    }
                    $("#label_perilaku").html('');*/

                    var sipp = $("input[name='sipp[]']")
                                    .map(function(){
                                        if ($(this).is(':checked')) {
                                            return $(this).val();
                                        }
                                    }).get();
                                    
                    if(sipp.length<1){
                        $("#label_sipp").html('Pilih minimal 1 Value/Nilai.');
                        return false;
                    }
                    $("#label_sipp").html('');

                }

                // pelanggaran
                if(currentIndex==2) {
                    var pelanggaran = $("input[name='pelanggaran']")
                                        .map(function(){
                                            if ($(this).is(':checked')) {
                                                return $(this).val();
                                            }
                                        }).get();
                    // console.log(pelanggaran);
                                    
                    if(pelanggaran.length<1){
                        $("#label_pelanggaran").html('Pilih salah satu pelanggaran disiplin.');
                        return false;
                    }
                    $("#label_pelanggaran").html('');
                }

                return $form_container.valid();
            },
            onFinishing: function (event, currentIndex) { 
                //NOTE: Here you can do form validation and return true or false based on your validation logic
                // console.log("Form has been validated!");

                // pelanggaran
                if(currentIndex==2) {
                    var pelanggaran = $("input[name='pelanggaran']")
                                        .map(function(){
                                            if ($(this).is(':checked')) {
                                                return $(this).val();
                                            }
                                        }).get();
                    // console.log(pelanggaran);
                                    
                    if(pelanggaran.length<1){
                        $("#label_pelanggaran").html('Pilih salah satu pelanggaran disiplin.');
                        return false;
                    }
                    $("#label_pelanggaran").html('');
                }

                return true; 
            }, 
            onFinished: function (event, currentIndex) {
                // alert("Submitted!");
                $(this).submit();
            }
            
        });
        return $form_container;
    },
    FormWizard.prototype.init = function() {
        //initialzing various forms

        //basic form
        this.createBasic($("#basic-form"));

        //form with validation
        this.createValidatorForm($("#wizard-validation-form"));

        //vertical form
        this.createVertical($("#wizard-vertical"));
    },
    //init
    $.FormWizard = new FormWizard, $.FormWizard.Constructor = FormWizard
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.FormWizard.init()
}(window.jQuery);
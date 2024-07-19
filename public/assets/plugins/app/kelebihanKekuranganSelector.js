// modal kelebihan/kekurangan plugin
$.fn.kelebihanKekuranganSelector = function (options) {
    var settings = $.extend({
        // default options
        target: null,
        min: 3,
		max: 3,
		customKKMin: 50,
        validationMessage: {
			kelebihanKekurangan: 'Silahkan pilih',
			kelebihanKekuranganLainnya: 'Kelebihan dan Kekurangan lainnya minimal 50 karakter!'
		},
        labelMessage: "",
        wordCount: 5,
    }, options);

	var customTextArea = this.find('[data-role="customOption"] textarea');
	var customBox = this.find('[data-role="customOption"] [data-role="option"]');
    var self = this;
    var datatableInitialized = false;
    var table = this.find('[data-role="datatable"]');
    var countChecked = self.find('[data-role="option"]:checked').length;
	var datatable;
	var customChar = customTextArea.val();
	var customChecked = customBox.prop('checked');

    checkOptionState();

    // HELPER FUNCTIONS
    function copyRow(source, target) {
        target.empty();
        var dataInputChecked = new Array();
        var dataTextareaEnable = new Array();
        source.find('input:checked').each(function (e) {
            let label = $(this).data('label');
            let valueLabel = $(this).val();
            var tmpArray = new Array();
            tmpArray['label'] = label;
            tmpArray['val'] = valueLabel;
            dataInputChecked[e] = tmpArray;
            // target.append(`<tr><td align="left">${label}</td></tr>`);
        });

        source.find('textarea:enabled').each(function (e) {
            let label = $(this).val();
            dataTextareaEnable[e] = label;
        });

        $.each(dataInputChecked, function( index, value ) {
            var classInput = "terpilihKekurangan";
            if(target.selector === "#selectedKelebihan"){
                classInput = "terpilihKelebihan";
            }
            var tmp = `<tr><td align="left">${value.label} <input type="hidden" class="${classInput}" value="${value.val}"></td><td align="left">${dataTextareaEnable[index]}</td></tr>`;
            target.append(tmp);
        });
    }

    function checkOptionState() {
        if (countChecked === settings.max) {
            self.find('[data-role="option"]:not(:checked)').prop('disabled', true);
        }
        else {
            self.find('[data-role="option"]').prop('disabled', false);

            if(settings.target.selector === "#selectedKelebihan") {
                $(`#selectedKekurangan`).find('input.terpilihKekurangan').each(function (e) {
                    let valueLable = $(this).val();
                    $(`#boxes_kelebihan_${valueLable}`).prop('disabled', true);
                });
            }else{
                $(`#selectedKelebihan`).find('input.terpilihKelebihan').each(function (e) {
                    let valueLable = $(this).val();
                    $(`#boxes_kekurangan_${valueLable}`).prop('disabled', true);
                });
            }
        }
    }

    this.on('shown.bs.modal', function () {
        if (!datatableInitialized) {
            datatable = table.DataTable({
                scrollY: "500px",
                scrollCollapse: true,
                bPaginate: false,
            });

            datatable.on('draw', function () {
                checkOptionState();
            });

            datatableInitialized = true;
        }
    });

    this.find('[data-role="option"]').on('change', function () {
        if ($(this).is(':checked')) {
            countChecked++;
        } else {
            countChecked--;
        }
        checkOptionState();
    });

    customTextArea.on('keyup', function () {
		self.find('[data-role="customOption"] input[type="checkbox"]').data('label', $(this).val());
		customChar = $(this).val();
    });

    customBox.on('change', function () {
		customChecked = customBox.prop('checked');
        self.find('[data-role="customOption"] textarea').prop('disabled', $(this).is(':not(:checked)'));
    });

    function checkMandatoriTextArea(source) {
        var totalAlasan = 0;
        source.find('textarea:enabled').each(function (e) {
            let label = $(this).val();
            if (label.trim() === "") {
                totalAlasan++;
            }
        });
        return totalAlasan;
    }

    function checkWordCount(elm) {
        var invalidated = 0;

        elm.find('textarea:enabled').each(function (e) {
            let value = $(this).val();

            console.log(invalidated);

            if (value.split(' ').length < settings.wordCount) {
                invalidated++;
            } else {
                console.log($(this));
            }
        });

        return invalidated;
    }

    // ADD SELECTED KELEBIHAN TO TABLE
    this.find('[data-role="save"]').click(function (e) {
        var checkTextArea = checkMandatoriTextArea(table);

        let checkboxChecked = $(this).parent().parent().find('[type="checkbox"]:checked').length,
            isCheckboxKurang = checkboxChecked < settings.min,
            countCheckboxKurang = settings.min - checkboxChecked;

        if (isCheckboxKurang) {
            e.preventDefault();

            alert(`Pilihan anda masih kurang, silahkan pilih ${countCheckboxKurang} lagi!`);

            return false;
        }

        if (checkTextArea > 0) {
            alert(`Ada ${checkTextArea} Keterangan/Alasan yang masih belum diisi!`);

            e.preventDefault();

            return false;
        }

        let invalidCount = checkWordCount(table);

        if (invalidCount > 0) {
            let wordCount = settings.wordCount;

            alert(`${invalidCount} isian Keterangan/Alasan masih ada yang kurang dari ${wordCount} kata!`);

            e.preventDefault();

            return false;
        }

		if (customChecked) {
			if (customChar.replace(/\s+/g, '').length < settings.customKKMin) {
				alert(settings.validationMessage.kelebihanKekuranganLainnya);
				e.preventDefault();
				return false;
			}
		}

        // clear datatable agar selector untuk mencari checked option berfungsi dengan baik
        // karena ada potensi bbrp row ter-hidden ketika melakukan searching via datatable
        if (settings.target) {
            datatable.destroy();
            copyRow(table, settings.target);
            datatable = table.DataTable({
                scrollY: "500px",
                scrollCollapse: true,
                bPaginate: false,
            });
        }
    });

    return this;
}

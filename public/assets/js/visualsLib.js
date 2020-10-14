$(document).ready(function () {
    //Form Validation
    ajax_form($('.ajax-form-features'), 'validate_feature');

    getVisualByAjax(1);
    // getVisualByAjax(2);

    //Manage Visual - Change field visual by id_category
    var visual_category = $('#visual_category');
    if (visual_category.length > 0) {
        changeVisualByIdCategoryAjax(visual_category.val());
        visual_category.change(function () {
            var category = $(this).val();
            changeVisualByIdCategoryAjax(category);
        });
    }

    //Comptage sms
    if ($('#visual-sms').length > 0) {
        countSMS($('#visual-sms'));
    }

    if ($('#visual-preview').length > 0 && $('#visual-form').length > 0) {
        var data_form = $('#visual-form').serializeObject();
        var category = data_form.category;
        getPreview(data_form, $('#visual-preview'));
    }

    $('#refresh-thumb').click(function () {
        var data_form = $('#visual-form').serializeObject();
        var category = data_form.category;
        getPreview(data_form, $('#visual-preview'));
    });

    $('#enlarge-thumb').click(function () {
        $('body').append('<div id="show-thumb-iframe" style="display:none">' + $('#visual-preview').find('.preview').html() + '</div>');
        var iframe_html = $('#visual-preview').find('.preview').html();
        $.fancybox.open({
            src: '#show-thumb-iframe',
            width: 'auto'
        });
    });

    $('select.select2').select2();

    $("#id_client_feature").select2({
        placeholder: "Please choose one or more features",
        allowClear: true
    });

    var features = $('input[name="defaultFeatures"]').val();
    if (features) {
        $('#features').val(features.split(',')).trigger('change');
    }

});
function changeVisualByIdCategoryAjax(category) {
    if (category != '') {
        var data_ajax = {
            category: category,
            action: 'getCategoryParent'
        };
        ajax_function(data_ajax, changeVisualByIdCategory, $('#visual-field'));
    } else {
        $('.visual-field').hide();
        $('.visual-field').find(':input').attr('disabled', 'disabled');
    }
}

function changeVisualByIdCategory(elem, data) {
    if (editor) {
        editor.toTextArea();
    }
    if (data.return == 'ok') {
        //On refresh parent_category_id
        if ($('#visual-form').find('input[type=hidden][name=category]').length > 0) {
            $('input[type=hidden][name=categor]').val(data.category);
        } else {
            $('#visual-form').append('<input type="hidden" name="category" value="' + data.category + '" style="display:none;">');
        }

        $('.visual-field').hide();
        $('.visual-field').find(':input').attr('disabled', 'disabled');

        $('#visual-field-' + data.type).slideDown(200);
        $('#visual-field-' + data.type).find('label').text(data.label);
        $('#visual-field-' + data.type).find(':input').removeAttr('disabled');
        if (data.is_sms) {
            $('#visual-field-' + data.type).find(':input').attr('id', data.is_sms);
            countSMS($('#' + data.is_sms));
            $('input[name=sms_url]').parent('.form-group').slideDown(200);
        } else {
            $('input[name=sms_url]').parent('.form-group').slideUp(200);
            $('#visual-field-' + data.type).find(':input').removeAttr('id');
            sms_remaining.empty();
            sms_messages.empty();
        }
        if (data.category == 1) {
            /* editor = CodeMirror.fromTextArea(document.querySelector('textarea[name=visual]'), {
                mode: 'htmlmixed',
                styleActiveLine: true,
                lineNumbers: true,
                theme: "dracula",
                autoCloseTags: true,
                extraKeys: {
                    "F11": function (cm) {
                        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                    },
                    "Esc": function (cm) {
                        if (cm.getOption("fullScreen"))
                            cm.setOption("fullScreen", false);
                    }
                }
            });
            $('button[name=fullscreen]').click(function (event) {
                event.preventDefault();
                editor.setOption("fullScreen", !editor.getOption("fullScreen"));
            }); */

            $('.summernote').summernote({
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["fontname", ["fontname"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["insert", ["link", "picture"]],
                    ["view", ["codeview"]]
                ],
            });

        } else {
            $('.summernote').summernote('destroy')
        }
    }
}
var editor = false;
var sms_remaining = $('#sms-remaining');
var sms_messages = $('#sms-message');

function countSMS(elem) {

    var chars = elem.val().length;
    var messages = Math.ceil(chars / 160);
    var remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

    sms_remaining.text(remaining + ' characters remaining');
    sms_messages.text(messages + ' message(s)');
    elem.keyup(function () {
        var chars = this.value.length,
            messages = Math.ceil(chars / 160),
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

        sms_remaining.text(remaining + ' characters remaining - ');
        sms_messages.text(messages + ' message(s)');
    });
}

/**
 * Get Visual Preview
 * @param {type} id_visual
 * @param {type} id_category
 * @returns {undefined}
 */
function getPreview(data, elem) {
    elem.append(card_overlay);
    var data_ajax = {
        id_visual: data.id_visual,
        category: data.category,
        visual: data.visual,
        sms_url: data.sms_url,
        name: data.name,
        action: 'generateVisualThumbnail'
    };
    ajax_function(data_ajax, showPreview, elem);
}

function showPreview(elem, data) {
    elem.find('.overlay').remove();
    if (data.return == 'ok') {
        elem.find('.preview').hide().empty().append(data.thumb).delay(100).slideDown(200);
        //On masque ou on affiche le bouton d'agrandissement
        if (data.category == 1) {
            $('#enlarge-thumb').show();
        } else {
            $('#enlarge-thumb').hide();
        }
    }
}

/**
 * Gestion affichage tab
 */

function getVisualByAjax(category) {
    var features;
    // if (category === 3) {
    features = $("select[name=features_"+category+"]").val();
    // } else if (category === 2) {
    //     features = $('select[name="features_sms"]').val();
    // }
    if(features){
        var data = {'features': features.join(",")};
        $.ajax({
            type: 'POST',
            data: 'data=' + JSON.stringify(data),
            url: '/visualslib/getVisualByAjax/' + category,
            beforeSend: function () {
                $('html').css('cursor', 'wait');
            },
            success: function (res, statut) {
                var html = '';
                var i;
                for (i = 0; i < res.visuals.length; i++) {
                    html += '<div class="col-md-3">' +
                        '<div id="visual-preview" class="card" style="min-height: 250px;">' +
                        '<div class="card-body">' +
                        '<div class="preview">' +
                        res.visuals[i].visual +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }
                console.log($('#listVisuals_'+category));
                console.log(html);
                console.log(category);
                // if (category === 3) {
                $('#listVisuals_'+category).html(html);
                // } else if (category === 2) {
                //     $('#listVisuals_sms').html(html);
                // } else {
                //     $('#listVisuals_email').html(html);
                // }

            },
            error: function (request, status, error) {
                toastr.error(error.message, 'Error');
            },
            complete: function () {
                $('html').css('cursor', 'default');
            },
            dataType: 'json'
        });
    }

}


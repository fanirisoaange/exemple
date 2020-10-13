$(document).ready(function () {
    //Form Validation
    ajax_form($('.ajax-form-features'), 'validate_feature');

    //Manage Visual - Change field visual by id_category
    var visual_id_category = $('#visual-id_category');
    if (visual_id_category.length > 0) {
        changeVisualByIdCategoryAjax(visual_id_category.val());
        visual_id_category.change(function () {
            var id_category = $(this).val();
            changeVisualByIdCategoryAjax(id_category);
        });
    }

    //Comptage sms
    if ($('#visual-sms').length > 0) {
        countSMS($('#visual-sms'));
    }

    if ($('#visual-preview').length > 0 && $('#visual-form').length > 0) {
        var data_form = $('#visual-form').serializeObject();
        var parent_category_id = data_form.parent_category_id;
        getPreview(data_form, $('#visual-preview'));
    }

    $('#refresh-thumb').click(function () {
        var data_form = $('#visual-form').serializeObject();
        var parent_category_id = data_form.parent_category_id;
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

});
function changeVisualByIdCategoryAjax(id_category) {
    if (id_category != '') {
        var data_ajax = {
            id_category: id_category,
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
        if ($('#visual-form').find('input[type=hidden][name=parent_category_id]').length > 0) {
            $('input[type=hidden][name=parent_category_id]').val(data.parent_category_id);
        } else {
            $('#visual-form').append('<input type="hidden" name="parent_category_id" value="' + data.parent_category_id + '" style="display:none;">');
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
        if (data.parent_category_id == 2) {
            editor = CodeMirror.fromTextArea(document.querySelector('textarea[name=visual]'), {
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
            });
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
        id_category: data.id_category,
        parent_category_id: data.parent_category_id,
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
        if (data.parent_category_id == 2) {
            $('#enlarge-thumb').show();
        } else {
            $('#enlarge-thumb').hide();
        }
    }
}
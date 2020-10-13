$(document).ready(function () {
    activateTabs(); 

    if (jQuery().dataTable) {
    var arrayColumnDefs = [];
    arrayColumnDefs[0] = { type: "date-eu", targets: 1 };
    $("#campaignList").DataTable({
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: true,
      aaSorting: [],
      columnDefs: arrayColumnDefs,
    });
  }

    $("input[data-bootstrap-switch]").each(function () {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
    $('#tabsCampaignContainer input').attr('autocomplete', 'off');

    $(document).on('change', '#dopdownCampaignType', function () {
        console.log($(this).val());
        if ($(this).val() == '2') {
            $('#checkboxAdhesion').show();
        } else {
            $('#checkboxAdhesion').hide();
        }
    });

    $(document).on('click', '#tabsCampaignContainer .nav-link', function () {
        $('#tabsCampaignContainer .nav-item').removeClass('active');
        if ($(this).hasClass('active')) {
            $(this).parents('li').addClass('active');
        }
    });

    $(document).on('click', '#editVisualBtn', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var visual_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: url,
            data: {type: 'visual', visual_id: visual_id},
            dataType: 'json',
            success: function (res) {
                $('#formVisual input[name="channel_id"]').empty().val(res.channel_id);
                $('#formVisual input[name="campaign_sender"]').empty().val(res.sender);
                $('#formVisual input[name="campaign_subject"]').empty().val(res.subject);
                editor.getDoc().setValue(res.visual_code);
                $('#formVisualContainer').show();
            }
        });
    });

    /*
    $(document).on('submit', '#tabsCampaignContainer #tabsContent form', function (e) {
        submitFormCampaign(this);
    });
    */

    $('.campaignDate').datetimepicker({
        // Formats
        // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
        format: 'DD-MM-YYYY HH:mm:ss',
        minDate: moment().add('days'),
        useCurrent: false,

        icons: {
            time: 'fas fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        }
    });

    $('#campaignEnd').datetimepicker({
        // Formats
        // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
        format: 'DD-MM-YYYY HH:mm:ss',
        minDate: moment().add('days', 3),
        useCurrent: false,

        icons: {
            time: 'fas fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-check',
            clear: 'fa fa-trash',
            close: 'fa fa-times'
        }
    });

    showHtmlEditor();
    $(document).on('change', '#dopdownCampaignChannelIds', function (e) {
        showHtmlEditor();
    });

    // script to validate form
    $('#formCampaign').on('submit', function (e) {
        if (!validateCampaignForm(this)) {
            e.preventDefault();
        }
    });

    // toastr options
    toastr.options.preventDuplicates = true;

    $('.campaignForm').on('submit', function (e) {
        var dataForm = $(this).serializeArray();
        var isOk = true;
        var error;
        var field = [];

        dataForm.map(item => {
            if (item.name == "error") {
                error = item.value;
            }
            if (item.value == "") {
                $('.note-editor').css('border','1px solid #dc3545');
                const key = item.name.split('[]').join("");
                if(!field.includes(key)) field.push(key);
                isOk = false;
            } else {
                $('input[name="'+item.name+'"]').css('border','1px solid #ced4da');
                $('.note-editor').css('border','1px solid #ced4da');
            }
        });

        if (!isOk) {
            var inputKey = field.map(i => i.charAt(0).toUpperCase() + i.slice(1)).join(', ');
            toastr.error(error + inputKey);
            return false;
        }

        return true;
    });

    $('#sub_company').on('change', function () {
        var id = $(this).val();
        $('input[name="company"]').val(id);
    });

    var companyId = $('input[name="company"]').val();
    if (companyId) {
        $('#sub_company').val(companyId).trigger('change');
    }

    $("#channel").select2({
        placeholder: "Please choose one or more channels",
        allowClear: true
    });

    var channel = $('input[name="defaultChannel"]').val();
    if (channel) {
        console.log(channel);
        $('#channel').val(channel.split(',')).trigger('change');
    }

    $('textarea').each(function(){
        $(this).val($(this).val().trim());
    });

    $("#addContent").on('click', function () {
        loadContentForm($(this));
    });

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

    $(".sms_oneclick_content").attr('disabled', 'disabled');

    $('.smsoneclick').on('change', function(){
        var val = $(this).val();
        if (val == 1) {
            $('.sms_oneclick_content').removeAttr('disabled');
        } else {
            $('.sms_oneclick_content').attr('disabled', 'disabled');
        }
    });

    $(".error-message").hide();
    $('.changeVolume').on('input', function() {
       var volumeMax = $(this).data('volume-max');
       var val = $(this).val();
       if (val > volumeMax) {
           $(this).val(volumeMax);
           $(this).css('border','1px solid #dc3545');
           $('.error').empty();
           $(this).after('<div class="error" style="color:#dc3545;font-size:13px;">Volume max '+volumeMax+'</div>');
           $('.btn-success').attr('disabled','disabled');
       } else {
           $(this).css('border','1px solid #ddd');
           $('.error').empty();
           $('.btn-success').removeAttr('disabled');
       }
    });

    $('.smsoneclick').on('change', function(){
        var val = $(this).val();
        if (val == 1) {
            $('input[name="mobile_url_redirect"]').attr('required', 'required');
        } else {
            $('input[name="mobile_url_redirect"]').removeAttr('required');
        }
    });

    $.validator.setDefaults({
        submitHandler: function () {
            return true;
        }
    });

    // content validation
    $('#campaignForm').validate({
        rules: {
            sender: {
                required: true,
            },
            object: {
                required: true,
            },
            html: {
                required: true,
            },
            mobile_expediteur: {
                required: true,
            },
            mobile_message: {
                required: true,
            },
            text: {
                required: true,
            }
        },
        messages: {
            sender: "Please enter a sender",
            object: "Please enter objet",
            html: "Please enter html content",
            mobile_expediteur: "Please enter a mobile expediteur",
            mobile_message: "Please enter a mobile message",
            text: "Please enter a text",
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    if (/campaign/.test(window.location.href)) {
        loadCampaignValidation();
    }

    $('select.select2').select2();

    $('.loader-validation-campaign').hide();
    $(document).on('click', '.validateCampaign', function(){
        var campaignId = $(this).data('campaign-id');

        $.ajax({
            type: 'GET',
            url: `/campaign/validation/${campaignId}`,
            beforeSend: function() {
                $('.loader-validation-campaign .loader').show();
                $('.loader-validation-campaign').show();
            },
            success: function(res) {
                const response = JSON.parse(res);
                if (response.error) {
                    toastr.error(response.error);
                    $('.loader-validation-campaign').hide();
                }
                if (response.status == 200) {
                    toastr.success(response.success);
                    $('.loader-validation-campaign').hide();
                }
            },
            error: function(err) {
                if (err.status == 500) {
                    toastr.error('An error is occured');
                    $('.loader-validation-campaign').hide();
                }
            }
        });

        return false;
    });

});

/**
* segmentation
*/
function addSegmentation(segmentation_id,campaign_id){
    $.ajax({
        type: "GET",
        url: '/campaign/segmentationItem/' + segmentation_id + '/' + campaign_id,
        async: false,
        success: function (data) {
            var dataParsed = JSON.parse(data);
            var id = dataParsed.id;

            $('#row-add-segmentation').before(dataParsed.html);
            var mySlider = new Slider('.slider-'+id);

            mySlider.on("slide", function([min,max])
            {   
                $("#age_min-" + id).val(min);
                $("#age_max-" + id).val(max);
                document.getElementById("segmentationValue-"+id).textContent = min + " - " + max;
            });

            loadCampaignValidation();
            initSegmentationCounter();

            $('#formSegmentation-' + id).on('submit',()=>{
                event.preventDefault();
                submitFormSegmentation(id);
            });

            $('#row-delete-' + id).on('click',()=>{
                event.preventDefault();
                $.ajax({
                    type: "GET",
                    url: '/campaign/segmentation/delete/' + id,
                    success:function(){
                        toastr.success('delete success');
                        $( "#row-segment-" + id ).remove();
                        loadCampaignValidation();
                        initSegmentationCounter();
                    },
                     error:(data) => {
                        toastr.error('delete error. Please try later !');
                     }
                });
            });

            setTimeout(() => {

                $("#auto_owned-" + id).select2();
                $("#auto_owned-" + id).on('select2:select select2:unselect', function (e) {
                    initSegmentationCounter();
                    submitFormSegmentation(id,true);
                });
                $('#formSegmentation-' + id + ' input').change(function(){
                    initSegmentationCounter();
                    submitFormSegmentation(id,true);
                })
            }, 500);

        }
    });
}

function submitFormSegmentation(id,quite=false){
    $.ajax({  
         url:$('#formSegmentation-' + id).attr('action'),
         method:"POST",  
         data:$('#formSegmentation-' + id).serialize(),
         success:(data) => {

            loadCampaignValidation();
            if(!quite){
                toastr.success('update success');
                initSegmentationCounter();
            }
         },
         error:(data) => {
            toastr.error('update error. Please try later !');
         }
    }); 
}

function toogleCollapseSegments(){
    const slidedUp=$('.tooglable-item[style*="display: none"]').length;
    const slidedDown=$('.tooglable-item').length -slidedUp;
    if(slidedDown>0){
        $('.tooglable-item').slideUp();
    }else{
        $('.tooglable-item').slideDown();
    }
}

function countSegmentation(campaignId){
    // hide loader before send ajax
    $('.segmentation-step').hide();

    // load campaign count segmentation
    $.ajax({
        type: "GET",
        url: '/campaign/segmentation/count/'+campaignId,
        async: true,
        dataType: 'json',
        beforeSend: function () {
            $('.wait-segmentation-step').show();
        },
        success: function (response) {
            $('.result-segmentation-step').html(response.message);
            if(response.total == 0){
                $('.wait-segmentation-step').hide();
                $('.result-segmentation-step').show();
                $('.count-segmentation-step').show();
            }else{
                $('.wait-segmentation-step').hide();
                $('.result-segmentation-step').show();
                $('.next-segmentation-step').show();
            }
        },
        error: function (error) {
            $('.wait-segmentation-step').hide();
            $('.count-segmentation-step').show();
        }
    });
}

function initSegmentationCounter(){
    $('.segmentation-step').hide();
    $('.count-segmentation-step').show();
}

document.addEventListener("DOMContentLoaded", function() {
    if($('.segmentationContainer').length===0) return;
    var  segments = $('.segmentationContainer').data('segments');

    if(segments.length>0){
        segments.forEach(({id,campaign_id})=>{
            addSegmentation(id,campaign_id);
        })
    }else addSegmentation(0,$('#addSegmentation').data('campagn-id'));

})

$('#addSegmentation').on('click',(e)=>{
    e.preventDefault();
    addSegmentation(0,$('#addSegmentation').data('campagn-id'))
})

/**
* end segmentation
*/

function activateTabs() {
    var campaign_id = $('#tabsCampaignContainer').data('id');
    var tabs = '#navTabChannel, #navTabSegmentation, #navTabVisual, #navTabPlanning, #navTabValidation';

    if (campaign_id) {
        $(tabs).removeClass('disabled');
        $('#navTabCampaign').parents('li').addClass('exists');
        if ($('#tableChannels tbody tr').length > 0) {
            $('#navTabChannel').parents('li').addClass('exists');
        }
        if ($('#tableVisuals tbody tr').length > 0) {
            $('#navTabVisual').parents('li').addClass('exists');
        }
        if ($('#tabPlanning').data('id') != '') {
            $('#navTabPlanning').parents('li').addClass('exists');
        }
    } else {
        $(tabs).addClass('disabled');
    }
}

function submitFormCampaign(form) {
    var url = $(form).attr('action');
    campaign_id = $('#tabsCampaignContainer').data('id');
    form = $(form).serialize();
    form = form + '&campaign_id=' + campaign_id;
    $.ajax({
        type: "POST",
        url: url,
        data: form,
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                if (res.action === 'saveCampaign' && res.redirect) {
                    window.location.replace(res.redirect);
                    return false;
                } else if (res.action === 'saveChannel') {
                    $('#tableChannels tbody').empty();
                    $('#tableChannels tbody').append(res.html);
                } else if (res.action === 'saveVisual') {
                    $('#formVisual').trigger("reset");
                    $('#formVisualContainer').toggle();
                    $('#tableVisuals tbody').empty();
                    $('#tableVisuals tbody').append(res.html);
                } else if (res.action === 'savePlanning') {
                    $('#tabPlanning').attr('data-id', res.planning_id);
                }
                //$('#tabsCampaignContainer').attr('data-id', res.campaign_id);
                activateTabs();
            }
        }
    });
}
function showHtmlEditor() {
    var channel_id = $('#dopdownCampaignChannelIds').val();
    var url = $('#dopdownCampaignChannelIds').data('url');
    $('#previewVisualContainer').remove();

    $.post(url, {channel_id: channel_id}, function (data) {
        if (data == 1) {
            iframe = '<div class="row" id="previewVisualContainer"><div class="col-sm-12"><div class="card card-body"><iframe id="previewVisual"></iframe></div></div></div>';
            $(iframe).insertAfter('#codeVisualContainer');
            setTimeout(updatePreview, 300);
        }
    });
}
var delay;
var nonEmpty = false;
/*
var editor = CodeMirror.fromTextArea(document.getElementById('codeVisual'), {
    mode: 'htmlmixed',
    styleActiveLine: true,
    lineNumbers: true,
    theme: "dracula",
    autoCloseTags: true
});

editor.on("change", function () {
    clearTimeout(delay);
    delay = setTimeout(updatePreview, 300);
});
*/

function updatePreview() {
    var previewFrame = document.getElementById('previewVisual');
    if (previewFrame) {
        var preview = previewFrame.contentDocument || previewFrame.contentWindow.document;
        preview.open();
        preview.write(editor.getValue());
        preview.close();
    }
}
setTimeout(updatePreview, 300);

function toggleSelProp() {
    nonEmpty = !nonEmpty;
    editor.setOption("styleActiveLine", {nonEmpty: nonEmpty});
    var label = nonEmpty ? 'Disable nonEmpty option' : 'Enable nonEmpty option';
    document.getElementById('toggleButton').innerText = label;
}

/**
 * Validate campaign form
 * @param $form
 * @returns {boolean}
 */
function validateCampaignForm($form) {
    var data = $($form).serializeArray();

    var isOk = true;
    var error;
    $.each(data, function (k, v){
        if (v.name == "error") {
            error = v.value;
        }
        if (v.value == "") {
            $('input[name="'+v.name+'"]').css('border','1px solid #dc3545');
            isOk = false;
        } else {
            $('input[name="'+v.name+'"]').css('border','1px solid #ced4da');
        }
    });

    if (!isOk) {
        toastr.error(error); 
        return false;
    } 

    return true;
}

function ajaxCampaignDelete(event, id, msgConfirm, msgSuccess, msgError) {
    event.preventDefault();
    if (confirm(msgConfirm)) {
        $.ajax({
            type : 'GET',
            url : '/campaign/delete/' + id,
            success : function(res, statut) {
                toastr.success(res.message, msgSuccess, toastr_options);
                window.location.href = '/campaign/list';
            },
            error : function (request, status, error) {
                toastr.error(error.message, msgError);
            },
            dataType : 'json'
        });
    } else
        return;
}

/**
 * Load validation with ajax
 */
function loadCampaignValidation() {
    // hide loader before send ajax
    $('.ajax-loader').hide();

    var parts = window.location.href.split('/');
    var campaignId = parts.pop();

    // load campaign validation
    $.ajax({
        type: "GET",
        url: '/campaign/validation/reload/'+campaignId,
        async: true,
        dataType: 'html',
        beforeSend: function () {
            $('.ajax-loader').show();
        },
        success: function (response) {
            $('#card-validation-ajax').html(response);
            $('.ajax-loader').hide();
        },
        error: function (error) {
            if (error.status == 404) {
                $(".ajax-loader").hide();
                $("#card-validation-ajax").html('<span class="d-flex justify-content-center">Nothing to show!</span>');
            }
        }
    });
}

/**
 * Load content form with ajax
 * @param $this
 */
function loadContentForm($this) {

    $this.text("Add content ...");

    // load new content with ajax
    $.ajax({
        type: "GET",
        url: '/campaign/content/load',
        async: true,
        dataType: 'html',
        beforeSend: function () {
            //$('.ajax-loader').show();
        },
        success: function (response) {
            $('#ajax-content').append(response);
            $('.summernote').summernote();
            $('select.select2').select2();

            $this.text("Add content");
        },
        error: function (error) {
            if (error.status == 404) {
                $(".ajax-loader").hide();
                $("#card-validation-ajax").html('<span class="d-flex justify-content-center">Nothing to show!</span>');
            }
        }
    });
}


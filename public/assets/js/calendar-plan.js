$(document).ready(function() {
  var startDateSelected = $('#startDate').attr('data');
  var endDateSelected = $('#endDate').attr('data');
  if (startDateSelected) {
    var ladate = new Date(startDateSelected);
  } else if (endDateSelected) {
    var ladate = new Date(endDateSelected);
  } else {
    var ladate = new Date();
  }
  var currentDate = Date.UTC(
    ladate.getFullYear(),
    ladate.getMonth(),
    ladate.getDate(),
    ladate.getHours(),
    ladate.getMinutes(),
    ladate.getSeconds()
  );
  var positionCompany = $('#companySelected').attr('positionCompany');
  
  var dataCommunicationPlan = $('#calendar').attr('data-communication-plan');
  var dataCommunicationPlan = JSON.parse(dataCommunicationPlan);
  var customEvents = [];
  var k = 0;
  $.each(dataCommunicationPlan , function(index, val) {
    if (val.visual_name == 'externalCampaign') {
      var visualName = val.name;
      var url = '/communicationplan/detail/' + val.id;
    } else {
      var visualName = val.subject;
      var url = '/campaign/detect/' + val.id;
    }
    if (val.startDate) {
      customEvents[k] = {
        id: val.id,
        title: visualName,
        start: val.startDate,
        end: val.endDate,
        url: url,
        color: colorCampaign(positionCompany)
      };
      k++;
    }
  });

  $('#calendar').fullCalendar({
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay,listWeek'
    },
    defaultDate: currentDate,
    navLinks: true,
    editable: true,
    eventLimit: true,
    events: customEvents
  });
});

function colorCampaign(positionCompany) {
  switch(positionCompany) {
    case '1':
      return '#ff9f89';
      break;
    case '2':
      return '#FFA500';
      break;
    case '3':
      return  '#3a87ad';
      break;
    default:
      return '#3a87ad';
  }
}

$('#externeCampaignModal').on('show.bs.modal', function (e) {
  if ($(document).width() <= 480 ){
    $('#externeCampaignModal').css({
      'width': '95%',
      'left': '0%',
      //'right': '5%'
    });
  } else if ($(document).width() <= 780 ) {
    $('#externeCampaignModal').css({
      'width': '85%',
      'left': '15%',
      'right': '10%'
    });
  } else {
    $('#externeCampaignModal').css({
      'width': '40%',
      'left': '30%',
      'right': '20%'
    });
  }
});

$(document).resize(function(){
  resizeModalExternalCampaign(this);
});

$(window).resize(function(){
  resizeModalExternalCampaign(this);
});

function resizeModalExternalCampaign(object){
  if ($(object).width() <= 480 ){
    $('#externeCampaignModal').css({
      'width': '95%',
      'left': '0%',
      //'right': '5%'
    });
  } else if ($(object).width() <= 780 ) {
    $('#externeCampaignModal').css({
      'width': '80%',
      'left': '10%',
      'right': '10%'
    });
  } else {
    $('#externeCampaignModal').css({
      'width': '40%',
      'left': '30%',
      'right': '20%'
    });
  }
}

function ajaxExterneCampaignSave(event, companyId, msgConfirm, msgSuccess, msgError) {
  if (confirm(msgConfirm)) {
    var nameCampaign = $('#nameCampaign').val();
    var startDateCampaign = $('#startDateCampaign').val();
    var endDateCampaign = $('#endDateCampaign').val();
    var data = {
      'nameCampaign': nameCampaign,
      'companyId': companyId,
      'startDateCampaign': startDateCampaign,
      'endDateCampaign': endDateCampaign
    };
    $.ajax({
      type: 'POST',
      url: '/communicationplan/ajaxExternalCampaignSave',
      data: 'data=' + JSON.stringify(data),
      success: function (res, statut) {
        $('#externeCampaignModal').hide();
        toastr.success(res.message, msgSuccess, toastr_options);
      },
      complete: function (res, statut) {
        window.location.reload();
      },
      error: function (request, status, error) {
        toastr.error(error.message, msgError);
      },
      dataType: 'json'
    });
  } else {
      return;
  }
}



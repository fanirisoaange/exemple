$(document).ready(function() {
  var donutData = {
    labels: [
      $('#pieChart').attr('legendLabelMail') + $('#pieChart').attr('qtyCampaignEmailExecuted'),
      $('#pieChart').attr('legendLabelSMS') + $('#pieChart').attr('qtyCampaignSMSExecuted'),
    ],
    datasets: [
      {
        data: [$('#pieChart').attr('qtyCampaignEmailExecuted'), $('#pieChart').attr('qtyCampaignSMSExecuted')],
        backgroundColor : ['#3c8dbc', '#f56954'],
        label: 'My dataset rty'
      }
    ]
  }
  
  //-------------
  //- PIE CHART -
  //-------------
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
  var pieData        = donutData;
  var pieOptions     = {
    maintainAspectRatio : false,
    responsive : true,
  }

  var pieChart = new Chart(pieChartCanvas, {
    type: 'pie',
    data: pieData,
    options: pieOptions      
  })

  //-------------
  //- BAR CHART -
  //-------------
  var areaChartData = {
    labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Aout'],
    datasets: [
      {
        label               : $('#barChart').attr('legendLabelLead'),
        backgroundColor     : 'rgba(60,141,188,0.9)',
        borderColor         : 'rgba(60,141,188,0.8)',
        pointRadius          : true,
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : [28000, 48000, 40000, 19000, 86000, 27000, 90000, 125252]
      },
    ]
  }
  var barChartCanvas = $('#barChart').get(0).getContext('2d')
  var barChartData = jQuery.extend(true, {}, areaChartData)
  var temp0 = areaChartData.datasets[0]
  barChartData.datasets[0] = temp0

  var barChartOptions = {
    responsive              : true,
    maintainAspectRatio     : false,
    datasetFill             : false
  }

  var barChart = new Chart(barChartCanvas, {
    type: 'bar', 
    data: barChartData,
    options: barChartOptions
  })
});

$('#startDate, #endDate, #users, #campaignChannelType').change(function (e) {
  var campaignChannelType =  $('#campaignChannelType').val();
  var user = $('#users').val();
  var startDate = $('#startDate').val();
  var endDate = $('#endDate').val();
  var data = {'campaignChannelType': campaignChannelType, 'user': user, 'startDate': startDate, 'endDate': endDate};
  $.ajax({
      type : 'POST',
      data : 'data=' + JSON.stringify(data),
      url : '/dashboard/getCampaignByAjax',
      beforeSend: function() {
        $('html').css('cursor', 'wait');
      },
      success : function(res, statut) {
          $('#labelStartDateEndDate').text(res.labelStartDateEndDate);
          $('#qtyCampaignEmailSend').text(res.qtyCampaignEmailSend);
          $('#qtyCampaignSMSSend').text(res.qtyCampaignSMSSend);
          if (res.mailEnabled == false) {
            $('#divMailSent').hide();
            $('#divSMSSent').show();
          }
          if (res.smsEnabled == false) {
            $('#divSMSSent').hide();
            $('#divMailSent').show();
          }
          if (res.mailEnabled == true && res.smsEnabled == true) {
            $('#divSMSSent').show();
            $('#divMailSent').show();
          }
          $('#budget').text(res.budget);
          $('#title-card-piechart').text(res.titleCardPiechart);

          var donutData = {
            labels: [
              $('#pieChart').attr('legendLabelMail') + res.numberMailExecuted,
              $('#pieChart').attr('legendLabelSMS') + res.numberSMSExecuted,
            ],
            datasets: [
              {
                data: [res.numberMailExecuted, res.numberSMSExecuted],
                backgroundColor : ['#3c8dbc', '#f56954'],
                label: 'My dataset rty'
              }
            ]
          }
          
          //-------------
          //- PIE CHART -
          //-------------
          var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
          var pieData        = donutData;
          var pieOptions     = {
            maintainAspectRatio : false,
            responsive : true,
          }
        
          var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions      
          })
      },
      error : function (request, status, error) {
          toastr.error(error.message, 'Error');
      },
      complete: function() {
        $('html').css('cursor', 'default');
      },
      dataType : 'json'
  });
});

function getCampaignByAjax(period) {
  var campaignChannelType =  $('#campaignChannelType').val();
  var user = $('#users').val();
  var startDate = $('#startDate').val();
  var endDate = $('#endDate').val();
  var data = {'campaignChannelType': campaignChannelType, 'user': user, 'startDate': startDate, 'endDate': endDate};
  $.ajax({
    type : 'POST',
    data : 'data=' + JSON.stringify(data),
    url : '/dashboard/getCampaignByAjax/' + period,
    beforeSend: function() {
      $('html').css('cursor', 'wait');
    },
    success : function(res, statut) {
        $('#labelStartDateEndDate').text(res.labelStartDateEndDate);
        $('#qtyCampaignEmailSend').text(res.qtyCampaignEmailSend);
        $('#qtyCampaignSMSSend').text(res.qtyCampaignSMSSend);
        if (res.mailEnabled == false) {
          $('#divMailSent').hide();
          $('#divSMSSent').show();
        }
        if (res.smsEnabled == false) {
          $('#divSMSSent').hide();
          $('#divMailSent').show();
        }
        if (res.mailEnabled == true && res.smsEnabled == true) {
          $('#divSMSSent').show();
          $('#divMailSent').show();
        }
        $('#budget').text(res.budget);
        $('#title-card-piechart').text(res.titleCardPiechart);

        $('#btn-month').removeClass('btn-primary');
        $('#btn-month').removeClass('active');
        $('#btn-quater').removeClass('btn-primary');
        $('#btn-quater').removeClass('active');
        $('#btn-year').removeClass('btn-primary');
        $('#btn-year').removeClass('active');
        if (res.period == 1) {
          $('#btn-month').removeClass('btn-secondary');
          $('#btn-month').addClass('btn-primary');
          $('#btn-month').addClass('active');
          $('#btn-quater').addClass('btn-secondary');
          $('#btn-year').addClass('btn-secondary');
        } else if (res.period == 3) {
          $('#btn-quater').removeClass('btn-secondary');
          $('#btn-quater').addClass('btn-primary');
          $('#btn-quater').addClass('active');
          $('#btn-month').addClass('btn-secondary');
          $('#btn-year').addClass('btn-secondary');
        } else if (res.period == 12) {
          $('#btn-year').removeClass('btn-secondary');
          $('#btn-year').addClass('btn-primary');
          $('#btn-year').addClass('active');
          $('#btn-quater').addClass('btn-secondary');
          $('#btn-month').addClass('btn-secondary');
        }

        var donutData = {
          labels: [
            $('#pieChart').attr('legendLabelMail') + res.numberMailExecuted,
            $('#pieChart').attr('legendLabelSMS') + res.numberSMSExecuted,
          ],
          datasets: [
            {
              data: [res.numberMailExecuted, res.numberSMSExecuted],
              backgroundColor : ['#3c8dbc', '#f56954'],
              label: 'My dataset rty'
            }
          ]
        }
        
        //-------------
        //- PIE CHART -
        //-------------
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData        = donutData;
        var pieOptions     = {
          maintainAspectRatio : false,
          responsive : true,
        }
      
        var pieChart = new Chart(pieChartCanvas, {
          type: 'pie',
          data: pieData,
          options: pieOptions      
        })
    },
    error : function (request, status, error) {
        toastr.error(error.message, 'Error');
    },
    complete: function() {
      $('html').css('cursor', 'default');
    },
    dataType : 'json'
  });
}
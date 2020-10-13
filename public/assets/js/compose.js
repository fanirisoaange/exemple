$(document).ready(function () {


	$(".select2").select2();
   $('#compose-textarea').summernote({
   	height: 300
   });

   if($("#draftTo").length) {
   	if($("#draftTo").val().indexOf(";") > 0) {
   	
   		$("#composeTo").val($("#draftTo").val().split(";")).change();
   	}
   	else {

   		$("#composeTo").val([$("#draftTo").val()]).change();
   	}
   }

   $('#composeTo').on("select2:selecting", function(e) { 
  		$(this).next("span").css("border", "none");
	});

   $("#subject").change(function() {
   	$(this).css("border", "1px solid #ced4da");
   })
   $("#attachments").click(function(e) {

       e.preventDefault();
        var nb_attachments = $('form input').length;
        var $input = $('<input type="file" name=attachments[]>');
        $input.on('change', function(evt) {
          var f = evt.target.files[0];
          $('form').append($(this));
          $('#attachmentList').append('<span style="margin-right: 0.6em"><strong>' + f.name + '</strong> (' + getReadableFileSizeString(f.size) + ')</span>');
        });
        $input.hide();
        $input.trigger('click');
      });

   function getReadableFileSizeString(fileSizeInBytes) {
       var i = -1;
       var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
       do {
           fileSizeInBytes = fileSizeInBytes / 1024;
           i++;
       } while (fileSizeInBytes > 1024);

       return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
   };
 

   $("#btnSend").click(function(e) {
   		e.preventDefault();
   		var b = 1;

   		$("#composeTo").next("span").css("border", "1px solid none");
   		$("#subject").css("border", "1px solid #ced4da");

   		
   		
   		if($("#composeTo").select2("data").length == 0) {
   			b = 0;
   			$("#composeTo").next("span").css("border", "1px solid #ba160d");
   		}
   		if(!$("#subject").val()) {
   			b = 0;
   			$("#subject").css("border", "1px solid #ba160d");
   		}

   		if(b)
   			$(this).parents("form")[0].submit();
   })
    $("#btnDraft").click(function(e) {
   		e.preventDefault();
   		var b = 1;

   		$("#composeTo").next("span").css("border", "1px solid none");
   		$("#subject").css("border", "1px solid #ced4da");

   		
   		
   		if($("#composeTo").select2("data").length == 0) {
   			b = 0;
   			$("#composeTo").next("span").css("border", "1px solid #ba160d");
   		}
   		if(!$("#subject").val()) {
   			b = 0;
   			$("#subject").css("border", "1px solid #ba160d");
   		}

   		if(b) {
   			$("#formMethod").val("draft");
   			$(this).parents("form")[0].submit();
   		}
   })
});
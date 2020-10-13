$(document).ready(function() {
	if($("#session_message").val())
		toastr.success($("#session_message").val());

	$("#btnSubmit").click(function(e) {
		e.preventDefault();
		var b = 1;
		$("form input").each(function() {
			$(this).val($(this).val().replace(",", "."));
			if($(this).val() && ($(this).val() < 0 || isNaN($(this).val()))) {
				b = 0;
				$(this).css("border", "1px solid red");
			}
		})
		if(b) {
			$("form")[0].submit();
		}
		else {
			toastr.error('Fields must be greater than or equal to zero numbers only.');		
		}
	})
})
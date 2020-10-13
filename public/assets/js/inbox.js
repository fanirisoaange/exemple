$(document).ready(function () {

	if($("#session_message").val())
		toastr.success($("#session_message").val());

	$(".btnReply").click(function() {

		if($(".checkRow:checked").length == 1)
			window.location.href = "/inbox/replyTo/" + $(".checkRow:checked").data("id");
	})
	$(".btnForward").click(function() {

		if($(".checkRow:checked").length == 1)
			window.location.href = "/inbox/forward/" + $(".checkRow:checked").data("id");
	})

	$("#searchMail").keyup(function(e) {
		if(e.keyCode == 13) {
			$("#mailboxPage").val(1);
			search();
		}
	})

	$(".btnCheck").click(function() {

		if($(this).find("i").hasClass("fa-square")) {

			$(".btnCheck").find("i").attr("class", "far fa-check-square");
			$(".checkRow").prop("checked", true);
		
		}
		else {
			$(".checkRow").prop('checked', false);
			$(".btnCheck").find("i").attr("class", "far fa-square");
		}

		
	})
	$("#searchBtn").click(function() { $("#mailboxPage").val(1); search(); });

	function search() {
		$.ajax({
			type: "GET",
			url: '/inbox/search?q=' + $("#searchMail").val() + "&p=" + $("#mailboxPage").val(),
			success: function(res) {
				var res = JSON.parse(res);
				if(res.nextPage) {

				}
				loadTable(res.messages);
				$(".currentCount").html(res.count);
				$(".totalCount").html(res.count);
			}
		})
	}

	$(".btnPrint").click(function() {
			$("#cardRead .mailbox-controls").hide();
			$("#cardRead .card-footer").hide();

			var printContents = document.getElementById("cardRead").innerHTML;
     		var originalContents = document.body.innerHTML;
     		document.body.innerHTML = printContents;
     		window.print();
     		document.body.innerHTML = originalContents;

     		$("#cardRead .mailbox-controls").show();
			$("#cardRead .card-footer").show();
	});




	function loadTable(res) {
		if(res.length) {
			$(".mailbox-controls").show();
			$("#noResults").hide();
		}
		else {
			$(".mailbox-controls").hide();
			$("#noResults").show();
		}
		$(".mailbox-messages tr:not(#mailboxTrSample)").remove();
				for(r in res) {
					var item = res[r];
					var tr = $("#mailboxTrSample").clone().attr("id", "");
					tr.show();
					if(item.seen == 0) {
						tr.find(".mailbox-name a").css("font-weight", "bold");
					}
					if(item.type == "recipient")
						tr.find(".mailbox-name a").html(item.sender).attr("href", tr.find(".mailbox-name a").attr("href") + "/inbox/read/" + item.id);
					else 
						tr.find(".mailbox-name a").html(item.recipient).attr("href", tr.find(".mailbox-name a").attr("href") + "/inbox/compose/" + item.id);

					
					if(item.favorite == 0)
						tr.find(".mailbox-star i").hide();

					tr.find(".checkRow").data("id", item.id).attr("id", "check" + item.id);
					tr.find(".icheck-primary label").attr("for", "check" + item.id);
					tr.find('.mailbox-subject b').html(item.subject);
					tr.find('.mailbox-subject span').html(item.content);
					tr.find(".mailbox-date").html(item.send_at);
					if(!item.attachments)
						tr.find(".mailbox-attachment i").hide();

					$(".mailbox-messages tbody").append(tr);

				}
				$(".mailbox-messages").css("opacity", 1);
	} 
	$(".btnRefresh").click(function() {
		$(".mailbox-messages").css("opacity", 0.6);
		refresh();
	})

	function refresh() {

		$.ajax({
			type: "GET",
			url: "/inbox/refresh?type=" + $("#pageType").val(),
			success: function(res) {
				loadTable(JSON.parse(res));
			}
		})
	}

	$(".deleteMultipleTrashBtn").click(function() {

		if($(".checkRow:checked").length) {
			if(confirm("Set selected items to trash ?")) {

				id = "";
				c = 0;
				$(".checkRow:checked").each(function() {
					id+= $(this).data("id") + ";";
					c++;
				})
				$.ajax({
					type: "POST",
					url: "/inbox/putTrash",
					data: { id: id },
					success: function(res) {
						res = JSON.parse(res);

						if(res.status == 200) {
							refresh();
							toastr.success("Items set to trash successfully.");

							$(".currentCount").html(parseInt($(".currentCount").html()) - c);
							$(".totalCount").html(parseInt($(".totalCount").html()) - c);
						}
					}
				})
				
			}
		}
	});

	$(".deleteBtn").click(function() {
		if(confirm("Delete this message?")) {
			window.location.href = $(this).data("href");
		}
	});

	$(".btnFavorite").click(function() {
		var f = Math.abs($(this).data("fav") - 1);
		
		var el = $(this);
		$.ajax({
			type: "POST",
			url: "/inbox/setFavorite",
			data: { id: $(this).data("id"), fav: f},
			success: function(res) {
				if(f)
					el.find("i").addClass("text-warning");
				else
					el.find("i").removeClass("text-warning");
				el.data("fav", f);
			}
		})
	})
	$(".deleteMultipleBtn").click(function() {
			if($(".checkRow:checked").length) {
				if(confirm("Delete selected items definitely ?")) {

					id = "";
					c = 0;
					$(".checkRow:checked").each(function() {
						id+= $(this).data("id") + ";";
						c++;
					})
					$.ajax({
						type: "POST",
						url: "/inbox/delete",
						data: { id: id },
						success: function(res) {
							res = JSON.parse(res);

							if(res.status == 200) {
								refresh();
								toastr.success("Items deleted successfully !");



								$(".currentCount").html(parseInt($(".currentCount").html()) - c);
								$(".totalCount").html(parseInt($(".totalCount").html()) - c);
							}
						}
					})
					
				}
			}
	});
});
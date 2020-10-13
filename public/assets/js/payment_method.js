$(document).ready(function() {
	if($("#session_message").val())
        toastr.success($("#session_message").val());
    
	$('input:radio').change(function() { 
		$.ajax({
			type: "POST",
			url: 'payment_method/setActive',
			data: { id: $(this).parents("tr").data("id")},
			success: function(res) {
				toastr.success($("#tradPmActive").val());
			}
		})
	});

	$(".btnShowDelete").click(function() {
		$("#modalDeletePaymentMethod").data("id", $(this).parents("tr").data("id")).modal({show: true});
	})
	$(".btnDelete").click(function() {
		var id = $("#modalDeletePaymentMethod").data("id");
		$.ajax({
			type: "POST",
			url: 'payment_method/delete',
			data: { id: id},
			success: function(res) {
				res = JSON.parse(res);
				$("#modalDeletePaymentMethod").modal("hide");
				$("tr[data-id=" + id + "]").remove();
				if(res.success)
					toastr.success($("#tradPmDelete").val());
				else
					toastr.error($("#tradPmUnable").val());
			}
		})
	})
	$("#addPaymentMethodBtn").click(function() {
			$.ajax({
				type: "GET",
				url: "payment_method/setupIntent",
				success: function(res) {
					res = JSON.parse(res);
					$("#card-button")[0].dataset.secret = res.client;

					var cardButton = document.getElementById('card-button');
					var clientSecret = cardButton.dataset.secret;

					cardButton.addEventListener('click', function(ev) {
						if($(this).data("disabled") != "false") {
							$("#modalAddPaymentMethod .error").html("");
							$(this).data("disabled", true);
							$(".modal-body").css("opacity", 0.4);
					
							 stripe.confirmCardSetup(
							    clientSecret,
							    {
							      payment_method: {
							        card: cardNumber,
							        billing_details: {
							        }		        
							      },
							    }
							  ).then(function(result) {
								  	$(".modal-body").css('opacity', 1);
								  	cardButton.dataset.disabled = false;
								    if (result.error) {
								     	$("#modalAddPaymentMethod .error").html(result.error.message)
								    } else {
								      	$.ajax({
									      	type: "POST",
									      	url: "payment_method/create",
									      	data: { pm: result.setupIntent.payment_method, type: "card"},
									      	success: function(res) {

									      		res = JSON.parse(res);
									      		if(res.success) {
									      			window.location.reload();
									      		}
									      		else if(res.error) {
									      			toastr.error(res.error);
									      			$("#modalAddPaymentMethod").modal("hide");
									      		}

									      		
									      	}
								      	})    
								  	}
							  	});
						}				  	
					});
					$("#modalAddPaymentMethod").modal({show: true});
				}
			})
		
	})
	var stripe = Stripe($("#stripe_key").val());	
	var elements = stripe.elements({
	    fonts: [
	      {
	        cssSrc: 'https://fonts.googleapis.com/css?family=Quicksand',
	      },
	    ],
	    locale: window.__exampleLocale,
	  });

   var elementStyles = {
	    base: {
	      color: '#46464a',
	      fontWeight: 500,
	      top: '5px!important',
	      fontFamily: 'Montserrat, Open Sans, Segoe UI, sans-serif',
	      fontSize: '13px',
	      fontSmoothing: 'antialiased',
	      ':focus': {
	        color: '#8a91a1'
	      },
	      '::placeholder': {
	        color: '#8a91a1',
	      },
	      ':focus::placeholder': {
	        color: '#a5abb8'
	      },
	    },
	    invalid: {
	      color: '#fa755a',
	      border: '1px solid #870808',
	      ':focus': {
	        color: '#FA755A',
	        background: '#870808'
	      },
	      '::placeholder': {
	        color: '#FFCCA5',
	      },
	    },
	  };

  var elementClasses = {
    focus: 'focus',
    empty: 'empty',
    invalid: 'invalid',
  };

  var cardNumber = elements.create('cardNumber', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardNumber.mount('#card-card-number');

  var cardExpiry = elements.create('cardExpiry', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardExpiry.mount('#card-card-expiry');

  var cardCvc = elements.create('cardCvc', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardCvc.mount('#card-card-cvc');

  cardNumber.on('change', function(event) {
	  var displayError = document.getElementById('cardError');
	  if (event.error) {
	    displayError.textContent = event.error.message;
	  } else {
	    displayError.textContent = '';
	  }
	});
  cardExpiry.on('change', function(event) {
	  var displayError = document.getElementById('cardExpiryError');
	  if (event.error) {
	    displayError.textContent = event.error.message;
	  } else {
	    displayError.textContent = '';
	  }
	});
  cardCvc.on('change', function(event) {
	  var displayError = document.getElementById('cardCVCError');
	  if (event.error) {
	    displayError.textContent = event.error.message;
	  } else {
	    displayError.textContent = '';
	  }
	});

function registerElements(elements, exampleName) {
  var formClass = '.' + exampleName;
  var example = document.querySelector(formClass);
  var form = example.querySelector('form');
  var resetButton = example.querySelector('a.reset');
  var error = form.querySelector('.error');

  function enableInputs() {
	    Array.prototype.forEach.call(
	      form.querySelectorAll(
	        "input[type='text'], input[type='email'], input[type='tel']"
	      ),
	      function(input) {
	        input.removeAttribute('disabled');
	      }
	    );
	  }
	}
})
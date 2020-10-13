
function countMessage(){
        $.ajax({
            url:'/Notification/NotificationUnreadCount',
            type:'GET',
            success: function (data,status,xhr) {   // success callback function
                data = JSON.parse(data);
                if(($.isNumeric(data.count)) && (data.count>0)) {
                    $(".all_notif").text(data.count);
                    $(".msg_count").text(data.count);
                     $(".msg_count").parent().removeClass("d-none");
                    $("#badgeUnread").text(data.count);
                }
                
                if(($.isNumeric(data.lastOrder))  && (data.lastOrder>0)){
                    $(".all_notif").text(data.lastOrder);
                    $(".order_notif").text(data.lastOrder);
                     $(".order_notif").parent().removeClass("d-none");
                    $(".ago_txt_msg").text(data.lastOrder);
                }
                
                if(($.isNumeric(data.count)) && ($.isNumeric(data.lastOrder))){
                    $(".all_notif").text(data.count + data.lastOrder);
                }
                
                if(data.lastMessage != null){           
                    var current= new Date(Date.parse(data.lastMessage));
                    var now= new Date();
                    
                    var ago = timeDifference(now, current);
                    $(".ago_txt_msg").text(ago)
                }
                
                if(data.lastNotif != null){         
                    var current= new Date(Date.parse(data.lastNotif));
                    var now= new Date();
                    
                    var ago = timeDifference(now, current);
                    $(".ago_txt_order").text(ago)
                }            
                
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                console.log('Error: ' + errorMessage);
            }
        });
    }
    
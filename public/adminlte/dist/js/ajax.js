$(function() {
	'use strict';

     function msgNotification(){
        $.ajax({
           url:url+'/notification/messages',
           method:'GET',
           success:function(data)
           {
               console.log(data.data);
               for (i in data) {
                    if(i == 'success'){
                        $('.dropdown .msg-menu').html(data.data);
                    }
               }
           }
        })

      }
    
    
     function projectNotification(){
        $.ajax({
           url:url+'/notification/projects',
           method:'GET',
           success:function(data)
           {
               console.log(data);
               var j;
               for (j in data) {
                    if(j == 'success'){
                        $('.dropdown .project-menu').html(data.data);
                    }
               }
           }
        })

      }
    
    window.setInterval(function(){
            msgNotification();
    }, 10000);
    
    /* if user is company or admin or support */
    if(permission == '3' || permission == '0' || permission == '1'){
        window.setInterval(function(){
                projectNotification();
        }, 7000);
    }
    
});







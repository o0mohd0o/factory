$(function() {
	'use strict';
   
    
    $(".select").selectBoxIt( { autoWidth: false } );
    
     $( ".datepicker" ).datepicker({
//          dayNamesMin: [ "الاحد",
//                         "الاثنين",
//                         "الثلاثاء",
//                         "الاربعاء",
//                         "الخميس",
//                         "الجمعه",
//                         "السبت"],
    //     isRTL: true,
         dateFormat: "dd-mm-yy",
     });
////////////////////////////////////////////////////////
     $('.confirm').click(function(){
          return confirm('Do You Want To Save ?');    
    })
///////////////////////////////////////////////////////
	$('[placeholder]').focus(function(){
	$(this).attr('data-text',$(this).attr('placeholder'));
	$(this).attr('placeholder','');
	})
    
	.blur(function(){
        $(this).attr('placeholder',$(this).attr('data-text'));
	})

///////////////////////////////////////////////////////////

	$('input').each(function (){
        if($(this).attr('required') === 'required'){
            $(this).before('<span class="asterisk">*</span>');
        }
    
    })
///////////////////////////////////////////////////////////
    
    $('.pro-page .cont-nav span').click(function(){
       // $(this).addClass('selected').siblings().removeClass('selected');
        $('.pro-page .cont-nav span').removeClass('selected');
        $(this).addClass('selected');
        $('.pro-page .no-show').hide();
        $('.pro-page'+' .' + $(this).data('class')).fadeIn(100);
        //console.log($(this).data('class')));
         //alert('.no-show'+' .' +$(this).data('class') );
    })
    
////////////////////////////////////////////////////////////////////////
    
    var requiredCheckboxes = $('.perm :checkbox[required]');
    requiredCheckboxes.change(function(){
        if(requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
        } else {
            requiredCheckboxes.attr('required', 'required');
        }
    
    });
    
////////////////////////////////////////////////////////////////////////////
    

});  

function myfunc(){
    return confirm('Do You Want To Delete This Item ?'); 
}

function myfuncAr(){
    return confirm('هل تريد الحذف بالفعل ؟ '); 
}


function confirmAr(){
    return confirm('هل انت متأكد ؟ '); 
}

function printfunc() {
  window.print();
}

function tt(){
    alert('dd');
}









 
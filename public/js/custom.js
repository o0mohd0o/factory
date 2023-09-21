$(document).ready(function () {
    //When right click , reset select option to first option
    $(document).on("mousedown", 'select', function (e) {
        if (e.which == 3) {
            e.preventDefault();
            let first_option = $(this).find("option:first-child").val();
            $(this).val(first_option);
        }
    });

    //Disable right click menu
    $(document).bind("contextmenu", function(e) {
        return false;
    });

    
});

 function roundToDecimals(number,  fixedPoint = 3) {
    return Number.parseFloat(number).toFixed(fixedPoint);
  }
  
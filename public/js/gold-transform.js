$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $('#used-items-autocomplete-table').on('click', 'tbody > tr > td.table-borderless > a', function(e){
        e.preventDefault();
        alert('dddd');
        let tr = $(this).closest('tr');
        var clone = tr.clone();
        clone.find(':text').val('');
        tr.after(clone);
    })
});

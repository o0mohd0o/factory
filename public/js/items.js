$(document).ready(function() {
    // DataTable initialisation
    $('#dataTable_invoice thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#dataTable_invoice thead');

    $('#dataTable_invoice').DataTable(
        {
            "language": {
                "url": "js/localization/dataTables_ar.json"
            },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "paging": true,
            "ordering": false,
            "autoWidth": true,
            "orderCellsTop": true,
            "fixedHeader": true,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 5;
                while(j < nb_cols){
                    var pageTotal = api
                        .column( j, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return Number(a) + Number(b);
                        }, 0 );
                    // Update footer
                    $( api.column( j ).footer() ).html(pageTotal);
                    j++;
                }
            },
            initComplete: function () {
                var api = this.api();

                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        // $(cell).html('<input type="text" placeholder="' + title + '" />');
                        $(cell).html('<input type="text" class="fa" placeholder="&#xF002;" />');

                        // On every keypress in this input
                        $(
                            'input',
                            $('.filters th').eq($(api.column(colIdx).header()).index())
                        )
                            .off('keyup change')
                            .on('keyup change', function (e) {
                                e.stopPropagation();

                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();

                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
            }
        }
    );
});

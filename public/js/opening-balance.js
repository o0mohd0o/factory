$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var rowcount, tableBody, basePath;
    rowcount = $("#opening-balance-autocomplete-table tbody tr").length + 1;
    tableBody = $("#opening-balance-autocomplete-table tbody");
    basePath = $("#base_path").val();

    function formHtml() {
        html = '<tr class="addrow" id="row-' + rowcount + '">';
        html +=
            '<td><input type="text" id="kind-' +
            rowcount +
            '" data-field-name="code" class="form-control autocomplete_txt" autofill="off" autocomplete="off" name="kind[]"></td>';
        html +=
            '<td><input type="text" id="kind-name-' +
            rowcount +
            '" class="form-control autocomplete_txt" data-field-name="name" autofill="off" autocomplete="off" name="kind_name[]"></td>';
        html +=
            '<td><input type="text" id="kind-karat-' +
            rowcount +
            '" class="form-control autocomplete_txt" data-field-name="karat" autofill="off" autocomplete="off" name="karat[]"></td>';
        html +=
            '<td><input type="text" id=" shares-' +
            rowcount +
            '" class="form-control " data-field-name="shares" autofill="off"  name="shares[]"></td>';
        html +=
            '<td><select class="form-control" name="unit[]" id="unit-' +
            rowcount +
            '"><option value="gram"> جرام</option><option value="kilogram">كيلو جرام</option><option value="ounce">أونصة </option></select>';
        html +=
            '<td><input type="text" class="form-control quantity" id="quantity-' +
            rowcount +
            '" name="quantity[]" value="1"></td>';
        html +=
            '<td><input type="text" class="form-control salary" id="salary-' +
            rowcount +
            '" name="salary[]" value="0"></td>';
        html +=
            '<td><input type="text" class="form-control total-cost" id="total-cost-' +
            rowcount +
            '" name="total_cost[]" value="0" readonly></td>';
        html +=
            '<td class="table-borderless"> <a href="http://" class="remove-row"><i class="fas fa-window-close text-danger fs-3"></i></a></td>';
        html += "</tr>";
        rowcount++;
        return html;
    }

    function addNewRow() {
        var html = formHtml();
        tableBody.append(html);
    }

    function getId(element) {
        var id, idArr;
        id = element.attr("id");
        idArr = id.split("-");
        return idArr[idArr.length - 1];
    }

    function getCurrentElement(element) {
        var id, idArr;
        id = element.attr("id");
        idArr = id.split("-");
        return idArr[0];
    }

    $("#opening-balance-autocomplete-table").on(
        "change",
        ".salary, .quantity",
        function () {
            let rowID = getId($(this).closest("tr"));
            let totalCost =
                $("#salary-" + rowID).val() * $("#quantity-" + rowID).val();
            $("#total-cost-" + rowID).val(totalCost);
        }
    );

    $("#opening-balance-autocomplete-table").on(
        "click",
        ".remove-row",
        function (e) {
            e.preventDefault();
            $(this).closest("tr").remove();
        }
    );

    function handleAutocomplete() {
        var fieldName, currentEle;
        currentEle = $(this);
        fieldName = currentEle.data("field-name");
        if (typeof fieldName === "undefined") {
            return false;
        }
        currentEle.autocomplete({
            source: function (data, cb) {
                axios
                    .get(basePath + "/ajax/item-cards/fetch-items", {
                        params: {
                            value: data.term,
                            field_name: fieldName,
                        },
                    })
                    .then((response) => {
                        let res = response.data;
                        var result;
                        result = [
                            {
                                label: "لا يوجد بيانات بهذا الاسم " + data.term,
                                value: "",
                            },
                        ];
                        if (res.length) {
                            result = $.map(res, function (obj) {
                                return {
                                    label: obj[fieldName],
                                    value: obj[fieldName],
                                    data: obj,
                                };
                            });
                        }
                        cb(result);
                    })
                    .catch((error) => {
                        toastr.error(error);
                    });
            },
            autoFocus: true,
            minLength: 1,
            select: function (event, selectedData) {
                if (
                    selectedData &&
                    selectedData.item &&
                    selectedData.item.data
                ) {
                    // console.log(selectedData);
                    var rowNo, data;
                    rowNo = getId(currentEle);
                    data = selectedData.item.data;
                    $("#kind-" + rowNo).val(data.code);
                    $("#kind-name-" + rowNo).val(data.name);
                    $("#kind-karat-" + rowNo).val(data.karat);
                    $("#shares-" + rowNo).val(data.karat);
                }
            },
        });
    }

    // Add New row
    function registerEvents() {
        $(".opening-balance-autocomplete-table")
            .off()
            .on("keydown", "input", function (e) {
                if ($(this).attr("type") != "submit") {
                    if (e.which == 40 || e.which == 13) {
                        if (e.which == 13) {
                            $(this)
                                .closest("td")
                                .next()
                                .find("input, button, select")
                                .focus();
                            $(this).closest("td").next().find("input").select();
                            e.preventDefault();
                            if ($(this).closest("td").next().is('td:last-of-type')) {
                                addNewRow();
                                $('#row' + rowcount).closest('input, select').focus();
                            }
                        }
                    }
                }
            });

        $(".opening-balance-autocomplete-table").on(
            "click",
            "input",
            function (e) {
                $(this).select();
            }
        );

        $(document).on("focus", ".autocomplete_txt", handleAutocomplete);
        // $(document).on('focus','.autocomplete_department', handleDeptTo);
    }
    registerEvents();
});

// Docuemnt List table
$(document).ready(function () {
    // DataTable initialisation
    $("#dataTable_invoice thead tr")
        .clone(true)
        .addClass("filters")
        .appendTo("#dataTable_invoice thead");

    $("#dataTable_invoice").DataTable({
        language: {
            url: "js/localization/dataTables_ar.json",
        },
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "pdf", "print"],
        paging: true,
        ordering: false,
        autoWidth: true,
        orderCellsTop: true,
        fixedHeader: true,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            nb_cols = api.columns().nodes().length;
            var j = 5;
            while (j < nb_cols) {
                var pageTotal = api
                    .column(j, { page: "current" })
                    .data()
                    .reduce(function (a, b) {
                        return Number(a) + Number(b);
                    }, 0);
                // Update footer
                $(api.column(j).footer()).html(pageTotal);
                j++;
            }
        },
        initComplete: function () {
            var api = this.api();

            // For each column
            api.columns()
                .eq(0)
                .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $(".filters th").eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    // $(cell).html('<input type="text" placeholder="' + title + '" />');
                    $(cell).html(
                        '<input type="text" class="fa" placeholder="&#xF002;" />'
                    );

                    // On every keypress in this input
                    $(
                        "input",
                        $(".filters th").eq(
                            $(api.column(colIdx).header()).index()
                        )
                    )
                        .off("keyup change")
                        .on("keyup change", function (e) {
                            e.stopPropagation();

                            // Get the search value
                            $(this).attr("title", $(this).val());
                            var regexr = "({search})"; //$(this).parents('th').find('select').val();

                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api.column(colIdx)
                                .search(
                                    this.value != ""
                                        ? regexr.replace(
                                              "{search}",
                                              "(((" + this.value + ")))"
                                          )
                                        : "",
                                    this.value != "",
                                    this.value == ""
                                )
                                .draw();

                            $(this)
                                .focus()[0]
                                .setSelectionRange(
                                    cursorPosition,
                                    cursorPosition
                                );
                        });
                });
        },
    });
});

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var basePath;
    basePath = $("#base_path").val();

   

    function handleAutocomplete() {
        var fieldName, currentEle;
        currentEle = $(this);
        fieldName = currentEle.data("field-name");
        department = currentEle.data("department");
        if (typeof fieldName === "undefined") {
            return false;
        }
        currentEle.autocomplete({
            source: function (data, cb) {
                $.ajax({
                    url: basePath + "/ajax/transfers/fetch-department-items",
                    method: "GET",
                    dataType: "json",
                    data: {
                        value: data.term,
                        field_name: fieldName,
                        department: department,
                    },
                    success: function (res) {
                        var result;
                        result = [
                            {
                                label: "لا توجد بيانات بهذا الاسم" + data.term,
                                value: "",
                            },
                        ];
                        if (res.length) {
                            result = $.map(res, function (obj) {
                                let label = "";
                                if (
                                    obj["shares"] == "null" ||
                                    !obj["shares"] ||
                                    obj["shares"] == undefined
                                ) {
                                    label =
                                        obj["kind"] + "-" + obj["kind_name"];
                                } else {
                                    label =
                                        obj["kind"] +
                                        "-" +
                                        obj["kind_name"] +
                                        " عيار ( " +
                                        obj["shares"] +
                                        ")";
                                }
                                return {
                                    label: label,
                                    value: obj[fieldName],
                                    data: obj,
                                };
                            });
                        }
                        cb(result);
                    },
                    error: function (result) {
                        if ((response.status = 422)) {
                            $.each(
                                response.responseJSON.errors,
                                function (key, value) {
                                    toastr.error(value);
                                }
                            );
                        } else {
                            toastr.error(response.message);
                        }
                    },
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
                    var data;

                    data = selectedData.item.data;
                    $("#transfered-item-id").val(data.id);
                    $("#transfered-item-department-id").val(data.department_id);
                    $("#kind-code").val(data.kind);
                    $("#kind-name").val(data.kind_name);
                    $("#kind-karat").val(data.karat);
                    $("#shares").val(data.shares);
                    $("#shares-to-transfer").val(data.shares);
                    $("#itemWeightBeforeTransfer").val(data.current_weight);
                }
            },
        });
    }

    function handleDept() {
        var fieldName, currentEle;
        currentEle = $(this);
        fieldName = currentEle.data("field-name");
        department = currentEle.data("department");

        if (typeof fieldName === "undefined") {
            return false;
        }
        currentEle.autocomplete({
            source: function (data, cb) {
                // console.log(data);
                $.ajax({
                    url: basePath + "/ajax/transfers/fetch-departments",
                    method: "GET",
                    dataType: "json",
                    data: {
                        value: data.term,
                        fieldName: fieldName,
                        department: department,
                    },
                    success: function (res) {
                        var result;
                        result = [
                            {
                                label: "لا توجد بيانات بهذا الاسم" + data.term,
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
                        // console.log(result);
                    },
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
                    var data, currentElement;
                    rowNo = getId(currentEle);
                    currentElement = getCurrentElement(currentEle);
                    data = selectedData.item.data;
                    console.log(currentElement);
                    $("#transfer_to").val(data.id);
                    $("#transfer_to_name").val(data.name);
                }
            },
        });
    }
    // Add New row
    function registerEvents() {
        $(document).on("focus", ".autocomplete_txt", handleAutocomplete);
        $(document).on("focus", ".autocomplete_department", handleDept);
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

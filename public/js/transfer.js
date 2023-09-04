$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var rowcount, tableBody, basePath;
    rowcount = $("#autocomplete_table tbody tr").length + 1;
    tableBody = $("#autocomplete_table tbody");
    basePath = $("#base_path").val();

    function formHtml() {
        html = '<tr class="addrow" id="row_' + rowcount + '">';
        html +=
            '<td><input name="item_id[]"  data-field-name="id" id="itemID_' +
            rowcount +
            '" class="item-id form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="item_name[]" data-field-name="item_name" id="itemName_' +
            rowcount +
            '" class="itemName form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="previous_balance[]"  data-field-name="previousـbalance" id="previousـbalance_' +
            rowcount +
            '" class="itemSerial form-control autocomplete_txt add_serial_items" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="coming[]" data-field-name="coming" id="coming_' +
            rowcount +
            '" class="carat form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="going[]" data-field-name="going" id="going_' +
            rowcount +
            '" class="weight form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="certified_loss[]" data-field-name="certified_loss" id="certified_loss_' +
            rowcount +
            '" class="fare form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="not_certified_loss[]" data-field-name="not_certified_loss" id="not_certified_loss_' +
            rowcount +
            '" class="sales-price form-control autocomplete_txt" autofill="off" autocomplete="off" ></td>';
        html +=
            '<td><input name="other_loss[]" data-field-name="other_loss" id="other_loss_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="add_stones[]" data-field-name="add_stones" id="add_stones_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="add_copper[]" data-field-name="add_copper" id="add_copper_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="add_other1[]" data-field-name="add_other1" id="add_other1_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="add_other2[]" data-field-name="add_other2" id="add_other2_' +
            rowcount +
            '"  class="form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="add_other3[]" data-field-name="add_other3" id="add_other3_' +
            rowcount +
            '"  class="form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="current_balance[]" data-field-name="current_balance" id="current_balance_' +
            rowcount +
            '"  class="form-control autocomplete_txt" autofill="off" autocomplete="off"></td>';
        html +=
            '<td><input name="transfer_to_name[]" data-field-name="name" id="transferTo_' +
            rowcount +
            '"  class="form-control autocomplete_department" autofill="off" autocomplete="off">';
        html +=
            '<input type="hidden" name="transfer_to[]" data-field-name="transfer_from_hidden" id="transfer_to_hidden_' +
            rowcount +
            '"  class="autocomplete_department"></td>';
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
        idArr = id.split("_");
        return idArr[idArr.length - 1];
    }

    function getCurrentElement(element) {
        var id, idArr;
        id = element.attr("id");
        idArr = id.split("_");
        return idArr[0];
    }

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
                                label:
                                    "لا توجد بيانات بهذا الاسم" +
                                    data.term,
                                value: "",
                            },
                        ];
                        if (res.length) {
                            result = $.map(res, function (obj) {
                                let label = '';
                                if (obj['shares']=='null' || !obj['shares'] || obj['shares'] == undefined) {
                                     label = obj[fieldName];
                                } else {
                                     label = obj[fieldName] + ' عيار ( ' + obj['shares'] + ')';
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
                        if (response.status = 422) {
                            $.each(response.responseJSON.errors, function(key, value) {
                                toastr.error( value);
                            });
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
                                label:
                                    "لا توجد بيانات بهذا الاسم" +
                                    data.term,
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
        $("body")
            .off()
            .on("keydown", ".addrow", function (e) {
                if (e.keyCode == 40) {
                    addNewRow();
                }
            });
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

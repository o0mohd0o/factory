$(document).ready(function () {
    numbersArToEn();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var rowcount, addBtn, tableBody, imgPath, basePath, serial;

    addBtn = $("#addNew");
    rowcount = $("#qrcode-autocomplete-table tbody tr").length + 1;
    tableBody = $("#qrcode-autocomplete-table tbody");
    imgPath = $("#imgPath").val();
    basePath = $("#base_path").val();
    serial = $("#serial_1").val();
    serial = parseInt(serial) + 1;

    function formHtml() {
        html = '<tr class="addrow" id="row_' + rowcount + '">';
        html +=
            '<td><input name="item_code[]" data-field-name="code" id="itemCode_' +
            rowcount +
            '" class="item-code form-control autocomplete_txt" autofill="off" autoComplete="off">';
        html +=
            '<input name="item_id[]" data-field-name="id" id="itemID_' +
            rowcount +
            '" class="item-id form-control autocomplete_txt" autofill="off" autoComplete="off" type="hidden"></td>';
        html +=
            '<td><input name="item_name[]" data-field-name="name" id="itemName_' +
            rowcount +
            '" class="itemName form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input name="serial[]" data-field-name="serial" id="serial_' +
            rowcount +
            '" class="itemSerial form-control autocomplete_txt add_serial_items" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="Karat[]" data-field-name="karat" id="carat_' +
            rowcount +
            '" class="carat form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input name="quantity[]" data-field-name="quantity" id="quantity_' +
            rowcount +
            '" class="weight form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="fare[]" data-field-name="fare" id="fare_' +
            rowcount +
            '" class="fare form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="sales_price[]" data-field-name="sales_price" id="salesPrice_' +
            rowcount +
            '" class="sales-price form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="descrip1[]" data-field-name="descrip1" id="descrip1_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="descrip2[]" data-field-name="descrip2" id="descrip2_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="descrip3[]" data-field-name="descrip3" id="descrip3_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="descrip4[]" data-field-name="descrip4" id="descrip4_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="descrip5[]" data-field-name="descrip5" id="descrip5_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="checkbox" name="print[]" data-field-name="print" id="print_' +
            rowcount +
            '" class="print_qr"></td>';
        html += "</tr>";
        rowcount++;
        return html;
    }

    function addNewRow() {
        var html = formHtml();
        tableBody.append(html);
    }

    function deleteRow() {
        $(this).parent().remove();
    }

    function getId(element) {
        var id, idArr;
        id = element.attr("id");
        idArr = id.split("_");
        return idArr[idArr.length - 1];
    }
    function getSerial(n, rowNo) {
        $.ajax({
            url: basePath + "/last-serial/" + n,

            success: function (res) {
                // console.log(res);
                $("#serial_" + rowNo).val(parseInt(res) + 1);
                console.log(res);
                return res;
            },
        });
    }

    function handleAutocomplete() {
        var fieldName, currentEle;
        // currentEle = $(this);
        var rowNo;
        id = $(this).attr("id");
        idArr = id.split("_");
        rowNo = idArr[idArr.length - 1];
        // currentEle = $('#itemID_'+rowNo);
        currentEle = $(this);
        // console.log(currentEle);
        fieldName = currentEle.data("field-name");

        if (typeof fieldName === "undefined") {
            return false;
        }

        currentEle.autocomplete({
            source: function (data, cb) {
                // console.log(data);

                $.ajax({
                    url: basePath + "/ajax/item-cards/fetch-all-items",
                    method: "GET",
                    dataType: "json",
                    data: {
                        value: data.term,
                        field_name: fieldName,
                    },
                    success: function (res) {
                        var result;
                        result = [
                            {
                                label:
                                    "There is no matching record found for " +
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
                    var rowNo, data;
                    rowNo = getId(currentEle);
                    data = selectedData.item.data;
                    $("#itemID_" + rowNo).val(data.id);
                    $("#itemCode_" + rowNo).val(data.code);
                    $("#itemName_" + rowNo).val(data.name);
                    $("#carat_" + rowNo).val(data.karat);
                    $("#fare_" + rowNo).val(data.fare);
                    $("#descrip1_" + rowNo).val(data.desc_1);
                    $("#descrip2_" + rowNo).val(data.desc_2);
                    $("#descrip3_" + rowNo).val(data.desc_3);
                    $("#descrip4_" + rowNo).val(data.desc_4);
                    $("#descrip5_" + rowNo).val(data.desc_5);
                    getSerial(data.id, rowNo);
                }
            },
        });
    }

    function formHtmlSerial(id) {
        html = '<tr class="addrow" id="row_' + rowcount + '">';
        html +=
            '<td><input name="item_code[]" data-field-name="code" value="' +
            $("#itemCode_" + id).val() +
            '" id="itemCode_' +
            rowcount +
            '" class="item-code form-control autocomplete_txt" autofill="off" autoComplete="off">';
        html +=
            '<input type="hidden" name="item_id[]" data-field-name="id" value="' +
            $("#itemID_" + id).val() +
            '" id="itemID_' +
            rowcount +
            '" class="item-id form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input name="item_name[]" value="' +
            $("#itemName_" + id).val() +
            '" data-field-name="name" id="itemName_' +
            rowcount +
            '" class="itemName form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input name="serial[]" data-field-name="serial" value="' +
            serial +
            '" id="serial_' +
            rowcount +
            '" class="itemSerial form-control autocomplete_txt add_serial_items" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="karat[]" data-field-name="karat" value="' +
            $("#carat_" + id).val() +
            '" id="carat_' +
            rowcount +
            '" class="carat form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input name="quantity[]" data-field-name="quantity" id="quantity_' +
            rowcount +
            '" class="weight form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="fare[]" data-field-name="fare" id="fare_' +
            rowcount +
            '" value="' +
            $("#fare_" + id).val() +
            '" class="fare form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="sales_price[]" data-field-name="sales_price" id="salesPrice_' +
            rowcount +
            '" class="sales-price form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="desc_1[]" data-field-name="descrip1" value="' +
            $("#descrip1_" + id).val() +
            '" id="descrip1_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="desc_2[]" data-field-name="descrip2" value="' +
            $("#descrip2_" + id).val() +
            '" id="descrip2_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="desc_3[]" data-field-name="descrip3" value="' +
            $("#descrip3_" + id).val() +
            '" id="descrip3_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="desc_4[]" data-field-name="descrip4" value="' +
            $("#descrip4_" + id).val() +
            '" id="descrip4_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="text" name="desc_5[]" data-field-name="descrip5" value="' +
            $("#descrip5_" + id).val() +
            '" id="descrip5_' +
            rowcount +
            '" class="form-control autocomplete_txt" autofill="off" autoComplete="off"></td>';
        html +=
            '<td><input type="checkbox" name="print[]" data-field-name="print" id="print_' +
            rowcount +
            '" class="print_qr"></td>';
        html += "</tr>";
        serial++;
        rowcount++;
        return html;
    }

    $("#qrcode-autocomplete-table").on("click", ".remove-row", function (e) {
        e.preventDefault();
        $(this).closest("tr").remove();
    });

    // Add New row
    function registerEvents() {
        $("body")
            .off()
            .on("keydown", ".addrow", function (e) {
                if (e.keyCode == 40 || e.keyCode == 13) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                    }
                    addNewRow();
                }
            });
        $(document).on("focus", ".autocomplete_txt", handleAutocomplete);
        // $(document).on('focus','.autocomplete_department', handleDeptTo);
    }
    registerEvents();

    $("body").on("contextmenu", ".add_serial_items", function (e) {
        // addNewRow();

        let id = $(this).closest("tr").attr("id");
        console.log(id);
        id = id.split("_")[1];
        let sn = $("#serial_" + id).val();
        serial = parseInt(sn) + 1;
        console.log(serial);
        let n = prompt("ادخل العدد");
        n = parseInt(n);
        for (let i = 0; i < n; i++) {
            var html = formHtmlSerial(id);
            if (Number.isInteger(n) && n != null) {
                tableBody.append(html);
            }
        }
    });

    // Totals table

    //Total Fare
    $("#qrcode-autocomplete-table")
        .on("autocompletechange change", function () {
            var calculated_total_sum = 0;

            $("#qrcode-autocomplete-table .fare").each(function () {
                var get_textbox_value = $(this).val();
                let weight = $(this).closest("tr").find(".weight").val();
                if ($.isNumeric(get_textbox_value)) {
                    calculated_total_sum +=
                        parseFloat(get_textbox_value) * weight;
                }
                $('input[name="total_fare"]').val(calculated_total_sum);
            });
        })
        .change();

    $("#qrcode-autocomplete-table").on("input", ".weight", function () {
        //Total Weight
        var calculated_total_sum = 0;

        $("#qrcode-autocomplete-table .weight").each(function () {
            var get_textbox_value = $(this).val();
            if ($.isNumeric(get_textbox_value)) {
                calculated_total_sum += parseFloat(get_textbox_value);
            }
        });
        $('input[name="total_weight"]').val(calculated_total_sum);

        var calculated_total_sum_18 = 0;
        var calculated_total_sum_21 = 0;
        var calculated_total_sum_22 = 0;
        var calculated_total_sum_24 = 0;
        var convert_to_21_sum = 0;
        var convert_to_24_sum = 0;

        // Total sum 18
        $("#qrcode-autocomplete-table .weight").each(function () {
            let carat = $(this).closest("tr").find(".carat").val();
            console.log(carat);
            if (carat == 18) {
                var wight18 = $(this).val();
                if ($.isNumeric(wight18)) {
                    calculated_total_sum_18 += parseFloat(wight18);
                    convert_to_21_sum += (parseFloat(wight18) * 18) / 21;
                    convert_to_24_sum += (parseFloat(wight18) * 18) / 24;
                    $('input[name="gold18"]').val(calculated_total_sum_18);
                }
            } else if (carat == 21) {
                var weight21 = $(this).val();
                if ($.isNumeric(weight21)) {
                    calculated_total_sum_21 += parseFloat(weight21);
                    convert_to_21_sum += (parseFloat(weight21) * 21) / 21;
                    convert_to_24_sum += (parseFloat(weight21) * 18) / 24;
                    $('input[name="gold21"]').val(calculated_total_sum_21);
                }
            } else if (carat == 22) {
                var weight22 = $(this).val();
                if ($.isNumeric(weight22)) {
                    calculated_total_sum_22 += parseFloat(weight22);
                    convert_to_21_sum += (parseFloat(weight22) * 22) / 21;
                    convert_to_24_sum += (parseFloat(weight22) * 22) / 24;
                    $('input[name="gold22"]').val(calculated_total_sum_22);
                }
            } else if (carat == 24) {
                var weight24 = $(this).val();
                if ($.isNumeric(weight24)) {
                    calculated_total_sum_24 += parseFloat(weight24);
                    convert_to_21_sum += (parseFloat(weight24) * 24) / 21;
                    convert_to_24_sum += (parseFloat(weight24) * 24) / 24;
                    $('input[name="gold24"]').val(calculated_total_sum_24);
                }
            }
            $('input[name="weight_in21"]').val(convert_to_21_sum.toFixed(2));
            $('input[name="weight_in24"]').val(convert_to_24_sum.toFixed(2));
        });
    });

    $("#qrcode-autocomplete-table").on("focusout", "input", function () {
        var rowCount = $("table#qrcode-autocomplete-table tr:last").index() + 1;
        $('input[name="count"]').val(rowCount);
    });

    // print

    $("#qrcode-autocomplete-table").on("input", ".print_qr", function () {
        var elements = new Array(6);
        $("#qrcode-autocomplete-table .print_qr").each(function () {
            if ($(this).is(":checked") && !$("#printAll").is(":checked")) {
                // selected.push($(this));
                var itemID,
                    itemCode,
                    itemName,
                    itemSerial,
                    itemWeight,
                    itemCarat,
                    itemPrice;
                itemID = $(this).closest("tr").find(".item-id").val();
                itemCode = $(this).closest("tr").find(".item-code").val();
                itemName = $(this).closest("tr").find(".itemName").val();
                itemSerial = $(this).closest("tr").find(".itemSerial").val();
                itemCarat = $(this).closest("tr").find(".carat").val();
                itemWeight = $(this).closest("tr").find(".weight").val();
                itemPrice = $(this).closest("tr").find(".sales-price").val();
                elements += ";";
                elements += [
                    itemID,
                    itemCode,
                    itemName,
                    itemSerial,
                    itemCarat,
                    itemWeight,
                    itemPrice,
                ];
                // console.log (itemID + ' + ' + itemName + ' + ' + itemSerial +' + ' + itemWeight +' + ' + itemCarat+' + ' + itemPrice)
                // console.log(elements);
            }
        });
        console.log(elements);
        localStorage.setItem("elements", elements);
    });

    localStorage.getItem("elements");

    $(document).on("change", "#printAll", function () {
        var elementsPrintAll = new Array(6);
        if ($("#printAll").is(":checked")) {
            $("#qrcode-autocomplete-table .print_qr").each(function () {
                var itemID,
                    itemCode,
                    itemName,
                    itemSerial,
                    itemWeight,
                    itemCarat,
                    itemPrice;
                itemID = $(this).closest("tr").find(".item-id").val();
                itemCode = $(this).closest("tr").find(".item-code").val();
                itemName = $(this).closest("tr").find(".itemName").val();
                itemSerial = $(this).closest("tr").find(".itemSerial").val();
                itemCarat = $(this).closest("tr").find(".carat").val();
                itemWeight = $(this).closest("tr").find(".weight").val();
                itemPrice = $(this).closest("tr").find(".sales-price").val();
                elementsPrintAll += ";";
                elementsPrintAll += [
                    itemID,
                    itemCode,
                    itemName,
                    itemSerial,
                    itemCarat,
                    itemWeight,
                    itemPrice,
                ];
                // console.log(itemID + ' + ' + itemName + ' + ' + itemSerial + ' + ' + itemWeight + ' + ' + itemCarat + ' + ' + itemPrice)
            });
            console.log(elementsPrintAll);
            localStorage.setItem("elementsPrintAll", elementsPrintAll);
        } else {
            var elementsPrintAll = new Array(6);
            console.log(elementsPrintAll);
        }
    });
    localStorage.getItem("elementsPrintAll");
    var checkbox = $("#printAll"); // Selected or current checkbox
    value = checkbox.val(); // Value of checkbox

    $(checkbox).click(function () {
        if ($(this).prop("checked") == true) {
            console.log("checked");
            $("#print_labels").on("click", function () {
                $.ajax({
                    type: "POST",
                    url: basePath + "/print-data-all",
                    data: {
                        data: JSON.stringify(
                            localStorage.getItem("elementsPrintAll")
                        ),
                    },
                    success: function (response) {
                        // Log response
                        window.open(basePath + "/print-data-all", "_blank");
                    },
                });
            });
        } else if ($(this).prop("checked") == false) {
            console.log("not checked");
            $("#print_labels").on("click", function () {
                if ($("#printAll").prop("checked") == false) {
                    $.ajax({
                        type: "POST",
                        url: basePath + "/print-data",
                        data: {
                            data: JSON.stringify(
                                localStorage.getItem("elements")
                            ),
                        },
                        success: function (response) {
                            window.open(basePath + "/print-data", "_blank");
                        },
                    });
                }
            });
        }
    });
    $("#print_labels").on("click", function () {
        if ($("#printAll").prop("checked") == false) {
            $.ajax({
                type: "POST",
                url: basePath + "/print-data",
                data: {
                    data: JSON.stringify(localStorage.getItem("elements")),
                },
                success: function (response) {
                    window.open(basePath + "/print-data", "_blank");
                },
            });
        }
    });
});

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
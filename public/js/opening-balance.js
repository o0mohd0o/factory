$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var basePath;
    basePath = $("#base_path").val();

    $("#opening-balance-autocomplete-table").on(
        "click",
        "tbody > tr > td > a.add-row",
        function (e) {
            e.preventDefault();
            let tr = $(this).closest("tr");
            var clone = tr.clone();
            let actionTdCell = `<a href="#" class="add-row m-1">
        <i class="fas fa-plus-square fs-2" style="color: green;"></i>
    </a>
    <a href="#" class="remove-row m-1"><i class="fas fa-window-close text-danger fs-2"></i></a>`;
            clone.find(":last-child").html(actionTdCell);
            clone.find("input:not(.new-item-qty)").val("");
            tr.after(clone);
        }
    );

    $("#opening-balance-autocomplete-table").on(
        "click",
        "tbody > tr > td > a.remove-row",
        function (e) {
            e.preventDefault();
            let body = $(this).closest("tr").parent("tbody");
            $(this).closest("tr").remove();
            body.trigger("change");
        }
    );

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
            let totalCost =
            $(this).closest("tr").find("td>input[name='quantity[]']").val() * $(this).closest("tr").find("td>input[name='salary[]']").val();
            $(this).closest("tr").find("td>input[name='total_cost[]']").val(totalCost);
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
                    $(this).closest("tr").find("td>input[name='kind[]']").val(data.code);
                    $(this).closest("tr").find("td>input[name='kind_name[]']").val(data.name);
                    $(this).closest("tr").find("td>input[name='karat[]']").val(data.karat);
                    $(this).closest("tr").find("td>input[name='shares[]']").val(data.karat);
                }
            },
        });
    }

    // Add New row
    function registerEvents() {
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

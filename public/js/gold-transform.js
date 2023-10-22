$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#used-items-autocomplete-table, #new-items-autocomplete-table").on(
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

    $("#used-items-autocomplete-table, #new-items-autocomplete-table").on(
        "click",
        "tbody > tr > td > a.remove-row",
        function (e) {
            e.preventDefault();
            let body = $(this).closest("tr").parent("tbody");
            $(this).closest("tr").remove();
            body.trigger("change");
        }
    );

    function handleUsedItemsAutocomplete() {
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
                        department_id: $("#gold-transform-department").val(),
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
                                    obj["karat"] == "null" ||
                                    !obj["karat"] ||
                                    obj["karat"] == undefined
                                ) {
                                    label = obj["code"] + "-" + obj["name"];
                                } else {
                                    label =
                                        obj["code"] +
                                        "-" +
                                        obj["name"] +
                                        " عيار ( " +
                                        obj["karat"] +
                                        ")"+
                                        " أسهم ( " +
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
                        if (result.status == 422) {
                            $.each(
                                result.responseJSON.errors,
                                function (key, value) {
                                    toastr.error(value);
                                }
                            );
                        } else {
                            toastr.error(result.message);
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
                    $(this)
                        .closest("tr")
                        .find("input[name='used_item_id[]']")
                        .val(data.id);
                    $(this)
                        .closest("tr")
                        .find("td>input[name='used_item']")
                        .val(data.code);
                    $(this)
                        .closest("tr")
                        .find("td>input[name='used_item_name']")
                        .val(data.name);
                    $(this)
                        .closest("tr")
                        .find("td>input[name='used_item_shares']")
                        .val(data.actual_shares);
                    $(this)
                        .closest("tr")
                        .find(
                            "td>input[name='used_item_weight_before_transform']"
                        )
                        .val(data.current_weight);
                }
            },
        });
    }

    function handleNewItemsAutocomplete() {
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
                                let label = "";
                                if (
                                    obj["karat"] == "null" ||
                                    !obj["karat"] ||
                                    obj["karat"] == undefined
                                ) {
                                    label = obj["code"] + "-" + obj["name"];
                                } else {
                                    label =
                                        obj["code"] +
                                        "-" +
                                        obj["name"] +
                                        " عيار ( " +
                                        obj["karat"] +
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
                    var data = selectedData.item.data;
                    $(this)
                        .closest("tr")
                        .find("input[name='new_item_id[]']")
                        .val(data.id);
                    $(this)
                        .closest("tr")
                        .find("td>input[name='new_item']")
                        .val(data.code);
                    $(this)
                        .closest("tr")
                        .find("td>input[name='new_item_name']")
                        .val(data.name);
                    $(this)
                        .closest("tr")
                        .find("td>input[name='new_item_karat']")
                        .val(data.karat);
                    $(this)
                        .closest("tr")
                        .find("td>input[name='new_item_shares[]']")
                        .val(data.shares);
                }
            },
        });
    }

    $(document).on(
        "focus",
        ".used-items-autocomplete",
        handleUsedItemsAutocomplete
    );
    $(document).on(
        "focus",
        ".new-items-autocomplete",
        handleNewItemsAutocomplete
    );

    $("#new-items-autocomplete-table, #used-items-autocomplete-table").on(
        "change",
        "tbody",
        function (e) {
            var usedGold = 0;
            var newGold = 0;
            $("#used-items-autocomplete-table>tbody>tr").each(function (e) {
                usedGold +=
                    Number(
                        $(this).find('td>input[name="used_item_shares"]').val()
                    ) *
                    Number(
                        $(this).find('td>input[name="weight_to_use[]"]').val()
                    );
            });
            $("#new-items-autocomplete-table>tbody>tr").each(function (e) {
                newGold +=
                    Number(
                        $(this).find('td>input[name="new_item_shares[]"]').val()
                    ) *
                    Number(
                        $(this).find('td>input[name="new_item_weight[]"]').val()
                    );
            });
            let sharesDifference = usedGold - newGold;
            let differenceInCalibIn21 = sharesDifference / 875;
            let differenceInCalibIn24 = sharesDifference / 1000;
            console.log(usedGold, newGold);
            $("#gold-transform-loss>tbody>tr>td.loss-calib-in-21").html(
                roundToDecimals(
                    differenceInCalibIn21 >= 0.01 ? differenceInCalibIn21 : 0
                )
            );
            $("#gold-transform-loss>tbody>tr>td.loss-calib-in-24").html(
                roundToDecimals(
                    differenceInCalibIn24 >= 0.01 ? differenceInCalibIn24 : 0
                )
            );
            if (differenceInCalibIn21 <= -0.01) {
                toastr.error(
                    "There is an error. New items gold shares must be equal or less than  used items gold shares"
                );
            }
        }
    );
});

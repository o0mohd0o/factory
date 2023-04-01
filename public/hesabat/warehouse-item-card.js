$(function () {
    "use strict";
    var chosen_form = "item-card-modal";
    // var url = "https://localhost/hesabat/public/api";
    //  var url = "https://tareklancer.com/projects/hesabat/public/api";
    var is_get_initial_html_row = false;
    var is_edit_status = false;
    var global_item_card_parent_code_id  = 0;
    var global_item_card_parent_code_id  = 0;
    var can_press_edit = false;
    var can_press_delete = false;
    var is_new_status = false;
    var selling_price = 0;
    var buying_price = 0;
    var global_item_card_id = 0;
    var global_item_card_level_num = 1;

    var item_card_kind_desc_a = [];
    var item_card_kind_desc_e = [];

    is_get_initial_html_row = false;
    is_edit_status = false;
    can_press_edit = false;
    can_press_delete = false;
    is_new_status = false;

    /* change disabled attr btns */
    btnEnableControl();

    /* set the global id */
    global_item_card_id = 0;
    /* reset all input values */
    $("#" + chosen_form + " .input_test").val("");
    $("#item-card-special-settings-unit-input").val("");

    for (var i = 1; i < 4; i++) {
        /** set values to empty initially */
        $("#item-card-selling-unit" + i).val("");
        $("#item-card-selling-retail" + i).val("");
        $("#item-card-buying-unit" + i).val("");
        $("#item-card-buying-price" + i).val("");
    }
    // alert('ss');
    /* ajax request */
    axios
        .get(
            apiBasePath + "/item-card?item_card_level_num=" + global_item_card_level_num
        )
        .then(function (data) {
            data = data.data;
            // console.log(data.data.status);
            if (data.status == "success") {
                // console.log(data.branches);
                // var branches = data.branches;
                var html_option = "";
                var item_card = data.item_card;
                var html_tr = "";
                /* loop the array */
                for (var i = 0; i < item_card.length; i++) {
                    console.log(item_card.is_main);
                    if (item_card[i].is_main == 0) {
                        html_tr +=
                            '<tr style="color:#007bff" id="id_' +
                            item_card[i].id +
                            '" data-id="' +
                            item_card[i].id +
                            '">';
                    } else {
                        html_tr +=
                            '<tr  id="id_' +
                            item_card[i].id +
                            '" data-id="' +
                            item_card[i].id +
                            '">';
                    }
                    console.log(item_card[i]);

                    html_tr +=
                        '<td class="text-center">' +
                        item_card[i].code +
                        "</td>";
                    html_tr +=
                        '<td class="text-right">' +
                        item_card[i].name_a +
                        "</td>";
                    html_tr += "</tr>";
                }
                /* add html to view */
                $("#item-card-table tbody").html(html_tr);
                /* end add html to view */

                $("#item-card-modal  select[name='supplier']").html(
                    get_select_html(data.supplier, "")
                );
                $("#item-card-modal  select[name='supplier']").val("");

                $("#item-card-modal  select[name='kind']").html(
                    get_select_html(data.kind, "kind")
                );
                $("#item-card-modal  select[name='kind']").val("");

                $("#item-card-modal  select[name='country']").html(
                    get_select_html(data.country, "")
                );
                $("#item-card-modal  select[name='country']").val("");

                $("#item-card-modal  select[name='company']").html(
                    get_select_html(data.company, "")
                );
                $("#item-card-modal  select[name='company']").val("");
                // units

                $("#item-card-raseed  select[name='unit_1']").html(
                    get_select_html(data.units, "")
                );
                $("#item-card-raseed  select[name='unit_1']").val("");
                $("#item-card-raseed  select[name='unit_2']").html(
                    get_select_html(data.units, "")
                );
                $("#item-card-raseed  select[name='unit_2']").val("");
                $("#item-card-raseed  select[name='unit_3']").html(
                    get_select_html(data.units, "")
                );
                $("#item-card-raseed  select[name='unit_3']").val("");

                $("#item-card-selling-unit1").html(
                    get_select_html(data.units, "")
                );
                $("#item-card-selling-unit1").val("");

                $("#item-card-selling-unit2").html(
                    get_select_html(data.units, "")
                );
                $("#item-card-selling-unit2").val("");

                $("#item-card-selling-unit3").html(
                    get_select_html(data.units, "")
                );
                $("#item-card-selling-unit3").val("");

                $("#item-card-buying-unit1").html(
                    get_select_html(data.units, "")
                );
                $("#item-card-buying-unit1").val("");

                $("#item-card-buying-unit2").html(
                    get_select_html(data.units, "")
                );
                $("#item-card-buying-unit2").val("");

                $("#item-card-buying-unit3").html(
                    get_select_html(data.units, "")
                );
                $("#item-card-buying-unit3").val("");

                console.log(data.currency);
                $(
                    "#item-card-special-settings  select[name='item-card-special-settings-related-currency']"
                ).html(get_select_html(data.currency, ""));
                $(
                    "#item-card-special-settings  select[name='item-card-special-settings-related-currency']"
                ).val("");

                /* set the html for the select */
            }
        });

    /* when click on the new */
    $(document).on("click", "#new-item-card", function () {
        is_new_status = true;
        can_press_edit = false;
        /* change disabled attr btns */
        btnEnableControl();
        /* set global_item_card_id = 0 */
        global_item_card_id = 0;
        // alert(chosen_form);
        /* ajax request */
        axios
            .get(
                apiBasePath +
                    "/item-card/create?item_card_level_num=" +
                    global_item_card_level_num +
                    "&parent_id=" +
                    global_item_card_parent_code_id
            )
            .then(function (data) {
                data = data.data;
                if (data.status == "success") {
                    /* remove disabled proberty from inputs */
                    $("#" + chosen_form + " .input_test").prop(
                        "disabled",
                        false
                    );
                    /* empty all values */
                    $("#" + chosen_form + " .input_test").val("");
                    // if(data.max_code != 0){
                    $(
                        "#item-card-modal .modal-input-form input[name='code']"
                    ).val(data.max_code);
                    // alert(data.total_parent_code);
                    $("#item-card-modal .parent_code").html(
                        data.total_parent_code
                    );
                    if (!data.allow_edit_code) {
                        $(
                            "#item-card-modal .modal-input-form input[name='code']"
                        ).prop("disabled", true);
                    } else {
                        $(
                            "#item-card-modal .modal-input-form input[name='code']"
                        ).prop("maxlength", data.max_code.length);
                        $(
                            "#item-card-modal .modal-input-form input[name='code']"
                        ).prop("minlength", data.max_code.length);
                    }
                }
            });
    });

    /* when click on edit */
    $(document).on("click", "#edit-item-card", function () {
        is_edit_status = true;
        can_press_delete = false;
        can_press_edit = false;
        /* change disabled attr btns */
        btnEnableControl();
    });

    $(document).on("click", "#save-item-card", function () {
        if ($("#item-card-unit1").val() && !$("#item-card-count1").val()) {
            alert("من فضلك اختر العدد الخاص بالوحده الاولى");
            return;
        }
        if ($("#item-card-unit2").val() && !$("#item-card-count2").val()) {
            alert("من فضلك اختر العدد الخاص بالوحده الثانيه");
            return;
        }
        if ($("#item-card-unit3").val() && !$("#item-card-count3").val()) {
            alert("من فضلك اختر العدد الخاص بالوحده الثالثه");
            return;
        }

        var id = global_item_card_id;

        /* add disabled proberty to inputs when show or undo */
        $("#" + chosen_form + " .input_test").prop("disabled", true);

        let map = new Map();
        $("#" + chosen_form + " .input_test").each(function () {
            map.set($(this).attr("name"), $(this).val());
        });
        // special_settings
        var item_card_special_settings_arr = {};
        $("#item-card-special-settings .input_test").each(function () {
            item_card_special_settings_arr[$(this).attr("name")] =
                $(this).val();
        });
        map.set(
            "special_settings",
            JSON.stringify(item_card_special_settings_arr)
        );

        // item_card_details
        var item_card_details_arr = {};
        $("#item-card-details .input_test").each(function () {
            item_card_details_arr[$(this).attr("name")] = $(this).val();
        });
        map.set("details", JSON.stringify(item_card_details_arr));
        // console.log(item_card_details_arr);
        // return;
        // item card raseed
        // item_card_raseed
        var item_card_raseed_arr = {};
        $("#item-card-raseed .input_test").each(function () {
            item_card_raseed_arr[$(this).attr("name")] = $(this).val();
        });
        map.set("raseed", JSON.stringify(item_card_raseed_arr));

        $("#item-card-modal .parent_code").html("");
        map.set("level_num", global_item_card_level_num);

        if (global_item_card_level_num > 1) {
            map.set("parent_id", global_item_card_parent_code_id);
        } else {
            map.set("parent_id", null);
        }

        console.log(map);
        var ajax_url = "";
        var ajax_method = "";

        /* save */
        if (id == 0) {
            ajax_url = apiBasePath + "/item-card";
            ajax_method = "POST";
        } else {
            /* update */
            ajax_url = apiBasePath + "/item-card/" + id;
            ajax_method = "PATCH";
        }
        /* ajax request */
        // let map = new Map();
        // $('#' + chosen_form + ' .input_test').each(function() {
        //     $(this).val('')
        // })

        $("#item-card-modal .parent_code").html("");

        // Send a POST request
        axios({
            method: ajax_method,
            url: ajax_url,
            data: {
                _token: csrf_token,
                val: Object.fromEntries(map),
            },
        }).then(function (data) {
            data = data.data;
            is_edit_status = false;
            is_new_status = false;
            can_press_edit = true;
            can_press_delete = true;
            /* change disabled attr btns */
            btnEnableControl();
            if (data.status == "success") {
                console.log(data);
                $(".success-msg.js-message strong").html("تم الحفظ بنجاح");
                $(".success-msg.js-message").removeClass("none");
                setTimeout(function () {
                    $(".success-msg.js-message").addClass("none");
                }, 3000);

                /* if save */
                if (data.type == "store") {
                    /* set html value */
                    var html_tr = "";
                    html_tr +=
                        '<tr id="id_' +
                        data.item_card.id +
                        '" data-id="' +
                        data.item_card.id +
                        '">';
                    html_tr +=
                        '<td class="text-center">' +
                        data.item_card.code +
                        "</td>";
                    html_tr +=
                        '<td class="text-right">' +
                        data.item_card.name_a +
                        "</td>";
                    html_tr += "</tr>";
                    /* add html to view */
                    $("#item-card-table tbody").append(html_tr);
                    /* end add html to view */
                } else {
                    /* if edit */
                    /* set html value */
                    var html_tr = "";
                    html_tr +=
                        '<td class="text-center">' +
                        data.item_card.code +
                        "</td>";
                    html_tr +=
                        '<td class="text-right">' +
                        data.item_card.name_a +
                        "</td>";

                    $("#item-card-table tbody #id_" + data.item_card.id).html(
                        html_tr
                    );
                }
            }
        });
    });

    // show or undo
    $(document).on(
        "click",
        "#item-card-table tbody tr, #undo-item-card",
        function () {
            if ($(this).attr("id") == "undo-item-card") {
                var confiramtionRes = confirmUndoAr();
                if (!confiramtionRes) {
                    return;
                }
                is_new_status = false;
                is_edit_status = false;
                /* change disabled attr btns */
                btnEnableControl();
            } else {
                can_press_edit = true;
                can_press_delete = true;
                /* change disabled attr btns */
                btnEnableControl();
            }

            var id = $(this).data("id");

            /* if clicked on #undo-item_card */
            if (id == undefined) {
                id = global_item_card_id;
            } else {
                /* if clicked on #item-card-table tbody tr */
                global_item_card_id = id;
                /* if in the first level */
                if (global_item_card_level_num == 1) {
                    /* set global_item_card_parent_code_id */
                    global_item_card_parent_code_id = global_item_card_id;
                }
                /* set global_forward_item_card_parent_code_id */
                global_forward_item_card_parent_code_id = id;
            }
            /* if the user click on undo when he doesn't choose any row */
            if (id == 0) {
                return;
            }

            /* add disabled proberty to inputs when show or undo */
            $("#" + chosen_form + " .input_test").prop("disabled", true);
            axios.get(apiBasePath + "/item-card/" + id).then(function (data) { 
                data = data.data;
                if (data.status == "success") {
                    global_forward_item_card_parent_code_id =
                        data.parent_id;
                    selling_price = data.selling_price;
                    buying_price = data.buying_price;
                    /* set values for the inputs */
                    $("#item-card-modal input[name='code']").val(
                        data.item_card.code
                    );
                    // alert(data.total_parent_code);
                    $("#item-card-modal .parent_code").html(
                        data.item_card.total_parent_code
                    );
                    $("#item-card-modal [name='item_number']").val(
                        data.item_card.item_number
                    );
                    $("#item-card-modal [name='oreder_limit']").val(
                        data.item_card.oreder_limit
                    );
                    $("#item-card-modal [name='min_quant']").val(
                        data.item_card.min_quant
                    );
                    $("#item-card-modal [name='max_quant']").val(
                        data.item_card.max_quant
                    );
                    $("#item-card-modal [name='position']").val(
                        data.item_card.position
                    );
                    $("#item-card-modal [name='name_a']").val(
                        data.item_card.name_a
                    );
                    $("#item-card-modal [name='name_e']").val(
                        data.item_card.name_e
                    );
                    $("#item-card-modal [name='supplier']").val(
                        data.item_card.supplier
                    );
                    $("#item-card-modal [name='kind']").val(
                        data.item_card.kind
                    );
                    $("#item-card-modal [name='trade_off']").val(
                        data.item_card.trade_off
                    );
                    $("#item-card-modal [name='country']").val(
                        data.item_card.country
                    );
                    $("#item-card-modal [name='company']").val(
                        data.item_card.company
                    );
                    $(".not_main").css("display", "none");
                    if (data.item_card.is_main == 1) {
                        $(".not_main").css("display", "block");
                    }

                    json_to_fields(
                        "item-card-special-settings",
                        data.item_card.special_settings
                    );
                    json_create_fields(
                        "item-card-details",
                        data.item_card.details
                    );

                    json_to_fields(
                        "item-card-raseed",
                        data.item_card.raseed,
                        data.total_stock
                    );

                    $("#item-card-numeric-balance").val(
                        data.numeric_balance
                    );
                }
             })
            /* ajax request */
          
                    
              
            /* end ajax request */
        }
    );

    $(document).on(
        "click",
        "#forward-item-card, #backward-item-card",
        function () {
            var btn_id = $(this).attr("id");
            forwardBackwardItemCard(btn_id);
        }
    );

    /* when dbl-click on the row */
    $(document).on("dblclick", "#item-card-table tbody tr", function () {
        var btn_id = "forward-item-card";
        forwardBackwardItemCard(btn_id);
    });
    /* end when db-click on the row */

    function forwardBackwardItemCard(btn_id) {
        can_press_edit = false;
        /* change disabled attr btns */
        btnEnableControl();
        var max_levels = 5;
        var direction;
        /* ajax request */
        // alert('ss');
        if (btn_id == "forward-item-card") {
            if (
                global_forward_item_card_parent_code_id == 0 ||
                global_item_card_level_num == max_levels
            ) {
                return;
            }
            $("#item-card-modal .parent_code").html(
                $("#item-card-modal .parent_code").html() +
                    $(
                        "#item-card-modal .modal-input-form input[name='code']"
                    ).val()
            );
            global_item_card_level_num =
                parseInt(global_item_card_level_num) + 1;
            var level_ajax = global_item_card_level_num;
            global_item_card_parent_code_id =
                global_forward_item_card_parent_code_id;
            direction = "forward";
        } else {
            if (global_item_card_level_num == 1) {
                return;
            }
            // global_item_card_level_num=parseInt(global_item_card_level_num) - 1
            var level_ajax = global_item_card_level_num;
            global_item_card_parent_code_id =
                global_forward_item_card_parent_code_id;
            direction = "backward";
            // global_item_card_parent_code_id = global_backward_item_card_parent_code_id;
            // if(global_backward_item_card_parent_code_id==-1){
            //     global_item_card_parent_code_id=no_branch_parent_id
            // }
        }

        /* remove disabled proberty from inputs */
        //    alert(global_item_card_level_num);
        $("#" + chosen_form + " .input_test").prop("disabled", true);
        /* empty all values */
        $("#" + chosen_form + " .input_test").val("");

        /* backward case */
        if (level_ajax < 1) {
            return;
        }

        axios.get(  apiBasePath +
            "/item-card?current_code=" +
            $(
                "#item-card-modal .modal-input-form input[name='code']"
            ).val() +
            "&data_count=" +
            $("#item-card-table tr").length +
            "&item_card_level_num=" +
            level_ajax +
            "&parent_id=" +
            global_item_card_parent_code_id +
            "&dirction=" +
            direction).then(function (data) {
                data = data.data;
                if (data.status == "success") {
                    global_forward_item_card_parent_code_id = data.parent_id;
                    global_item_card_level_num = data.lvl;
                    global_item_card_id = -1;

                    var item_card = data.item_card;
                    var html_tr = "";
                    /* loop the array */
                    var style;
                    if (item_card.is_main == 0) {
                        style = 'style="color:blue"';
                    }
                    for (var i = 0; i < item_card.length; i++) {
                        console.log(item_card[i]);
                        /* set html value */
                        if (item_card[i].is_main == 0) {
                            html_tr +=
                                '<tr style="color:#007bff" id="id_' +
                                item_card[i].id +
                                '" data-id="' +
                                item_card[i].id +
                                '">';
                        } else {
                            html_tr +=
                                '<tr  id="id_' +
                                item_card[i].id +
                                '" data-id="' +
                                item_card[i].id +
                                '">';
                        }
                        html_tr +=
                            '<td class="text-center">' +
                            item_card[i].code +
                            "</td>";
                        html_tr +=
                            '<td class="text-right">' +
                            item_card[i].name_a +
                            "</td>";
                        html_tr += "</tr>";
                    }
                    /* add html to view */
                    $("#item-card-table tbody").html(html_tr);
                    if (data.parent != null) {
                        $("#item-card-modal .parent_code").html(
                            data.parent.code
                        );
                    }

                    /* end add html to view */
                }
              });
        
           
       
        /* end ajax request */
    }

    $(document).on("click", "#delete-item-card", function () {
        var id = global_item_card_id;

        var confiramtionRes = confirmAr();
        if (!confiramtionRes) {
            return;
        }

        axios.get(apiBasePath + "/item-card/" + id + "/delete").then(function(data){
            if (data.status == "success") {
                can_press_edit = false;
                can_press_delete = false;
                /* change disabled attr btns */
                btnEnableControl();
                global_forward_item_card_parent_code_id = data.parent_id;

                $(
                    `#item-card-table>tbody>tr[id=id_${global_item_card_id}`
                ).remove();
                // if($('#item-card-table>tbody>tr').length==0){
                //     if(global_item_card_level_num>1){
                //         global_item_card_level_num=global_item_card_level_num-1
                //     }

                // }
            } else if (data.status == "parent") {
                alert("هذا العنصر لديه فرعيات .... لذلك لايمكن حذفه");
            }
        });
        
              
          
        /* end ajax request */
    });
    $(document).on("change", "#item_card_kind", function () {
        //  console.log(item_card_kind_desc_a[$("#item-card-modal  select[name='kind']").val()]);
        var arr = JSON.parse(
            item_card_kind_desc_a[
                $("#item-card-modal  select[name='kind']").val()
            ]
        );
        console.log(arr);
        var disabled = "";
        var html_option = '<div class="col-12 row" style="text-align:left">';
        for (var i = 0; i < arr.length; i++) {
            if (arr[i] != null) {
                html_option =
                    html_option +
                    '<div class="col-6" style="text-align:left"> ' +
                    '<label style="width:25%" for="">' +
                    arr[i] +
                    " </label>" +
                    '<input type="text" style="width:70%; margin-right:30%" "' +
                    disabled +
                    '" class="form-control input_test" name="' +
                    arr[i] +
                    '" value="">' +
                    "</div>";
            }
        }
        html_option = html_option + "</div>";
        console.log(html_option);
        $("#item-card-details").html(html_option);
        // item-card-details
    });

    function get_select_html(arr, name_of_item) {
        var html_option = "";
        for (var i = 0; i < arr.length; i++) {
            // console.log(branches[i]);
            /* set html value */
            html_option +=
                '<option value="' +
                arr[i].id +
                '" >' +
                arr[i].name_a +
                "</option>";
            if (name_of_item == "kind") {
                item_card_kind_desc_a[arr[i].id] = arr[i].desc_a;
                item_card_kind_desc_e[arr[i].id] = arr[i].desc_e;
            }
        }

        return html_option;
    }

    function json_to_fields(div_id, json_arr, total_stock = 0) {
        var data = JSON.parse(json_arr);
        $.each(data, function (key, item) {
            $("#" + div_id + "  [name=" + key + "]").val(item);
        });

        if (div_id == "item-card-raseed") {
            var stock = 0;
            var retail = 0;
            var average_buying = 0;
            for (var i = 1; i < 4; i++) {
                /** set values to empty initially */
                $("#item-card-selling-unit" + i).val("");
                $("#item-card-selling-retail" + i).val("");
                $("#item-card-buying-unit" + i).val("");
                $("#item-card-buying-price" + i).val("");
                if ($("#item-card-count" + i).val()) {
                    stock = total_stock / $("#item-card-count" + i).val();
                    $("#" + div_id + "  [name=stock_" + i + "]").val(
                        Math.round(stock * 1000) / 1000
                    );
                    /** set first val stock special settings */
                    if (i == 1) {
                        $("#item-card-special-settings-unit-input").val(
                            Math.round(stock * 1000) / 1000
                        );
                        $("#item-card-special-settings-unit").html(
                            $("#item-card-unit1 option:selected").text()
                        );
                    }
                    /** set values for sellings */
                    $("#item-card-selling-unit" + i).val(
                        $("#item-card-unit" + i).val()
                    );
                    retail = selling_price / $("#item-card-count" + i).val();
                    $("#item-card-selling-retail" + i).val(
                        Math.round(retail * 1000) / 1000
                    );
                    /** set values for buying */
                    $("#item-card-buying-unit" + i).val(
                        $("#item-card-unit" + i).val()
                    );
                    average_buying =
                        buying_price / $("#item-card-count" + i).val();
                    $("#item-card-buying-price" + i).val(
                        Math.round(average_buying * 1000) / 1000
                    );
                }
            }
        }
    }

    function json_create_fields(div_id, json_arr) {
        $("#" + div_id).html("");
        var data = jQuery.parseJSON(json_arr);
        var html_option = '<div class="col-12 row" style="text-align:left">';
        var disabled = "disabled";
        $.each(data, function (key, item) {
            html_option =
                html_option +
                '<div class="col-6" style="text-align:left"> ' +
                '<label style="width:25%" for="">' +
                key +
                " </label>" +
                '<input type="text" style="width:70%; margin-right:30%" "' +
                disabled +
                '" class="form-control input_test" name="' +
                key +
                '" value="' +
                item +
                '">' +
                "</div>";
        });

        html_option = html_option + "</div>";
        $("#" + div_id).html(html_option);
    }

    /* enable and disable btns */
    function btnEnableControl() {
        /* in case of new or edit */
        if (is_edit_status || is_new_status) {
            $("#item-card-modal .modal-footer button").each(function () {
                $(this).attr("disabled", true);
            });

            if (can_press_edit) {
                $("#edit-item-card").attr("disabled", false);
            }
            if (can_press_delete) {
                $("#delete-item-card").attr("disabled", false);
            }
            $("#undo-item-card").attr("disabled", false);
            $("#save-item-card").attr("disabled", false);
        } else {
            /* can't press delete and not in new or edit state */
            if (!can_press_delete) {
                $("#item-card-modal .modal-footer button").each(function () {
                    $(this).attr("disabled", true);
                });
                $("#new-item-card").attr("disabled", false);
                $("#item-card-modal .close-modal").attr("disabled", false);
            } else {
                $("#item-card-modal .modal-footer button").each(function () {
                    $(this).attr("disabled", false);
                });
                if (!can_press_edit) {
                    $("#edit-item-card").attr("disabled", true);
                }
                $("#undo-item-card").attr("disabled", true);
                $("#save-item-card").attr("disabled", true);
            }
        }
    }
});

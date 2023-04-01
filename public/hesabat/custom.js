function confirmAr() {
    return confirm('هل انت متأكد ؟ ');
}

function confirmCloseAr() {
    return confirm('هل تريد الخروج بالفعل ؟ ');
}

function confirmUndoAr() {
    return confirm('هل تريد تأكيد التراجع ؟');
}

/* check if array has specified index */
function checkArray(arr, first_index, second_index = 'empty') {
    try {
        if (second_index != 'empty') {
            if (typeof arr[first_index][second_index] !== 'undefined') {
                return arr[first_index][second_index];
            } else {
                return '';
            }

        } else {
            if (typeof arr[first_index] !== 'undefined') {
                return arr[first_index];
            } else {
                return '';
            }

        }
    } catch (err) {
        return '';
    }
}

/* check if json obj has specified attribute */
function checkAttr(obj, attr) {
    try {
        if (obj.hasOwnProperty(attr)) {
            return obj[attr];
        } else {
            return '';
        }
    } catch (err) {
        return ''
    }
}

/* get select options */
function get_option_html(arr, name_of_item, id = 'empty', isFirstEmpty = true) {
    var html_option = '';
    var selected_status = '';

    if (false && isFirstEmpty) {
        html_option += '<option value="-1" >...</option>';
    }

    for (var i = 0; i < arr.length; i++) {
        // console.log(branches[i]);
        /* set html value */
        if (id == 'empty') {
            html_option +=
                '<option value="' +
                arr[i].id +
                '" >' +
                arr[i][name_of_item] +
                '</option>';
        } else {
            //            if (i == 0) {
            //                if (id == -1) {
            //                    selected_status = 'selected';
            //                } else {
            //                    selected_status = '';
            //                }
            //
            //            } 
            if (id == arr[i].id) {
                selected_status = 'selected';
            } else {
                selected_status = '';
            }

            html_option +=
                '<option value="' +
                arr[i].id +
                '" ' + selected_status + ' >' +
                arr[i][name_of_item] +
                '</option>';
        }
    }

    return html_option
}

/* get select options */
function get_option_html_no_obj_arr(arr, key = 'empty', isFirstEmpty = true) {
    var html_option = '';
    if (false && isFirstEmpty) {
        html_option += '<option value="-1" >...</option>';
    }

    for (var i = 0; i < arr.length; i++) {
        //        alert(arr[i]);
        /* set html value */
        if (key == 'empty') {
            html_option +=
                '<option value="' +
                i +
                '" >' +
                arr[i] +
                '</option>';
        } else {
            //            if (i == 0) {
            //                if (key == -1) {
            //                    selected_status = 'selected';
            //                } else {
            //                    selected_status = '';
            //                }
            //
            //            }
            if (key == i) {
                selected_status = 'selected';
            } else {
                selected_status = '';
            }
            html_option +=
                '<option value="' +
                i +
                '" ' + selected_status + ' >' +
                arr[i] +
                '</option>';
        }
    }

    return html_option;
}


/* get select options */
function get_option_string_index(object, key = 'empty', isFirstEmpty = true) {
    var html_option = '';
    if (isFirstEmpty) {
        html_option += '<option value="-1" >...</option>';
    }

    for (var property in object) {
        //        alert(arr[i]);
        /* set html value */
        if (key == 'empty') {
            html_option +=
                '<option value="' +
                property +
                '" >' +
                object[property] +
                '</option>';
        } else {
            if (key == property) {
                selected_status = 'selected';
            } else {
                selected_status = '';
            }
            html_option +=
                '<option value="' +
                property +
                '" ' + selected_status + ' >' +
                object[property] +
                '</option>';
        }
    }

    return html_option;
}

function isEmpty(str) {
    return !str.trim().length;
}


function checkArrayVal(arr, val) {
    if (arr.includes(val)) {
        return true;
    } else {
        return false;
    }
}


function getTodayDate() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = yyyy + '/' + mm + '/' + dd;
    return today;
}


/* remove alert when click x */
$(document).on('click', '.close-alert', function() {
    $(this).parent().addClass('none');
});


function closeModal(modal_id, is_new_status, is_edit_status) {
    /* if clicked on new or edit */
    if (is_new_status || is_edit_status) {
        var confiramtionRes = confirmCloseAr();
        if (!confiramtionRes) {
            return;
        }
    }

    $('#' + modal_id).modal('toggle');
}


function showError(msg) {

    $('#error-msg').html(msg);
    $('#error-msg').removeClass('none');
    setTimeout(function() {
        $('#error-msg').addClass('none');
    }, 3000);
}


function showSuccess(msg) {

    $('.success-msg.js-message strong').html(msg);
    $('.success-msg.js-message').removeClass('none');
    setTimeout(function() {
        $('.success-msg.js-message').addClass('none');
    }, 3000);
}

function valueOrZero(attr) {
    return isNaN(attr) ? 0 : attr;
}



function clearNull(value) {
    return value == null ? ' ' : value
}
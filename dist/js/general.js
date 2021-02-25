var token = GetCookie("token");
var main_role_option = "";
var edit_teacher_option = "";
var edu_type = "";
var edu_explain = "";
var edu_item = "";
var test_option = "";
var activate_btn = 0;
var ans_delete_id = [];

function show_data_table(value) {
    var main = document.getElementById("main");
    var sub = document.getElementById("sub_item");
    var strategy = document.getElementById("strategy");
    if (value === "0") {
        main.style.display = "block";
        sub.style.display = "none";
        strategy.style.display = "none";
        main_table.style.display = "block";
        sub_table.style.display = "none";
        strategy_table.style.display = "none";
        alarm.style.display = "none";
        activity.style.display = "none";
        activity_table.style.display = "none";
    }
    else if (value === "1") {
        main.style.display = "none";
        sub.style.display = "block";
        strategy.style.display = "none";
        main_table.style.display = "none";
        sub_table.style.display = "block";
        strategy_table.style.display = "none";
        alarm.style.display = "none";
        activity.style.display = "none";
        activity_table.style.display = "none";
        item_show_main_name();
    }
    else if (value === "2") {
        main.style.display = "none";
        sub.style.display = "none";
        strategy.style.display = "block";
        main_table.style.display = "none";
        sub_table.style.display = "none";
        strategy_table.style.display = "block";
        alarm.style.display = "none";
        activity.style.display = "none";
        activity_table.style.display = "none";
        strategy_show_option();
    }
    else if (value === "3") {
        main.style.display = "none";
        sub.style.display = "none";
        strategy.style.display = "none";
        main_table.style.display = "none";
        sub_table.style.display = "none";
        strategy_table.style.display = "none";
        alarm.style.display = "block";
        activity.style.display = "block";
        activity_table.style.display = "block";
        activity_show_option();
        $.ajax({
            type: "POST",
            url: "phpMod/call_date.php",
            async: false,
            dataType: "json",
            data: { token: token },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    var data_array = data.out;
                    var day = data_array['day'];
                    var isactivate = data_array['isactivate'];
                    $('#alarm_period').val(day);
                    if (isactivate == '0') {
                        $('#isactivate_on').css("background", "#00c0ef");
                        $('#isactivate_off').css("background", "blue");
                        activate_btn = 0;
                    }
                    else {
                        $('#isactivate_off').css("background", "#00c0ef");
                        $('#isactivate_on').css("background", "blue");
                        activate_btn = 1;
                    }

                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        })
    }
}

var plus_edit_index = 1;
var plus_ans_index = 1;
$(document).on('click', '.plus_btn', function (event) {
    var id = "id";
    $(this).parents('.act_main_edit').after('<div class="form-class row_main" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>可編輯人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_edit_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_edit_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_edit_name'+plus_edit_index).selectpicker();
    $('#main_edit_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.row_plus_btn', function (event) {
    var id = "id";
    $(this).parents('.row_main').after('<div class="form-class row_main" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>可編輯人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_edit_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_edit_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_edit_name'+plus_edit_index).selectpicker();
    $('#main_edit_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.minus_btn', function (event) {
    $(this).parents('.row_main').remove();
});
$(document).on('click', '.plus_review_btn', function (event) {
    var id = "id";
    $(this).parents('.act_main_review').after('<div class="form-class row_riview_main" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>審查人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_review_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_review_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_review_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_review_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_review_name'+plus_edit_index).selectpicker();
    $('#main_review_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.row_plus_review_btn', function (event) {
    var id = "id";
    $(this).parents('.row_riview_main').after('<div class="form-class row_riview_main" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>審查人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_review_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_review_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_review_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_review_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_review_name'+plus_edit_index).selectpicker();
    $('#main_review_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.minus_review_btn', function (event) {
    $(this).parents('.row_riview_main').remove();
});
$(document).on('click', '.plus_item_btn', function (event) {
    $(this).parents('.act_item_edit').after('<div class="form-class row_item" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>可編輯人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_edit_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_edit_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_item_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_item_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_edit_name'+plus_edit_index).selectpicker();
    $('#main_edit_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.row_plus_item_btn', function (event) {
    $(this).parents('.row_item').after('<div class="form-class row_item" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>可編輯人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_edit_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_edit_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_item_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_item_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_edit_name'+plus_edit_index).selectpicker();
    $('#main_edit_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.minus_item_btn', function (event) {
    $(this).parents('.row_item').remove();
});
$(document).on('click', '.plus_strategy_btn', function (event) {
    $(this).parents('.act_strategy_edit').after('<div class="form-class row_strategy" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>可編輯人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_edit_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_edit_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_strategy_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_strategy_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_edit_name'+plus_edit_index).selectpicker();
    $('#main_edit_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.row_plus_strategy_btn', function (event) {
    $(this).parents('.row_strategy').after('<div class="form-class row_strategy" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>可編輯人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_edit_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_edit_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_strategy_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_strategy_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_edit_name'+plus_edit_index).selectpicker();
    $('#main_edit_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.minus_strategy_btn', function (event) {
    $(this).parents('.row_strategy').remove();
});
$(document).on('click', '.plus_activity_btn', function (event) {
    $(this).parents('.act_activity_edit').after('<div class="form-class row_activity" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>可編輯人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_edit_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_edit_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_activity_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_activity_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_edit_name'+plus_edit_index).selectpicker();
    $('#main_edit_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.row_plus_activity_btn', function (event) {
    $(this).parents('.row_activity').after('<div class="form-class row_activity" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>可編輯人員：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="main_edit_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="main_edit_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <button type="button" class="button hollow circle row_plus_activity_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_activity_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#main_edit_name'+plus_edit_index).selectpicker();
    $('#main_edit_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.minus_activity_btn', function (event) {
    $(this).parents('.row_activity').remove();
});
//審核程序
$(document).on('click', '.plus_act_review_btn', function (event) {
    $(this).parents('.act_review_edit').after('<div class="form-class row_review" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>審核人員：</label>\
													<label for="inputEmail3">→</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="review_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true" id="review_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <input type="text" class="review_status" id="review_status' + plus_edit_index + '" size="5%" style="height:30px;display:none;" />\
													<button type="button" class="button hollow circle row_plus_act_review_btn" id="row_plus_act_review_btn' + plus_edit_index + '" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_act_review_btn" id="minus_act_review_btn' + plus_edit_index + '" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#review_name'+plus_edit_index).selectpicker();
    $('#review_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.row_plus_act_review_btn', function (event) {
    $(this).parents('.row_review').after('<div class="form-class row_review" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>審核人員：</label>\
													<label for="inputEmail3">→</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit selectpicker" data-live-search="true" id="review_name' + plus_edit_index + '">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" class="main_edit_teacher selectpicker" data-live-search="true"id="review_teacher' + plus_edit_index + '">' + edit_teacher_option + '</select>\
                                                    <input type="text" class="review_status" id="review_status' + plus_edit_index + '" size="5%" style="height:30px;display:none;" />\
													<button type="button" class="button hollow circle row_plus_act_review_btn" id="row_plus_act_review_btn' + plus_edit_index + '" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_act_review_btn" id="minus_act_review_btn' + plus_edit_index + '" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#review_name'+plus_edit_index).selectpicker();
    $('#review_teacher'+plus_edit_index).selectpicker();
    plus_edit_index++;
});
$(document).on('click', '.minus_act_review_btn', function (event) {
    $(this).parents('.row_review').remove();
});
// 策略指定題目 0802
$(document).on('click', '.plus_strategy_ans_btn', function (event) {
    $(this).parents('.act_strategy_ans_edit').after('<div class="form-class strategy_ans_edit" style="margin-top:0.5%;">\
                                                    <div><label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答題目：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" id="strategy_index_type'+ plus_ans_index + '" class="strategy_index_type">' + edu_type + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" id="strategy_index_explain'+ plus_ans_index + '" class="strategy_index_explain">' + edu_explain + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" id="strategy_index_item'+ plus_ans_index + '" class="strategy_index_item">' + edu_item + '</select></div>\
                                                    <div style="margin-top:0.5%;"><label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答項目：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" id="strategy_test_select'+ plus_ans_index + '" class="strategy_test_select">' + test_option + '</select>\
                                                    <input type="text" id="strategy_ans_id' + plus_ans_index + '" size="5%" style="height:30px;display:none;" /> \
                                                    <button type="button" class="button hollow circle row_plus_strategy_ans_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_strategy_ans_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#strategy_test_select' + plus_ans_index).trigger("change");
    plus_ans_index++;
});
$(document).on('click', '.row_plus_strategy_ans_btn', function (event) {
    $(this).parents('.strategy_ans_edit').after('<div class="form-class strategy_ans_edit" style="margin-top:0.5%;">\
                                                <div><label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答題目：</label>\
                                                <select style="height:30px;width:15%;margin-left:0.5%;" id="strategy_index_type'+ plus_ans_index + '" class="strategy_index_type">' + edu_type + '</select>\
                                                <select style="height:30px;width:15%;margin-left:0.5%;" id="strategy_index_explain'+ plus_ans_index + '" class="strategy_index_explain">' + edu_explain + '</select>\
                                                <select style="height:30px;width:15%;margin-left:0.5%;" id="strategy_index_item'+ plus_ans_index + '" class="strategy_index_item">' + edu_item + '</select></div>\
                                                <div style="margin-top:0.5%;"><label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答項目：</label>\
                                                <select style="height:30px;width:15%;margin-left:0.5%;" id="strategy_test_select'+ plus_ans_index + '" class="strategy_test_select">' + test_option + '</select>\
                                                <button type="button" class="button hollow circle row_plus_strategy_ans_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                <button type="button" class="button hollow circle minus_strategy_ans_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#strategy_test_select' + plus_ans_index).trigger("change");
    plus_ans_index++;
});
$(document).on('click', '.minus_strategy_ans_btn', function (event) {
    var delete_id = $(this).parents(".strategy_ans_edit").children().next().children().next().next().next().next().val();
    if (delete_id != "") {
        ans_delete_id.push(delete_id);
    }
    $(this).parents('.strategy_ans_edit').remove();
});

/*0711活動-指定回答項目備份----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$(document).on('click', '.plus_activity_ans_btn', function (event) {
    $(this).parents('.act_activity_ans_edit').after('<div class="form-class activity_ans_edit" style="margin-top:0.5%;">\
                                                    <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答項目：</label>\
                                                    <select style="height:30px;width:10%;margin-left:0.5%;" id="activity_test_select'+ plus_ans_index + '" class="activity_test_select">' + test_option + '</select>\
                                                    <input type="text" id="activity_ans'+ plus_ans_index + '" style="width:10%;height:30px;margin-left:0.5%;" value="如問題描述，請回答" />\
                                                    <select style="width:10%;height:30px;margin-left:0.5%;" id="activity_ans_type'+ plus_ans_index + '">\
                                                    <option value="0">文字描述</option>\
                                                    <option value="1">附檔</option></select>\
                                                    <select style="height:30px;width:10%;margin-left:0.5%;" id="supervisor_teacher_role'+ plus_ans_index + '" class="main_edit">' + main_role_option + '</select>\
                                                    <select style="height:30px;width:10%;margin-left:0.5%;" id="supervisor_teacher_name'+ plus_ans_index + '">' + edit_teacher_option + '</select>\
                                                    <input type="text" id="activity_ans_id' + plus_ans_index + '" size="5%" style="height:30px;display:none;" /> \
                                                    <button type="button" class="button hollow circle row_plus_activity_ans_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_activity_ans_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#activity_test_select' + plus_ans_index).trigger("change");
    plus_ans_index++;
});
$(document).on('click', '.row_plus_activity_ans_btn', function (event) {
    $(this).parents('.activity_ans_edit').after('<div class="form-class activity_ans_edit" style="margin-top:0.5%;">\
                                                <label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答項目：</label>\
                                                <select style="height:30px;width:10%;margin-left:0.5%;" id="activity_test_select'+ plus_ans_index + '" class="activity_test_select">' + test_option + '</select>\
                                                <input type="text" id="activity_ans'+ plus_ans_index + '" style="width:10%;height:30px;margin-left:0.5%;" value="如問題描述，請回答" />\
                                                <select style="width:10%;height:30px;margin-left:0.5%;" id="activity_ans_type'+ plus_ans_index + '">\
                                                <option value="0">文字描述</option>\
                                                <option value="1">附檔</option></select>\
                                                <select style="height:30px;width:10%;margin-left:0.5%;" id="supervisor_teacher_role'+ plus_ans_index + '" class="main_edit">' + main_role_option + '</select>\
                                                <select style="height:30px;width:10%;margin-left:0.5%;" id="supervisor_teacher_name'+ plus_ans_index + '">' + edit_teacher_option + '</select>\
                                                <input type="text" id="activity_ans_id' + plus_ans_index + '" size="5%" style="height:30px;display:none;" /> \
                                                <button type="button" class="button hollow circle row_plus_activity_ans_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                <button type="button" class="button hollow circle minus_activity_ans_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#activity_test_select' + plus_ans_index).trigger("change");
    plus_ans_index++;
});
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
$(document).on('click', '.plus_activity_ans_btn', function (event) {
    $(this).parents('.act_activity_ans_edit').after('<div class="form-class activity_ans_edit" style="margin-top:0.5%;">\
                                                    <div><label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答題目：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" id="activity_index_type'+ plus_ans_index + '" class="activity_index_type">' + edu_type + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" id="activity_index_explain'+ plus_ans_index + '" class="activity_index_explain">' + edu_explain + '</select>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" id="activity_index_item'+ plus_ans_index + '" class="activity_index_item">' + edu_item + '</select></div>\
                                                    <div style="margin-top:0.5%;"><label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答項目：</label>\
                                                    <select style="height:30px;width:15%;margin-left:0.5%;" id="activity_test_select'+ plus_ans_index + '" class="activity_test_select">' + test_option + '</select>\
                                                    <input type="text" id="activity_ans'+ plus_ans_index + '" style="width:15%;height:30px;margin-left:0.5%;" value="如問題描述，請回答" />\
                                                    <select style="width:10%;height:30px;margin-left:0.5%;margin-right:5%;" id="activity_ans_type'+ plus_ans_index + '">\
                                                    <option value="0">文字描述</option>\
                                                    <option value="1">附檔</option></select>\
                                                    <input type="text" id="activity_ans_id' + plus_ans_index + '" size="5%" style="height:30px;display:none;" /> \
                                                    <button type="button" class="button hollow circle row_plus_activity_ans_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                    <button type="button" class="button hollow circle minus_activity_ans_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                    <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#activity_test_select' + plus_ans_index).trigger("change");
    plus_ans_index++;
});
$(document).on('click', '.row_plus_activity_ans_btn', function (event) {
    $(this).parents('.activity_ans_edit').after('<div class="form-class activity_ans_edit" style="margin-top:0.5%;">\
                                                <div><label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答題目：</label>\
                                                <select style="height:30px;width:15%;margin-left:0.5%;" id="activity_index_type'+ plus_ans_index + '" class="activity_index_type">' + edu_type + '</select>\
                                                <select style="height:30px;width:15%;margin-left:0.5%;" id="activity_index_explain'+ plus_ans_index + '" class="activity_index_explain">' + edu_explain + '</select>\
                                                <select style="height:30px;width:15%;margin-left:0.5%;" id="activity_index_item'+ plus_ans_index + '" class="activity_index_item">' + edu_item + '</select></div>\
                                                <div style="margin-top:0.5%;"><label for="inputEmail3" style="visibility:hidden;"><font color="red">*</font>指定回答項目：</label>\
                                                <select style="height:30px;width:15%;margin-left:0.5%;" id="activity_test_select'+ plus_ans_index + '" class="activity_test_select">' + test_option + '</select>\
                                                <input type="text" id="activity_ans'+ plus_ans_index + '" style="width:15%;height:30px;margin-left:0.5%;" value="如問題描述，請回答" />\
                                                <select style="width:10%;height:30px;margin-left:0.5%;margin-right:5%;" id="activity_ans_type'+ plus_ans_index + '">\
                                                <option value="0">文字描述</option>\
                                                <option value="1">附檔</option></select>\
                                                <input type="text" id="activity_ans_id' + plus_ans_index + '" size="5%" style="height:30px;display:none;" /> \
                                                <button type="button" class="button hollow circle row_plus_activity_ans_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
                                                <i class="fa fa-plus" aria-hidden="true"></i></button>\
                                                <button type="button" class="button hollow circle minus_activity_ans_btn" data-quantity="minus" data-field="quantity" style="margin-left:1%;">\
                                                <i class="fa fa-minus" aria-hidden="true"></i></button></div>');
    $('#activity_test_select' + plus_ans_index).trigger("change");
    plus_ans_index++;
});
$(document).on('click', '.minus_activity_ans_btn', function (event) {
    var delete_id = $(this).parents(".activity_ans_edit").children().next().children().next().next().next().next().val();
    if (delete_id != "") {
        ans_delete_id.push(delete_id);
    }
    $(this).parents('.activity_ans_edit').remove();
});

$(document).ready(function () {
    var user_name = GetCookie("user_name");
    $("#username").text(user_name);
    var usertitle = GetCookie("role_name");
    $('#usertitle').text(usertitle);
    checkToken();
    $("#logout_btn").click(function () {
        $.ajax({
            type: "POST",
            url: "phpMod/signout_serve.php",
            async: false,
            dataType: "json",
            data: { token: token },
            success: function (data) {
                if (checkMsg(data.msg)) {
                    delete_all_cookie();
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        })
    });

    $.ajax({
        type: "POST",
        url: "phpMod/menu.php",
        async: false,
        dataType: "json",
        data: { token: token, page: 4, subpage: 2 },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var menu = data.remenu;
                $("#menu").append(menu);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })
    var y = new Date();
    var year_now = y.getFullYear() - 1911;
    $.ajax({
        type: "POST",
        url: "phpMod/spindles_get_year.php",
        async: false,
        dataType: "json",
        data: { token: token },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var database_year = data_array['year'];
                    var o = new Option(database_year, database_year);
                    $("#index_type_year").append(o);
                }
                $("#index_type_year").val(year_now);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });

    show_role();
    edu_show_option();
    item_show_main_name();
    strategy_show_option();
    activity_show_option();

    show_main_table();
    show_item_table();
    show_strategy_table();
    show_activity_table();

    $('#type').trigger("change");
});

function show_role() {
    var table_type = $('#type').val();
    main_role_option = "";
    edit_teacher_option = "";
    $.ajax({
        type: "POST",
        url: "phpMod/spindles_get_role.php",
        async: false,
        dataType: "json",
        data: { token: token },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var role_id = data_array['role_id'];
                    var role_name = data_array['name'];
                    main_role_option = main_role_option + '<option value=' + role_id + '>' + role_name + '</option>';
                }
                $("#main_role_name").html(main_role_option);
                $("#main_role_name").selectpicker('refresh');
                $("#main_edit_name").html(main_role_option);
                $("#main_edit_name").selectpicker('refresh');
                $("#main_review_name").html(main_role_option);
                $("#main_review_name").selectpicker('refresh');
                $("#item_role_name").html(main_role_option);
                $("#item_role_name").selectpicker('refresh');
                $("#item_edit_name").html(main_role_option);
                $("#item_edit_name").selectpicker('refresh');
                $("#strategy_role_name").html(main_role_option);
                $("#strategy_role_name").selectpicker('refresh');
                $("#strategy_edit_name").html(main_role_option);
                $("#strategy_edit_name").selectpicker('refresh');
                $("#activity_role_name").html(main_role_option);
                $("#activity_role_name").selectpicker('refresh');
                $("#activity_edit_name").html(main_role_option);
                $("#activity_edit_name").selectpicker('refresh');
                $("#supervisor_teacher_role").html(main_role_option);
                $("#supervisor_teacher_role").selectpicker('refresh');
				$("#review_name").html(main_role_option);
                $("#review_name").selectpicker('refresh');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });

    $.ajax({
        type: "POST",
        url: "phpMod/spindles_get_people.php",
        async: false,
        dataType: "json",
        data: { token: token, role_id: 2 },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var teacher_id = data_array['sno_members'];
                    var teacher_name = data_array['name'];
                    edit_teacher_option = edit_teacher_option + '<option value=' + teacher_id + '>' + teacher_name + '</option>';
                }
                $("#main_teacher").html(edit_teacher_option);
                $("#main_teacher").selectpicker('refresh');
                $("#main_edit_teacher").html(edit_teacher_option);
                $("#main_edit_teacher").selectpicker('refresh');
                $("#main_review_teacher").html(edit_teacher_option);
                $("#main_review_teacher").selectpicker('refresh');
                $("#item_teacher").html(edit_teacher_option);
                $("#item_teacher").selectpicker('refresh');
                $("#item_edit_teacher").html(edit_teacher_option);
                $("#item_edit_teacher").selectpicker('refresh');
                $("#strategy_teacher").html(edit_teacher_option);
                $("#strategy_teacher").selectpicker('refresh');
                $("#strategy_edit_teacher").html(edit_teacher_option);
                $("#strategy_edit_teacher").selectpicker('refresh');
                $("#activity_teacher").html(edit_teacher_option);
                $("#activity_teacher").selectpicker('refresh');
                $("#activity_edit_teacher").html(edit_teacher_option);
                $("#activity_edit_teacher").selectpicker('refresh');
                $("#supervisor_teacher_name").html(edit_teacher_option);
                $("#supervisor_teacher_name").selectpicker('refresh');
				$("#review_teacher").html(edit_teacher_option);
                $("#review_teacher").selectpicker('refresh');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}

$(document).on('change', '.main_edit', function (event) {
    var index = this.value;
    var select_id = $(this).parent().next().children(".main_edit_teacher").attr("id");
    $.ajax({
        type: "POST",
        url: "phpMod/spindles_get_people.php",
        async: false,
        dataType: "json",
        data: { token: token, role_id: index },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var Sinner = "";
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var teacher_id = data_array['sno_members'];
                    var teacher_name = data_array['name'];
                    Sinner = Sinner + '<option value=' + teacher_id + '>' + teacher_name + '</option>';
                }
                $("#" + select_id).html(Sinner);
                $("#" + select_id).selectpicker('refresh');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$(document).on('change', '.index_type_year', function (event) {
    var table = $('#example1').DataTable();
    table.clear().draw();
    table = $('#example2').DataTable();
    table.clear().draw();
    table = $('#example3').DataTable();
    table.clear().draw();
    table = $('#example4').DataTable();
    table.clear().draw();
    edu_show_option();
    edu_change_option();
    strategy_edu_change_option();
    show_main_table();
    show_item_table();
    show_strategy_table();
    show_activity_table();
    item_show_main_name();
    strategy_show_option();
    activity_show_option();
});

$("#btndelete").click(function (e) {
    var delete_bool = confirm("確定要刪除資料嗎?");
    if (delete_bool == true) {
        var table_type = $('#type').val();
        if (table_type == 0) {
            var table = $('#example1').DataTable();
            var $table = table.table().node();
            var $chkbox_all = $('tbody input[name="main_checkbox"]', $table);
            var $chkbox_checked = $('tbody input[name="main_checkbox"]:checked', $table);
            var chkbox_select_all = $('thead input[id="main_CheckAll"]', $table).get(0);
            var Data = table.rows('.selected').data();
            var id = "0";
            for (var i = 0; i < Data.length; i++) {
                if (id == "0")
                    id = Data[i][0];
                else
                    id += "," + Data[i][0];
            }
            $.ajax({
                type: "POST",
                url: "phpMod/spindles_delete.php",
                async: false,
                dataType: "json",
                data: { token: token, main: id },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        table.clear().draw();
                        show_main_table();
                    }
                    chkbox_select_all.checked = false;
                    chkbox_select_all.indeterminate = false;
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                }
            });
        }
        else if (table_type == 1) {
            var table = $('#example2').DataTable();
            var $table = table.table().node();
            var $chkbox_all = $('tbody input[name="item_checkbox"]', $table);
            var $chkbox_checked = $('tbody input[name="item_checkbox"]:checked', $table);
            var chkbox_select_all = $('thead input[id="item_CheckAll"]', $table).get(0);
            var Data = table.rows('.selected').data();
            var id = "0";
            for (var i = 0; i < Data.length; i++) {
                if (id == "0")
                    id = Data[i][0];
                else
                    id += "," + Data[i][0];
            }
            $.ajax({
                type: "POST",
                url: "phpMod/suboption_delete.php",
                async: false,
                dataType: "json",
                data: { token: token, sub_pk: id },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        table.clear().draw();
                        show_item_table();
                    }
                    chkbox_select_all.checked = false;
                    chkbox_select_all.indeterminate = false;
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                }
            });
        }
        else if (table_type == 2) {
            var table = $('#example3').DataTable();
            var $table = table.table().node();
            var $chkbox_all = $('tbody input[name="strategy_checkbox"]', $table);
            var $chkbox_checked = $('tbody input[name="strategy_checkbox"]:checked', $table);
            var chkbox_select_all = $('thead input[id="strategy_CheckAll"]', $table).get(0);
            var Data = table.rows('.selected').data();
            var id = "0";
            for (var i = 0; i < Data.length; i++) {
                if (id == "0")
                    id = Data[i][0];
                else
                    id += "," + Data[i][0];
            }
            $.ajax({
                type: "POST",
                url: "phpMod/strategy_delete.php",
                async: false,
                dataType: "json",
                data: { token: token, sno_option_strategies: id },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        table.clear().draw();
                        show_strategy_table();
                    }
                    chkbox_select_all.checked = false;
                    chkbox_select_all.indeterminate = false;
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                }
            });
        }
        else if (table_type == 3) {
            var table = $('#example4').DataTable();
            var $table = table.table().node();
            var $chkbox_all = $('tbody input[name="activity_checkbox"]', $table);
            var $chkbox_checked = $('tbody input[name="activity_checkbox"]:checked', $table);
            var chkbox_select_all = $('thead input[id="activity_CheckAll"]', $table).get(0);
            var Data = table.rows('.selected').data();
            var id = "0";
            for (var i = 0; i < Data.length; i++) {
                if (id == "0")
                    id = Data[i][0];
                else
                    id += "," + Data[i][0];
            }
            $.ajax({
                type: "POST",
                url: "phpMod/activity_delete.php",
                async: false,
                dataType: "json",
                data: { token: token, sno_option_projects: id },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        table.clear().draw();
                        show_activity_table();
                    }
                    chkbox_select_all.checked = false;
                    chkbox_select_all.indeterminate = false;
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                }
            });
        }
    }
});

//活動-指定回答項目 前三個教育部指標下拉式選單
function edu_show_option() {
    var year = $("#index_type_year").val();
    edu_type="";
    edu_explain="";
    edu_item="";
    test_option = "";
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_indicators_type.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                if (checkMsg(data.msg)) {
                    for (var i = 0; i < data.out1.length; i++) {
                        var data_array = data.out1[i];
                        var sno_edu_indicators = data_array['sno_edu_indicators'];
                        var name = data_array['name'];
                        edu_type = edu_type + '<option value=' + sno_edu_indicators + '>' + name + '</option>';
                    }
                    $("#strategy_index_type").html(edu_type);
                    $("#activity_index_type").html(edu_type);
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_indicators_direction.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, sno_edu_indicators: $('#activity_index_type').val() },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                if (checkMsg(data.msg)) {
                    for (var i = 0; i < data.out.length; i++) {
                        var data_array = data.out[i];
                        var sno_edu_indicators_sub = data_array['sno_edu_indicators_sub'];
                        var name = data_array['name'];
                        edu_explain = edu_explain + '<option value=' + sno_edu_indicators_sub + '>' + name + '</option>';

                    }
                    $("#strategy_index_explain").html(edu_explain);
                    $("#activity_index_explain").html(edu_explain);
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_indicators_project.php", 
        async: false,
        dataType: "json",
        data: { token: token, sno_edu_indicators: $('#activity_index_type').val(), edu_indicators_sub: $('#activity_index_explain').val() },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var sno_edu_indicators_detail = data_array['sno_edu_indicators_detail'];
                    var name = data_array['name'];
                    edu_item = edu_item + '<option value=' + sno_edu_indicators_detail + '>' + name + '</option>';
                }
                $("#strategy_index_item").html(edu_item);
                $("#activity_index_item").html(edu_item);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    $.ajax({
        type: "POST",
        url: "phpMod/activity_edit_get_topic.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_edu_indicators_detail:  $("#activity_index_item").val() },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var sno_project_summary = data_array['sno_strategies_summary'];
                    var name = data_array['name'];
                    test_option = test_option + '<option value=' + sno_project_summary + '>' + name + '</option>';
                }
                $("#activity_test_select").html(test_option);
                $("#strategy_test_select").html(test_option);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}

$(document).on('change', '.activity_index_type', function (event) {
    var year = $("#index_type_year").val();
    var index = this.value;
    var select_id = $(this).next().attr('id');
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_indicators_direction.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, sno_edu_indicators: index },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                if (checkMsg(data.msg)) {
                    var Sinner = "";
                    for (var i = 0; i < data.out.length; i++) {
                        var data_array = data.out[i];
                        var sno_edu_indicators_sub = data_array['sno_edu_indicators_sub'];
                        var name = data_array['name'];
                        Sinner = Sinner + '<option value=' + sno_edu_indicators_sub + '>' + name + '</option>';
                    }
                    $("#" + select_id).html(Sinner);
                    $("#" + select_id).trigger("change");
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});
$(document).on('change', '.activity_index_explain', function (event) {
    var index = this.value;
    var forward_id = $(this).prev().attr('id');
    var select_id = $(this).next().attr('id');
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_indicators_project.php", 
        async: false,
        dataType: "json",
        data: { token: token, sno_edu_indicators: $('#'+forward_id).val(), edu_indicators_sub: index },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var Sinner = "";
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var sno_edu_indicators_detail = data_array['sno_edu_indicators_detail'];
                    var name = data_array['name'];
                    Sinner = Sinner + '<option value=' + sno_edu_indicators_detail + '>' + name + '</option>';
                }
                $("#" + select_id).html(Sinner);
                $("#" + select_id).trigger("change");
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});
$(document).on('change', '.activity_index_item', function (event) {
    var index = this.value;
    var select_id = $(this).parent().next().children().next().attr('id');
    $.ajax({
        type: "POST",
        url: "phpMod/activity_edit_get_topic.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_edu_indicators_detail:  index },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var Sinner = "";
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var sno_project_summary = data_array['sno_strategies_summary'];
                    var name = data_array['name'];
                    Sinner = Sinner + '<option value=' + sno_project_summary + '>' + name + '</option>';
                }
                $("#"+select_id).html(Sinner);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});




function edu_change_option(){
    var test_plus_num = $('.activity_ans_edit').length;
    for (var i = 0; i < test_plus_num ; i++) {
        $('.activity_ans_edit').eq(i).parents().children('.activity_ans_edit').remove();
    }
    for (var i = 0; i < test_plus_num ; i++) {
        $('.plus_activity_ans_btn').click();
    }
}

function strategy_edu_change_option(){
    var test_plus_num = $('.strategy_ans_edit').length;
    for (var i = 0; i < test_plus_num ; i++) {
        $('.strategy_ans_edit').eq(i).parents().children('.strategy_ans_edit').remove();
    }
    for (var i = 0; i < test_plus_num ; i++) {
        $('.plus_strategy_ans_btn').click();
    }
}

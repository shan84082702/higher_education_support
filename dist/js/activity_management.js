$('#example4').DataTable({
    'paging': true,
    'lengthChange': true,
    'searching': true,
    'ordering': true,
    'info': true,
    'autoWidth': false,
    "columnDefs": [
        {
            "targets": [0],
            "visible": false
        }
    ],
    'oLanguage': {
        "sProcessing": "處理中...",
        "sLengthMenu": "顯示 _MENU_ 項結果",
        "sZeroRecords": "沒有匹配結果",
        "sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
        "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
        "sInfoFiltered": "(從 _MAX_ 項結果過濾)",
        "sSearch": "搜尋:",
        "oPaginate": {
            "sFirst": "首頁",
            "sPrevious": "上一頁",
            "sNext": "下一頁",
            "sLast": "尾頁"
        }
    }
});

function activity_show_option() {
    var year = $("#index_type_year").val();
    $.ajax({
        type: "POST",
        url: "phpMod/suboption_get_spindles.php",
        async: false,  ///非同步執行
        dataType: "json",
        data: { token: token, year: year },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var Sinner = "";
                if (checkMsg(data.msg)) {
                    for (var i = 0; i < data.out.length; i++) {
                        var data_array = data.out[i];
                        var main_pk = data_array['main_pk'];
                        var main_name = data_array['main_name'];
                        Sinner = Sinner + '<option value=' + main_pk + '>' + main_name + '</option>';
                    }
                    $("#activity_main_name").html(Sinner);
                }
                $('#activity_main_name').trigger("change");
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}

$("#isactivate_on").click(function (e) {
    $('#isactivate_off').css("background", "#00c0ef");
    $('#isactivate_on').css("background", "blue");
    activate_btn = 1;
});

$("#isactivate_off").click(function (e) {
    $('#isactivate_on').css("background", "#00c0ef");
    $('#isactivate_off').css("background", "blue");
    activate_btn = 0;
});

$("#activate_setup").click(function (e) {
    var day = $('#alarm_period').val();
    if (day == 0)
        alert("催繳緩衝天數不可為0");
    else {
        $.ajax({
            type: "POST",
            url: "phpMod/call_date_update.php",
            async: false,
            dataType: "json",
            data: { token: token, day: day, isactivate: activate_btn },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
    }
});

$(document).on('change', '.activity_main', function (event) {
    var year = $("#index_type_year").val();
    var main_pk = $('#activity_main_name').val();
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_suboption.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, sno_option_main: main_pk },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var Sinner = "";
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var sub_pk = data_array['sub_pk'];
                    var sub_name = data_array['sub_name'];
                    Sinner = Sinner + '<option value=' + sub_pk + '>' + sub_name + '</option>';
                }
                $("#activity_item_name").html(Sinner);
            }
            $('#activity_item_name').trigger("change");
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$(document).on('change', '.activity_item', function (event) {
    var year = $("#index_type_year").val();
    var sub_pk = $('#activity_item_name').val();
    if (sub_pk == null) {
        $("#activity_strategy_name").html("");
        $('#activity_strategy_name').trigger("change");
    }
    else {
        $.ajax({
            type: "POST",
            url: "phpMod/activity_edit_get_strategy.php",
            async: false,
            dataType: "json",
            data: { token: token, sub_pk: sub_pk, year: year },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    var Sinner = "";
                    for (var i = 0; i < data.out.length; i++) {
                        var data_array = data.out[i];
                        var sno_option_strategies = data_array['sno_option_strategies'];
                        var name = data_array['name'];
                        Sinner = Sinner + '<option value=' + sno_option_strategies + '>' + name + '</option>';
                    }
                    $("#activity_strategy_name").html(Sinner);
                }
                $('#activity_strategy_name').trigger("change");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
    }
});

/*
$(document).on('change', '.activity_strategy', function (event) {
    var sno_option_strategies = $('#activity_strategy_name').val();
    if (sno_option_strategies == null){
        test_option = "<option value=0>請選擇</option>";
        $(".activity_test_select").html(test_option);
    }
    else {
        $.ajax({
            type: "POST",
            url: "phpMod/activity_edit_get_topic.php",
            async: false,
            dataType: "json",
            data: { token: token, sno_option_strategies: sno_option_strategies },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    var Sinner = "<option value=0>請選擇</option>";
                    test_option = "";
                    for (var i = 0; i < data.out.length; i++) {
                        var data_array = data.out[i];
                        var sno_project_summary = data_array['sno_strategies_summary'];
                        var name = data_array['name'];
                        Sinner = Sinner + '<option value=' + sno_project_summary + '>' + name + '</option>';
                    }
                    test_option = Sinner;
                    $(".activity_test_select").html(Sinner);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
    }
});*/

function show_activity_table() {
    var year = $('#index_type_year').val();
    var table = $('#example4').DataTable();
    $.ajax({
        type: "POST",
        url: "phpMod/activity_get_list.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var project_pk = data_array['project_pk'];
                    var order = data_array['order'];
                    var name = data_array['name'];
                    var edu = data_array['edu'];
                    var act_date = data_array['act_date'];
                    var supervisor_name = data_array['supervisor'];
                    var edit = data_array['edit_name'];
                    var edit_name_string = "";
                    for (var j = 0; j < edit.length; j++) {
                        var edit_data = edit[j];
                        if (j > 0) {
                            edit_name_string += ",";
                        }
                        edit_name_string += edit_data['name'];
                    }
                    table.row.add([
                        project_pk,
                        "<input type='checkbox' id='" + project_pk + "' name='activity_checkbox' />",
                        year,
                        order,
                        name,

                        act_date,
                        supervisor_name,
                        edit_name_string,
                        "<a class='btn btn-warning btn-xs' id='editrow'>編輯</a>"
                    ]).draw();
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}

$("#activity_btnadd").click(function (e) {
    activity_insertORupdate(0);
});

//取得編輯列表
$('#example4 tbody').on('click', 'a#editrow', function () {
    clear_activity(false);
    var table = $('#example4').DataTable();
    var edit_data = table.row($(this).parents('tr')).data();
    var edit_data_id = edit_data[0];
    var year = $('#index_type_year').val();
    $.ajax({
        type: "POST",
        url: "phpMod/activity_edit_list.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, sno_option_projects: edit_data_id },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var can_edit = data_array['edit'];
                    document.getElementById("activity_data_id").value = edit_data_id;
                    document.getElementById("activity_main_name").value = data_array['main_id'];
                    $('#activity_main_name').trigger("change");
                    document.getElementById("activity_item_name").value = data_array['sub_id'];
                    $('#activity_item_name').trigger("change");
                    document.getElementById("activity_strategy_name").value = data_array['strategies_id'];
                    $('#activity_strategy_name').trigger("change");
                    document.getElementById("activity_role_name").value = data_array['role_id'];
                    $("#activity_role_name").selectpicker("refresh");
                    $('#activity_role_name').trigger("change");
                    document.getElementById("activity_teacher").value = data_array['supervisor'];
                    $("#activity_teacher").selectpicker("refresh");
                    var edit = data_array['edit_name'];
                    if (edit.length > 0) {
                        for (var j = 0; j < edit.length; j++) {
                            if (j == 0) {
                                document.getElementById("activity_edit_name").value = edit[j]['role_id'];
                                $("#activity_edit_name").selectpicker("refresh");
                                $('#activity_edit_name').trigger("change");
                                document.getElementById("activity_edit_teacher").value = edit[j]['member_id'];
                                $("#activity_edit_teacher").selectpicker("refresh");
                            }
                            else {
                                $('.plus_activity_btn').click();
                            }
                        }
                        for (var j = 0; j < edit.length - 1; j++) {
                            $('.row_activity').eq(j).children().children(".main_edit").val(edit[j + 1]['role_id']);
                            $('#' + $('.row_activity').eq(j).children().children(".main_edit").attr('id')).selectpicker("refresh");
                            $('#' + $('.row_activity').eq(j).children().children(".main_edit").attr('id')).trigger("change");
                            $('.row_activity').eq(j).children().children(".main_edit_teacher").val(edit[j + 1]['member_id']);
                            $('#' + $('.row_activity').eq(j).children().children(".main_edit_teacher").attr('id')).selectpicker("refresh");
                            if (can_edit == 0) {
                                $('#' + $('.row_activity').eq(j).children().children(".main_edit").attr('id')).attr('disabled', true);
                                $('#' + $('.row_activity').eq(j).children().children(".main_edit_teacher").attr('id')).attr('disabled', true);
                            }
                        }
                    }
                    else {
                        $("#activity_edit_name").selectpicker("refresh");
                        $("#activity_edit_teacher").selectpicker("refresh");
                    }
                    document.getElementById("activity_name").value = data_array['name'];
                    document.getElementById("activity_month").value = data_array['act_date'];
                    var test_topic = data_array['topic'];
                    if (test_topic.length == 0) {
                        edu_show_option();
                        document.getElementById("activity_ans").value = "如問題描述，請回答";
                        document.getElementById("activity_ans_type").value = 0;
                        for (var i = 0; i < $('.activity_ans_edit').length; i++) {
                            $('.activity_ans_edit').eq(i).parents().children('.activity_ans_edit').remove();
                        }
                    }
                    for (var j = 0; j < test_topic.length; j++) {
                        if (j == 0) {
                            document.getElementById("activity_index_type").value = test_topic[j]['sno_edu_indicators'];
                            $('#activity_index_type').trigger("change");
                            document.getElementById("activity_index_explain").value = test_topic[j]['sno_edu_indicators_sub'];
                            $('#activity_index_explain').trigger("change");
                            document.getElementById("activity_index_item").value = test_topic[j]['sno_edu_indicators_detail'];
                            $('#activity_index_item').trigger("change");
                            document.getElementById("activity_test_select").value = test_topic[j]['sno_edu_management'];
                            document.getElementById("activity_ans").value = test_topic[j]['question'];
                            document.getElementById("activity_ans_type").value = test_topic[j]['type'];
                            document.getElementById("activity_ans_id").value = test_topic[j]['sno_project_summary'];
                        }
                        else {
                            $('.plus_activity_ans_btn').click();
                        }
                    }
                    for (var j = 0; j < test_topic.length - 1; j++) {
                        $('.activity_ans_edit').eq(j).children().children().next().val(test_topic[j + 1]['sno_edu_indicators']);
                        $('#' + $('.activity_ans_edit').eq(j).children().children().next().attr('id')).trigger("change");
                        $('.activity_ans_edit').eq(j).children().children().next().next().val(test_topic[j + 1]['sno_edu_indicators_sub']);
                        $('#' + $('.activity_ans_edit').eq(j).children().children().next().next().attr('id')).trigger("change");
                        $('.activity_ans_edit').eq(j).children().children().next().next().next().val(test_topic[j + 1]['sno_edu_indicators_detail']);
                        $('#' + $('.activity_ans_edit').eq(j).children().children().next().next().next().attr('id')).trigger("change");
                        $('.activity_ans_edit').eq(j).children().next().children().next().val(test_topic[j + 1]['sno_edu_management']);
                        $('.activity_ans_edit').eq(j).children().next().children().next().next().val(test_topic[j + 1]['question']);
                        $('.activity_ans_edit').eq(j).children().next().children().next().next().next().val(test_topic[j + 1]['type']);
                        $('.activity_ans_edit').eq(j).children().next().children().next().next().next().next().val(test_topic[j + 1]['sno_project_summary']);
                        if (can_edit == 0) {
                            $('#' + $('.activity_ans_edit').eq(j).children().children().next().attr('id')).attr('disabled', true);
                            $('#' + $('.activity_ans_edit').eq(j).children().children().next().next().attr('id')).attr('disabled', true);
                            $('#' + $('.activity_ans_edit').eq(j).children().children().next().next().next().attr('id')).attr('disabled', true);
                            $('#' + $('.activity_ans_edit').eq(j).children().next().children().next().attr('id')).attr('disabled', true);
                            $('#' + $('.activity_ans_edit').eq(j).children().next().children().next().next().attr('id')).attr('disabled', true);
                            $('#' + $('.activity_ans_edit').eq(j).children().next().children().next().next().next().attr('id')).attr('disabled', true);
                        }
                    }
                    if (can_edit == 0) {
                        $('#activity_edit_name').attr('disabled', true);
                        $('#activity_edit_teacher').attr('disabled', true);
                        $('.plus_activity_btn').attr('disabled', true);
                        $('.row_plus_activity_btn').attr('disabled', true);
                        $('.minus_activity_btn').attr('disabled', true);
                        $('#activity_index_type').attr('disabled', true);
                        $('#activity_index_explain').attr('disabled', true);
                        $('#activity_index_item').attr('disabled', true);
                        $('#activity_test_select').attr('disabled', true);
                        $('#activity_ans').attr('disabled', true);
                        $('#activity_ans_type').attr('disabled', true);
                        $('.plus_activity_ans_btn').attr('disabled', true);
                        $('.row_plus_activity_ans_btn').attr('disabled', true);
                        $('.minus_activity_ans_btn').attr('disabled', true);
                    }
                    var flow = data_array['flow'];
                    if (flow.length > 0) {
                        for (var j = 0; j < flow.length; j++) {
                            if (j == 0) {
                                document.getElementById("review_name").value = flow[j]['role_id'];
                                $("#review_name").selectpicker("refresh");
                                $('#review_name').trigger("change");
                                document.getElementById("review_teacher").value = flow[j]['sno_members'];
                                $("#review_teacher").selectpicker("refresh");
                                document.getElementById("review_status").value = flow[j]['flow_status'];
                            }
                            else {
                                $('#plus_act_review_btn').click();
                            }
                        }
                        for (var j = 0; j < flow.length - 1; j++) {
                            $('.row_review').eq(j).children().children(".main_edit").val(flow[j + 1]['role_id']);
                            $('#' + $('.row_review').eq(j).children().children(".main_edit").attr('id')).selectpicker("refresh");
                            $('#' + $('.row_review').eq(j).children().children(".main_edit").attr('id')).trigger("change");
                            $('.row_review').eq(j).children().children(".main_edit_teacher").val(flow[j + 1]['sno_members']);
                            $('#' + $('.row_review').eq(j).children().children(".main_edit_teacher").attr('id')).selectpicker("refresh");
                            $('.row_review').eq(j).children().next().next().next().next().val(flow[j + 1]['flow_status']);
                            if (can_edit == 0) {
                                $('#' + $('.row_review').eq(j).children().children(".main_edit").attr('id')).attr('disabled', true);
                                $('#' + $('.row_review').eq(j).children().children(".main_edit_teacher").attr('id')).attr('disabled', true);
                                $('#' + $('.row_review').eq(j).children().next().next().next().next().next().attr('id')).attr('disabled', true);
                                $('#' + $('.row_review').eq(j).children().next().next().next().next().next().next().attr('id')).attr('disabled', true);
                            }
                        }
                    }
                    else{

                    }
                    if (can_edit == 0) {
                        $('#review_name').attr('disabled', true);
                        $('#review_teacher').attr('disabled', true);
                        $('#plus_act_review_btn').attr('disabled', true);
                    }
                }
                if (can_edit == 0) {
                    $('#activity_main_name').attr('disabled', true);
                    $('#activity_item_name').attr('disabled', true);
                    $('#activity_strategy_name').attr('disabled', true);
                    $('#activity_role_name').attr('disabled', true);
                    $('#activity_teacher').attr('disabled', true);
                    $('#activity_name').attr('disabled', true);
                    $('#activity_month').attr('disabled', true);
                }
            }
            if (can_edit == 0) {
                $("#activity_btncancel").show();
                $("#activity_btnedit").hide();
                $("#activity_btnadd").hide();
            }
            else {
                $("#activity_btnedit").show();
                $("#activity_btncancel").hide();
                $("#activity_btnadd").hide();
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$("#activity_btnedit").click(function (e) {
    var table = $('#example4').DataTable();
    activity_insertORupdate(1);
});

//新增或修改
function activity_insertORupdate(type) {
    var canadd = true;
    var updatelist = false;
    var year = $('#index_type_year').val();
    var activity_strategy_name = $("#activity_strategy_name").val();
    var activity_name = $('#activity_name').val();
    var activity_month = $('#activity_month').val();
    if (!(/^((0?[1-9]|1[012])[- -.](0?[1-9]|1[012]))*$/.test(activity_month)) && !(/^(0?[1-9]|1[0-2])$/.test(activity_month))) {
        canadd = false;
        alert("預計執行月份請依照正確格式填寫 ex:1 or 1-3 P.S.不接受'1~3'形式")
    }
    var supervisor_main = $('#activity_teacher').val();
    var plus_num = $('.row_activity').length;
    var edit_member = [];
    edit_member.push($('#activity_edit_teacher').val());
    for (var i = 0; i < plus_num; i++) {
        edit_member.push($('.row_activity').eq(i).children().children(".main_edit_teacher").val());
    }
    var test_plus_num = $('.activity_ans_edit').length;
    if ($('#activity_ans').val() == '' || activity_name == '' || activity_month == '')
        canadd = false;
    else {
        if (type == 0) {
            var answer_test = [];
            var answer_question = [];
            var answer_type = [];
            answer_test.push($('#activity_test_select').val());
            answer_question.push($('#activity_ans').val());
            answer_type.push($('#activity_ans_type').val());
        }
        else if (type == 1) {
            var update_new_answer_test = [];
            var update_new_answer_question = [];
            var update_new_answer_type = [];
            var update_old_answer_test = [];
            var update_old_answer_question = [];
            var update_old_answer_type = [];
            var update_old_answer_id = [];
            update_old_answer_test.push($('#activity_test_select').val());
            update_old_answer_question.push($('#activity_ans').val());
            update_old_answer_type.push($('#activity_ans_type').val());
            update_old_answer_id.push($('#activity_ans_id').val());

        }
    }
    for (var i = 0; i < test_plus_num; i++) {
        if (type == 0) {
            answer_test.push($('.activity_ans_edit').eq(i).children().next().children().next().val());
            answer_question.push($('.activity_ans_edit').eq(i).children().next().children().next().next().val());
            answer_type.push($('.activity_ans_edit').eq(i).children().next().children().next().next().next().val());
        }
        else if (type == 1) {
            var update_id = $('.activity_ans_edit').eq(i).children().next().children().next().next().next().next().val();
            if (update_id == "") {
                update_new_answer_test.push($('.activity_ans_edit').eq(i).children().next().children().next().val());
                update_new_answer_question.push($('.activity_ans_edit').eq(i).children().next().children().next().next().val());
                update_new_answer_type.push($('.activity_ans_edit').eq(i).children().next().children().next().next().next().val());
            }
            else {
                update_old_answer_test.push($('.activity_ans_edit').eq(i).children().next().children().next().val());
                update_old_answer_question.push($('.activity_ans_edit').eq(i).children().next().children().next().next().val());
                update_old_answer_type.push($('.activity_ans_edit').eq(i).children().next().children().next().next().next().val());
                update_old_answer_id.push($('.activity_ans_edit').eq(i).children().next().children().next().next().next().next().val());
            }
        }
    }
    var review_main = $('#review_teacher').val();
    var review_plus_num = $('.row_review').length;
    var review_member = [];
    review_member.push($('#review_teacher').val());
    for (var i = 0; i < review_plus_num; i++) {
        review_member.push($('.row_review').eq(i).children().children(".main_edit_teacher").val());
    }
    if (type == 1) {
        review_status = [];
        review_status.push($('#review_status').val());
        for (var i = 0; i < review_plus_num; i++) {
			if($('.row_review').eq(i).children().next().next().next().next().val()=="")
				review_status.push("0");
			else
				review_status.push($('.row_review').eq(i).children().next().next().next().next().val());
			//review_status.push($('.row_review').eq(i).children().children(".review_status").val());
        }
    }
    if (canadd) {
        if (type == 0) {
            $.ajax({
                type: "POST",
                url: "phpMod/activity_insert.php",
                async: false,
                dataType: "json",
                data: { token: token, name: activity_name, sno_option_strategies: activity_strategy_name, activate_yyy: year, activate_date: activity_month, supervisor_project: supervisor_main, sno_member: edit_member, sno_edu_management: answer_test, question: answer_question, type: answer_type, sno_members: review_member },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        updatelist = true;
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                }
            });
        }
        else if (type == 1) {
            var id = document.getElementById("activity_data_id").value;
            $.ajax({
                type: "POST",
                url: "phpMod/activity_update.php",
                async: false,
                dataType: "json",
                data: {
                    token: token, name: activity_name, sno_option_strategies: activity_strategy_name, activate_yyy: year, activate_date: activity_month, supervisor_project: supervisor_main, sno_option_projects: id, sno_member: edit_member,
                    insert_sno_edu_management: update_new_answer_test, insert_question: update_new_answer_question, insert_type: update_new_answer_type,
                    question: update_old_answer_question, sno_edu_management: update_old_answer_test, type: update_old_answer_type, sno_project_summary: update_old_answer_id,
                    delete_sno_project_summary: ans_delete_id, sno_members: review_member, flow_status: review_status
                },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        updatelist = true;
                        alert("編輯成功!");
                        $("#activity_btnedit").hide();
                        $("#activity_btncancel").hide();
                        $("#activity_btnadd").show();
                        ans_delete_id = [];
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                }
            });
        }
        clear_activity(updatelist);
    }
    else
        alert("Please fill all the information");
}

$("#activity_btncancel").click(function (e) {
    clear_activity(false);
    $("#activity_btnedit").hide();
    $("#activity_btncancel").hide();
    $("#activity_btnadd").show();
});

//清除資料填寫處
function clear_activity(updatelist) {
    var plus_num = $('.row_activity').length;
    var review_plus_num = $('.row_review').length;
    var test_plus_num = $('.activity_ans_edit').length;
    $("#activity_name").val("");
    $("#activity_month").val("");
    //$("#activity_test_select").val("0");
    $("#activity_ans").val("如問題描述，請回答");
    $("#activity_ans_type").val("0");
    activity_show_option();
    edu_show_option();
    show_role();
    for (var i = 0; i < plus_num; i++) {
        $('.row_activity').eq(i).parents().children('.row_activity').remove();
    }
    for (var i = 0; i < review_plus_num; i++) {
        $('.row_review').eq(i).parents().children('.row_review').remove();
    }
    for (var i = 0; i < test_plus_num; i++) {
        $('.activity_ans_edit').eq(i).parents().children('.activity_ans_edit').remove();
    }
    $('#plus_act_review_btn').attr('disabled', false);
    $('.plus_activity_btn').attr('disabled', false);
    $('.row_plus_activity_btn').attr('disabled', false);
    $('.minus_activity_btn').attr('disabled', false);
    $('.plus_activity_ans_btn').attr('disabled', false);
    $('.row_plus_activity_ans_btn').attr('disabled', false);
    $('.minus_activity_ans_btn').attr('disabled', false);
    if (updatelist == true) {
        var table = $('#example4').DataTable();
        table.clear().draw();
        show_activity_table();
    }
}

$('#example4 tbody').on('click', 'input[name="activity_checkbox"]', function (e) {
    var table = $('#example4').DataTable();
    var $row = $(this).closest('tr');
    if (this.checked) {
        $row.addClass('selected');
    }
    else {
        $row.removeClass('selected');
    }
    activity_updateDataTableSelectAllCtrl(table);
});

$("#activity_CheckAll").click(function () {
    var table = $('#example4').DataTable();
    if ($("#activity_CheckAll").prop("checked")) {
        $("input[name='activity_checkbox']").each(function () {
            $(this).prop("checked", true);
            $(this).closest('tr').addClass('selected');
        })
    }
    else {
        $("input[name='activity_checkbox']").each(function () {
            $(this).prop("checked", false);
            $(this).closest('tr').removeClass('selected');
        })
    }
});

function activity_updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[name="activity_checkbox"]', $table);
    var $chkbox_checked = $('tbody input[name="activity_checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[id="activity_CheckAll"]', $table).get(0);
    if ($chkbox_checked.length === 0) {
        chkbox_select_all.checked = false;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
    } else if ($chkbox_checked.length === $chkbox_all.length) {
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
    } else {
        chkbox_select_all.checked = true;
        if ('indeterminate' in chkbox_select_all) {
            chkbox_select_all.indeterminate = true;
        }
    }
}

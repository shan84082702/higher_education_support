$('#example3').DataTable({
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
        },
        {
            className: "strategy_td",
            "targets": [7]
        },
        {
            className: "strategy_td",
            "targets": [6]
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

function strategy_show_option() {
    var year = $("#index_type_year").val();
    $.ajax({
        type: "POST",
        url: "phpMod/suboption_get_spindles.php",
        async: false,
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
                    $("#strategy_main_name").html(Sinner);
                }
                $('#strategy_main_name').trigger("change");
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}
// $("#strategy_index_type").on("change",function(event){
//     var year = $("#index_type_year").val();
//     var sno_edu_indicators = $('#strategy_index_type').val();
//     $.ajax({
//         type: "POST",
//         url: "phpMod/strategy_get_indicators_direction.php",
//         async: false,
//         dataType: "json",
//         data: { token: token, year: year,sno_edu_indicators:sno_edu_indicators },
//         success: function (data) {
//             if (!checkMsg(data.msg)) {
//                 delete_all_cookie();
//             }
//             if (checkMsg(data.msg)) {
//                 var a="";
//                 if (checkMsg(data.msg)) {
//                     for (var i = 0; i < data.out.length; i++) {
//                         var data_array = data.out[i];
//                         var sno_edu_indicators_sub = data_array['sno_edu_indicators_sub'];
//                         var name = data_array['name'];
//                         a = a + '<option value=' + sno_edu_indicators_sub + '>' + name + '</option>';

//                     }
//                     $("#strategy_index_explain").html(a);
//                 }
//             }
//             $('#strategy_index_explain').trigger("change");
//         },
//         error: function (XMLHttpRequest, textStatus, errorThrown) {
//             alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
//         }
//     });
// });
// $(document).on('change', '.strategy_edu_index', function (event) {
//     var sno_edu_indicators = $('#strategy_index_type').val();
//     var edu_indicators_sub = $('#strategy_index_explain').val();
//     $.ajax({
//         type: "POST",
//         url: "phpMod/strategy_get_indicators_project.php", 
//         async: false,
//         dataType: "json",
//         data: { token: token, sno_edu_indicators: sno_edu_indicators, edu_indicators_sub: edu_indicators_sub },
//         success: function (data) {
//             if (!checkMsg(data.msg)) {
//                 delete_all_cookie();
//             }
//             if (checkMsg(data.msg)) {
//                 var Sinner = "";
//                 for (var i = 0; i < data.out.length; i++) {
//                     var data_array = data.out[i];
//                     var sno_edu_indicators_detail = data_array['sno_edu_indicators_detail'];
//                     var name = data_array['name'];
//                     Sinner = Sinner + '<option value=' + sno_edu_indicators_detail + '>' + name + '</option>';
//                 }
//                 $("#strategy_index_item").html(Sinner);
//             }
//         },
//         error: function (XMLHttpRequest, textStatus, errorThrown) {
//             alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
//         }
//     });
// });

$(document).on('change', '.strategy_main_item', function (event) {
    var year = $("#index_type_year").val();
    var sno_option_main = this.value;
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_suboption.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, sno_option_main: sno_option_main },
        success: function (data) {
            var Sinner = "";
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var sub_pk = data_array['sub_pk'];
                    var sub_name = data_array['sub_name'];
                    Sinner = Sinner + '<option value=' + sub_pk + '>' + sub_name + '</option>';
                }
                $("#strategy_item_name").html(Sinner);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

//策略指定題目
$(document).on('change', '.strategy_index_type', function (event) {
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
$(document).on('change', '.strategy_index_explain', function (event) {
    var index = this.value;
    var forward_id = $(this).prev().attr('id');
    var select_id = $(this).next().attr('id');
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_indicators_project.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_edu_indicators: $('#' + forward_id).val(), edu_indicators_sub: index },
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
$(document).on('change', '.strategy_index_item', function (event) {
    var index = this.value;
    var select_id = $(this).parent().next().children().next().attr('id');
    $.ajax({
        type: "POST",
        url: "phpMod/activity_edit_get_topic.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_edu_indicators_detail: index },
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
                $("#" + select_id).html(Sinner);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});






function show_strategy_table() {
    var year = $('#index_type_year').val();
    var table = $('#example3').DataTable();
    //var indicators_btn = "";
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_list.php",
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
                    var strategies_pk = data_array['strategies_pk'];
                    var main_sub_order = data_array['main_sub_order'];
                    var strategy_order = data_array['strategies_order'];
                    var strategy_name = data_array['name'];
                    var aim = data_array['aim'];
                    var aim_num = aim.length;
                    var show_aim = "";
                    if (aim_num > 20) {
                        show_aim = aim.substring(0, 19) + "<a onclick='showDetailAim(" + strategies_pk + ")'>......</a>";
                    }
                    else {
                        show_aim = aim;
                    }
                    //    indicators_btn = "<a class='btn btn-warning btn-xs' id='setuptest' data-toggle='modal' data-target='#modal-default'>設定</a>";
                    // var edu = data_array['edu'];
                    // if(edu!=null){
                    //     var edu_length = edu.length;
                    //     var show_edu="";
                    //     if(edu_length>10){
                    //         show_edu = edu.substring( 0 , 9 )+"<a onclick='showDetailEdu(" + strategies_pk + ")'>......</a>";
                    //     }
                    //     else{
                    //         show_edu=edu;
                    //     }
                    // }
                    // else{
                    //     show_edu=edu;
                    // }

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
                        strategies_pk,
                        "<input type='checkbox' id='" + strategies_pk + "' name='strategy_checkbox' />",
                        year,
                        main_sub_order,
                        strategy_order,
                        strategy_name,
                        show_aim,

                        supervisor_name,
                        edit_name_string,
                        //   indicators_btn,
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

function showDetailAim(id) {

    var year = $('#index_type_year').val();
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_edit_list.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, sno_option_strategies: id },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];

                    var aim = data_array['aim'];
                    alert("策略目標： " + aim);
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}
function showDetailEdu(id) {
    var year = $('#index_type_year').val();
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_list.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, sno_option_strategies: id },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    if (data_array['strategies_pk'] == id) {
                        var edu = data_array['edu'];
                        alert("所屬指標類型/部定指標說明/部定指標項目:\n" + edu);
                    }

                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}


/*here*/
var supervisor_strategy_id = 0;
$('#example3 tbody').on('click', 'a#setuptest', function () {
    var table = $('#example3').DataTable();
    var edit_data = table.row($(this).parents('tr')).data();
    var edit_data_id = edit_data[0];
    supervisor_strategy_id = edit_data[0];
    var table_content = '<table style="width:100%; text-align:center;">\
                            <tr>\
                            <td style="display:none"><a style="color:black;font-size: 16px">ID</a></td>\
                            <td><a style="color:black;font-size: 16px">編號</a></td>\
                            <td><a style="color:black;font-size: 16px">管考欄位名稱</a></td>\
                            <td><a style="color:black;font-size: 16px">指標<br>回答類型</a></td>\
                            </tr>';

    /*        <td><a style="color:black;font-size: 16px">負責<br>教師職稱</a></td>\
            <td><a style="color:black;font-size: 16px">負責<br>教師姓名</a></td>\
            <td><a style="color:black;font-size: 16px">執行內容與管考指標設定</a></td>\ */
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_topic.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_option_strategies: edit_data_id },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var manage_name = data_array['manage_name'];
                    var num = i + 1;
                    table_content += "<tr style='line-height:350%'>\
                                            <td>"+ num + ".</td>\
                                            <td style='display:none'><div class='supervisor_id'><input type='text' style='width:100%;height:30px;' /></div></td>\
                                            <td>"+ manage_name + "</td>\
                                            <td><div class='supervisor_ans_type'><select style='width:100%;height:30px;'>\
                                            <option value='0'>文字描述</option><option value='1'>附檔</option></select></div></td>\
                                            </tr>";
                }
                /* <td colspan='2'><div class='supervisor_teacher_role'><select style='width:48%;height:30px;' class='main_edit' id='supervisor_teacher_role"+ num + "'>\
                                 "+ main_role_option + "\
                                     </select>\
                                     <select style='width:48%;height:30px;' id='supervisor_teacher_name"+ num + "'>\
                "+ edit_teacher_option + "\
                                     </select></div></td>\
                                     <td><div class='supervisor_question'><input type='text' style='width:100%;height:30px;' /></div></td>\*/
                table_content += "</table>"
                $("#supervisor_show_table").html(table_content);
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var manage_id = data_array['manage_id'];
                    var type = data_array['type'];
                    /*   var sno_roles = data_array['sno_roles'];
                       var sno_members = data_array['sno_members'];
                       var question = data_array['question']; */
                    $('.supervisor_id').eq(i).children().val(manage_id);
                    $('.supervisor_ans_type').eq(i).children().val(type);
                    $('.supervisor_manage_name').eq(i).children().val(manage_name);
                    /*   $('.supervisor_teacher_role').eq(i).children().val(sno_roles);
                       if (sno_roles != null)
                           $('#' + $('.supervisor_teacher_role').eq(i).children().attr('id')).trigger("change");
                       $('.supervisor_teacher_role').eq(i).children().next().val(sno_members);
                       $('.supervisor_question').eq(i).children().val(question);*/
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$('#supervisor_table_save').click(function () {
    var sno_option_strategies = supervisor_strategy_id;
    var sno_edu_management = [];
    // var sno_members = [];
    var type = [];
    // var question = [];
    var plus_num = $('.supervisor_ans_type').length;
    for (var i = 0; i < plus_num; i++) {
        sno_edu_management.push($('.supervisor_id').eq(i).children().val());
        type.push($('.supervisor_ans_type').eq(i).children().val());
        //   sno_members.push($('.supervisor_teacher_role').eq(i).children().next().val());
        //   question.push($('.supervisor_question').eq(i).children().val());
    }
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_insert_topic.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_strategies_summary: sno_edu_management, type: type },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                alert("修改成功!");
                var table = $('#example3').DataTable();
                table.clear().draw();
                show_strategy_table();
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$("#strategy_btnadd").click(function (e) {
    strategy_insertORupdate(0);
});

$('#example3 tbody').on('click', 'a#editrow', function () {
    clear_strategy(false);
    var table = $('#example3').DataTable();
    var edit_data = table.row($(this).parents('tr')).data();
    var edit_data_id = edit_data[0];
    var year = $('#index_type_year').val();
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_edit_list.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, sno_option_strategies: edit_data_id },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    document.getElementById("strategy_data_id").value = edit_data_id;
                    document.getElementById("strategy_index_type").value = data_array['edu_id'];
                    $('#strategy_index_type').trigger("change");
                    document.getElementById("strategy_index_explain").value = data_array['edu_sub_id'];
                    $('#strategy_index_explain').trigger("change");
                    document.getElementById("strategy_index_item").value = data_array['detail_id'];
                    document.getElementById("strategy_id").value = data_array['strategies_order'];
                    document.getElementById("strategy_main_name").value = data_array['main_id'];
                    $('#strategy_main_name').trigger("change");
                    document.getElementById("strategy_item_name").value = data_array['sub_id'];
                    document.getElementById("strategy_name").value = data_array['name'];
                    document.getElementById("strategy_goal").value = data_array['aim'];
                    document.getElementById("strategy_role_name").value = data_array['role_id'];
                    $("#strategy_role_name").selectpicker("refresh");
                    $('#strategy_role_name').trigger("change");
                    document.getElementById("strategy_teacher").value = data_array['supervisor_id'];
                    $("#strategy_teacher").selectpicker("refresh");
                    var edit = data_array['edit_name'];
                    if (edit.length > 0) {
                        for (var j = 0; j < edit.length; j++) {
                            if (j == 0) {
                                document.getElementById("strategy_edit_name").value = edit[j]['role_id'];
                                $("#strategy_edit_teacher").selectpicker("refresh");
                                $('#strategy_edit_name').trigger("change");
                                document.getElementById("strategy_edit_teacher").value = edit[j]['member_id'];
                                $("#strategy_edit_teacher").selectpicker("refresh");
                            }
                            else {
                                $('.plus_strategy_btn').click();
                            }
                        }
                        for (var j = 0; j < edit.length - 1; j++) {
                            $('.row_strategy').eq(j).children().children(".main_edit").val(edit[j + 1]['role_id']);
                            $('#' + $('.row_strategy').eq(j).children().children(".main_edit").attr('id')).selectpicker("refresh");
                            $('#' + $('.row_strategy').eq(j).children().children(".main_edit").attr('id')).trigger("change");
                            $('.row_strategy').eq(j).children().children(".main_edit_teacher").val(edit[j + 1]['member_id']);
                            $('#' + $('.row_strategy').eq(j).children().children(".main_edit_teacher").attr('id')).selectpicker("refresh");
                        }
                    }
                    else{
                        $("#strategy_edit_name").selectpicker("refresh");
                        $("#strategy_edit_teacher").selectpicker("refresh");
                    }
                    //策略編輯取得指定題目
                    var test_topic = data_array['edu_management'];
                    if (test_topic.length == 0) {
                        edu_show_option();
                        for (var i = 0; i < $('.strategy_ans_edit').length; i++) {
                            $('.strategy_ans_edit').eq(i).parents().children('.strategy_ans_edit').remove();
                        }
                    }
                    for (var j = 0; j < test_topic.length; j++) {
                        if (j == 0) {
                            document.getElementById("strategy_index_type").value = test_topic[j]['sno_edu_indicators'];
                            $('#strategy_index_type').trigger("change");
                            document.getElementById("strategy_index_explain").value = test_topic[j]['sno_edu_indicators_sub'];
                            $('#strategy_index_explain').trigger("change");
                            document.getElementById("strategy_index_item").value = test_topic[j]['sno_edu_indicators_detail'];
                            $('#strategy_index_item').trigger("change");
                            document.getElementById("strategy_test_select").value = test_topic[j]['sno_edu_management'];

                        }
                        else {
                            $('.plus_strategy_ans_btn').click();
                        }
                    }
                    for (var j = 0; j < test_topic.length - 1; j++) {
                        $('.strategy_ans_edit').eq(j).children().children().next().val(test_topic[j + 1]['sno_edu_indicators']);
                        $('#' + $('.strategy_ans_edit').eq(j).children().children().next().attr('id')).trigger("change");
                        $('.strategy_ans_edit').eq(j).children().children().next().next().val(test_topic[j + 1]['sno_edu_indicators_sub']);
                        $('#' + $('.strategy_ans_edit').eq(j).children().children().next().next().attr('id')).trigger("change");
                        $('.strategy_ans_edit').eq(j).children().children().next().next().next().val(test_topic[j + 1]['sno_edu_indicators_detail']);
                        $('#' + $('.strategy_ans_edit').eq(j).children().children().next().next().next().attr('id')).trigger("change");
                        $('.strategy_ans_edit').eq(j).children().next().children().next().val(test_topic[j + 1]['sno_edu_management']);
                    }
                }
            }
            $("#strategy_btnedit").show();
            $("#strategy_btnadd").hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$("#strategy_btnedit").click(function (e) {
    var table = $('#example3').DataTable();
    strategy_insertORupdate(1);
});

function strategy_insertORupdate(type) {
    var year = $('#index_type_year').val();
    //var sno_edu_indicators_detail = $('#strategy_index_item').val();
    var sno_option_sub = $('#strategy_item_name').val();
    var strategy_order = $("#strategy_id").val();
    var strategy_name = $("#strategy_name").val();
    var strategy_aim = $("#strategy_goal").val();
    var supervisor = $('#strategy_teacher').val();
    var plus_num = $('.row_strategy').length;
    var edit_member = [];
    
    edit_member.push(document.getElementById("strategy_edit_teacher").value);
    for (var i = 0; i < plus_num; i++) {
        edit_member.push($('.row_strategy').eq(i).children().children(".main_edit_teacher").val());
    }
    var canadd = true;
    var updatelist = false;
    if (strategy_order == '' || strategy_name == '' || strategy_aim == '') {
        canadd = false;
        alert("Please fill all the information");
    }
    var answer_test = [];
    $(".strategy_test_select").each(function (index) {
        answer_test[index] = $(this).val();
    })
    console.log(answer_test,strategy_order,strategy_name,strategy_aim,supervisor,strategy_order,year,sno_option_sub,edit_member);
    if (canadd) {
        if (type == 0) {
            $.ajax({
                type: "POST",
                url: "phpMod/strategy_insert.php",
                async: false,
                dataType: "json",
                data: { token: token, sno_edu_management: answer_test, option_strategies_order: strategy_order, name: strategy_name, aims: strategy_aim, supervisor_strategies: supervisor, activate_yyy: year, sno_option_sub: sno_option_sub, sno_member: edit_member },
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
            var id = document.getElementById("strategy_data_id").value;
            $.ajax({
                type: "POST",
                url: "phpMod/strategy_update.php",
                async: false,
                dataType: "json",
                data: { token: token, sno_edu_management: answer_test, option_strategies_order: strategy_order, name: strategy_name, aims: strategy_aim, supervisor_strategies: supervisor, sno_option_sub: sno_option_sub, sno_option_strategies: id, sno_member: edit_member },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        updatelist = true;
                        alert("編輯成功!");
                        $("#strategy_btnedit").hide();
                        $("#strategy_btnadd").show();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                }
            });
        }
        clear_strategy(updatelist);
    }
}

function clear_strategy(updatelist) {
    var plus_num = $('.row_strategy').length;
    var test_plus_num = $('.strategy_ans_edit').length;
    $("#strategy_id").val("");
    $("#strategy_name").val("");
    $("#strategy_goal").val("");
    strategy_show_option();

    show_role();
    for (var i = 0; i < plus_num; i++) {
        $('.row_strategy').eq(i).parents().children('.row_strategy').remove();
    }
    for (var i = 0; i < test_plus_num; i++) {
        $('.strategy_ans_edit').eq(i).parents().children('.strategy_ans_edit').remove();
    }
    if (updatelist == true) {
        var table = $('#example3').DataTable();
        table.clear().draw();
        show_strategy_table();
    }
}

$('#example3 tbody').on('click', 'input[name="strategy_checkbox"]', function (e) {
    var table = $('#example3').DataTable();
    var $row = $(this).closest('tr');
    if (this.checked) {
        $row.addClass('selected');
    }
    else {
        $row.removeClass('selected');
    }
    syrategy_updateDataTableSelectAllCtrl(table);
});

$("#strategy_CheckAll").click(function () {
    var table = $('#example3').DataTable();
    if ($("#strategy_CheckAll").prop("checked")) {
        $("input[name='strategy_checkbox']").each(function () {
            $(this).prop("checked", true);
            $(this).closest('tr').addClass('selected');
        })
    }
    else {
        $("input[name='strategy_checkbox']").each(function () {
            $(this).prop("checked", false);
            $(this).closest('tr').removeClass('selected');
        })
    }
});

function strategy_updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[name="strategy_checkbox"]', $table);
    var $chkbox_checked = $('tbody input[name="strategy_checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[id="strategy_CheckAll"]', $table).get(0);
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

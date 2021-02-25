$("#example2").DataTable({
    paging: true,
    lengthChange: true,
    searching: false,
    ordering: false,
    info: true,
    autoWidth: false,
    oLanguage: {
        sProcessing: "處理中...",
        sLengthMenu: "顯示 _MENU_ 項結果",
        sZeroRecords: "沒有匹配結果",
        sInfo: "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
        sInfoEmpty: "顯示第 0 至 0 項結果，共 0 項",
        sInfoFiltered: "(從 _MAX_ 項結果過濾)",
        sSearch: "搜尋:",
        oPaginate: {
            sFirst: "首頁",
            sPrevious: "上一頁",
            sNext: "下一頁",
            sLast: "尾頁"
        }
    }
});

$(document).ready(function () {
    var opt = {
        oLanguage: {
            sProcessing: "處理中...",
            sLengthMenu: "顯示 _MENU_ 項結果",
            sZeroRecords: "沒有匹配結果",
            sInfo: "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            sInfoEmpty: "顯示第 0 至 0 項結果，共 0 項",
            sInfoFiltered: "(從 _MAX_ 項結果過濾)",
            sSearch: "搜尋:",
            oPaginate: {
                sFirst: "首頁",
                sPrevious: "上一頁",
                sNext: "下一頁",
                sLast: "尾頁"
            }
        }
    };

    var token = GetCookie("token");
    var table = $('#example2').DataTable();
    checkToken();
    var pid = GetCookie("pid");
    var t = $("#example1").DataTable(opt);
    var user_name = GetCookie("user_name");
    var role_name = GetCookie("role_name");
    $("#usertitle").text(role_name);
    $("#username").text(user_name);
    //取得人員下拉式選單

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
                console.log("角色", data.out);
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var role_id = data_array['role_id'];
                    var role_name = data_array['name'];
                    main_role_option = main_role_option + '<option value=' + role_id + '>' + role_name + '</option>';

                }
                $("#review_role").html(main_role_option);

            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    var role_select = $(".review_role").val();
    console.log("rowselect", role_select);
    $.ajax({
        type: "POST",
        url: "phpMod/spindles_get_people.php",
        async: false,
        dataType: "json",
        data: { token: token, role_id: role_select },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                console.log("人", data.out);
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var teacher_id = data_array['sno_members'];
                    var teacher_name = data_array['name'];
                    edit_teacher_option = edit_teacher_option + '<option value=' + teacher_id + '>' + teacher_name + '</option>';

                }
                $('#review_people').html(edit_teacher_option);

            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    $(".review_role").trigger("change");
    $(".review_people").selectpicker('refresh');

    $.ajax({
        type: "POST",
        url: "phpMod/menu.php",
        async: false, ///非同步執行
        dataType: "json",
        data: { token: token, page: 3, subpage: 2 },
        success: function (data) {
            if (checkMsg(data.msg)) {
                var menu = data.remenu;
                $("#menu").append(menu);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });

    $.ajax({
        type: "POST",
        url: "phpMod/find_paper_detail_serve.php",
        async: false, ///非同步執行
        dataType: "json",
        data: { token: token, pid: pid },
        success: function (data) {
            if (checkMsg(data.msg)) {
                console.log(data);
                var isedit = data.isedit;
                if (isedit == '0') {
                    $("#edit_paper_btn").hide();
                    $("#detail").hide();
                }
                $("#review_state").text(data.outt.review_state);
                var pname = data.outt.pname;
                $("#pname").val(pname);
                var estimated_date = data.outt.estimated_date;
                $("#estimated_date").val(estimated_date);
                var opt_name = data.outt.opt_name;
                $("#opt_name").val(opt_name);
                var edu_name = data.outt.edu_name;
                $("#edu_name").val(edu_name);
                var edu_detail = data.outt.edu_detail;
                $("#edu_detail").val(edu_detail);
                var edusub_name = data.outt.edusub_name;
                $("#edusub_name").val(edusub_name);
                if (data.outt.activate_date != " ") {
                    var activate_date = data.outt.activate_date.split(",");
                    var act_date_length = activate_date.length;
                } else var act_date_length = activate_date.length;

                for (var i = 1; i < act_date_length; i++) {
                    $(".act_date_row").after(
                        '<div class="row row2"><div class="col-sm-3" style="text-align:right"><\/div><div class="col-sm-6 act_date" ><input type="date" class="date" disabled><\/div><\/div>'
                    );
                }
                $(".act_date")
                    .children("input")
                    .each(function (index) {
                        $(this).val(activate_date[index]);
                    });
                var activate_location = data.outt.activate_location;
                $("#activate_location").val(activate_location);
                var paricipant_number = data.outt.paricipant_number;
                $("#paricipant_number").val(paricipant_number);
                var cost = data.outt.cost;
                $("#cost").val(cost);
                var isongante = data.outt.isongante;
                var isinresulf = data.outt.isinresulf;

                if (isongante == 0)
                    $("input:radio[name=isongante]")
                        .filter("[value=0]")
                        .prop("checked", true);
                else if (isongante == 1)
                    $("input:radio[name=isongante]")
                        .filter("[value=1]")
                        .prop("checked", true);
                else {
                    $("input:radio[name=isongante]")
                        .filter("[value=0]")
                        .prop("checked", false);
                    $("input:radio[name=isongante]")
                        .filter("[value=1]")
                        .prop("checked", false);
                }
                if (isinresulf == 0)
                    $("input:radio[name=isinresulf]")
                        .filter("[value=0]")
                        .prop("checked", true);
                else if (isinresulf == 1)
                    $("input:radio[name=isinresulf]")
                        .filter("[value=1]")
                        .prop("checked", true);
                else {
                    $("input:radio[name=isinresulf]")
                        .filter("[value=0]")
                        .prop("checked", false);
                    $("input:radio[name=isinresulf]")
                        .filter("[value=1]")
                        .prop("checked", false);
                }
                var description = data.outt.description;
                $("#description").val(description);
                $("#reason").val(data.outt.reason);
                var results = JSON.parse(data.outt.results);

                if (results[0]["qid"] != "") {
                    for (var i = results.length - 1; i >= 0; --i) {
                        console.log(results[i]["type"], results[i]["qid"]);
                        if (results[i]["type"] == 0) {
                            if (results[i]["result"] == null)
                                results[i]["result"] = "";
                            console.log(results[i]);
                            $("#q_row").after(
                                '<div class="row q_row2">' +
                                '<div class="col-sm-3" style="text-align:right">' +
                                '<a   style="color:#000000; font-size:18px" >管考指標 : <\/a>' +
                                "<\/div>" +
                                '<div class="q_col col-sm-6">' +
                                '<a  class="question" style="color:#000000; font-size:18px" ><\/a></div></div><br/>' +
                                "<div class='row'><div class='col-sm-3' style='text-align:right'>" +
                                '<a   style="color:#000000; font-size:18px" >衡量基準 : <\/a>' +
                                "<\/div>" +
                                '<div class=" col-sm-6">' +
                                '<a  class="edu_name2" style="color:#000000; font-size:18px" ><\/a></div></div><br/>' +
                                "<div class='row'><div class='col-sm-3' style='text-align:right'>" +
                                '<a   style="color:#000000; font-size:18px" >執行內容 : <\/a></div><div class="col-sm-6"><textarea disabled style="width:100%" rows="4" class="result" id="' + results[i]["qid"] + '">' + results[i]["result"] + '<\/textarea></div></div><br/>'
                            );
                        } else {
                            if (results[i]['result'] != null) {
                                var filepath = results[i]['result'];
                                var filename = filepath.split('/');
                                filename = filename[filename.length - 1];
                                $("#q_row").after(
                                    '<div class="row q_row2">' +
                                    '<div class="col-sm-3" style="text-align:right">' +
                                    '<a   style="color:#000000; font-size:18px" >管考指標 : <\/a>' +
                                    "<\/div>" +
                                    '<div class="q_col col-sm-6">' +
                                    '<a  class="question" style="color:#000000; font-size:18px" ><\/a></div></div><br/>' +
                                    "<div class='row'><div class='col-sm-3' style='text-align:right'>" +
                                    '<a   style="color:#000000; font-size:18px" >衡量基準 : <\/a>' +
                                    "<\/div>" +
                                    '<div class=" col-sm-6">' +
                                    '<a  class="edu_name2" style="color:#000000; font-size:18px" ><\/a></div></div><br/>' +
                                    "<div class='row'><div class='col-sm-3' style='text-align:right'>" +
                                    '<a   style="color:#000000; font-size:18px" >執行內容 : <\/a></div><div class="col-sm-6"><a href="' + filepath +
                                    '">' + filename + '</a></div></div><br/>'

                                );
                            } else {
                                $("#q_row").after(
                                    '<div class="row q_row2">' +
                                    '<div class="col-sm-3" style="text-align:right">' +
                                    '<a   style="color:#000000; font-size:18px" >管考指標 : <\/a>' +
                                    "<\/div>" +
                                    '<div class="q_col col-sm-6">' +
                                    '<a  class="question" style="color:#000000; font-size:18px" ><\/a></div></div><br/>' +
                                    "<div class='row'><div class='col-sm-3' style='text-align:right'>" +
                                    '<a   style="color:#000000; font-size:18px" >衡量基準 : <\/a>' +
                                    "<\/div>" +
                                    '<div class=" col-sm-6">' +
                                    '<a  class="edu_name2" style="color:#000000; font-size:18px" ><\/a></div></div><br/>' +
                                    "<div class='row'><div class='col-sm-3' style='text-align:right'>" +
                                    '<a   style="color:#000000; font-size:18px" >執行內容 : <\/a></div><div class="col-sm-6"><input type="file" name="q' + results[i]["qid"] + '"/></div></div><br/>'

                                );
                            }

                        }

                    }
                    $(".question").each(function (index) {
                        $(this).text(results[index]["edu_name2"]);
                    });
                    $(".edu_name2").each(function (index) {
                        $(this).text(results[index]["question"]);
                    })

                }
                else {
                    $(".q_row").hide();
                    //results = " ";
                }


                var cooperation_project = data.outt.cooperation_project;
                if (cooperation_project == "") {
                    $("input:radio[name=cooperation_project]")
                        .filter("[value=0]")
                        .prop("checked", true);
                } else {
                    $("input:radio[name=cooperation_project]")
                        .filter("[value=1]")
                        .prop("checked", true);
                    $("#cooperation_project_text").val(cooperation_project);
                }
                var target = data.outt.target;
                var target_length = data.outt.target.length
                console.log(data.outt.target.length);
                for (var i = 0; i < target_length; i++) {
                    if (target[i] == 0)
                        $("input:checkbox[name=target1]")
                            .filter("[value=0]")
                            .prop("checked", true);
                    if (target[i] == 1)
                        $("input:checkbox[name=target2]")
                            .filter("[value=1]")
                            .prop("checked", true);
                    if (target[i] == 2)
                        $("input:checkbox[name=target3]")
                            .filter("[value=2]")
                            .prop("checked", true);
                    if (target[i] == 3)
                        $("input:checkbox[name=target4]")
                            .filter("[value=3]")
                            .prop("checked", true);
                }
                $("#recommendations").val(data.outt.recommendations);
                var sign_path = data.outt.sign_path;

                if (sign_path != null && sign_path != "" && sign_path != undefined) {
                    var sign_name = sign_path.split("/");
                    sign_name = sign_name[sign_name.length - 1];
                }
                else
                    var sign_name = "";

                console.log(sign_name);
                $("#sign_path").text(sign_name);
                $("#sign_path").attr("href", sign_path);

                var annex_path = data.outt.annex_path;
                if (annex_path != null && annex_path != "" && annex_path != undefined) {
                    var annex_name = annex_path.split("/");
                    annex_name = annex_name[annex_name.length - 1];
                    console.log(annex_name);
                }
                else
                    var annex_name = "";

                $("#annex_path").text(annex_name);
                $("#annex_path").attr("href", annex_path);


                var img_arr_length = data.img.length;
                if (img_arr_length != 0) {
                    for (var i = 0; i < img_arr_length; i++) {
                        $(".img_row").after(
                            '<div class="row img_row2">' +
                            '<div class="col-sm-3" style="text-align:right">' +
                            "<\/div>" +
                            '<div class="col-sm-9 act_img" >' +
                            '<table width="80%" height="80%" border="1" ><tbody><tr   align="center"><td  colspan="2"><img  class="img_src" style="padding-top:1%;padding-bottom:1%;width:30%"></td></tr>' +
                            '<tr ><td width="50%"><span style="padding-left:2%">圖片說明 : </span></td><td width="50%"><span class="img_dec" style="padding-left:2%"></span></td></tr></tbody></table></div>' +
                            '<\/div><br>'
                        );
                    }
                    $(".img_src").each(function (index) {
                        $(this).attr("src", data.img[index]["img_path"]);
                    });
                    $(".img_dec").each(function (index) {
                        $(this).text(data.img[index]["img_des"]);
                    });
                }

            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    $(document).on('change', '.review_role select', function (event) {
        var index = this.value;
        var select_id = $(this).parent().next().children(".review_people").attr("id");

        $("#" + select_id).find('option').remove();

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
                    $("#" + select_id).append(Sinner);
                    $("#" + select_id).selectpicker("refresh");

                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
    });
    // $(document).on('change', '.review_role', function (event) {
    //     var index = this.value;

    //     var select_id = $(this).next().attr('id');
    //     console.log("select_id", select_id);
    //     $(this).next().find('option').remove();

    //     $.ajax({
    //         type: "POST",
    //         url: "phpMod/spindles_get_people.php",
    //         async: false,
    //         dataType: "json",
    //         data: { token: token, role_id: index },
    //         success: function (data) {
    //             if (!checkMsg(data.msg)) {
    //                 delete_all_cookie();
    //             }
    //             if (checkMsg(data.msg)) {
    //                 var Sinner = "";
    //                 for (var i = 0; i < data.out.length; i++) {
    //                     var data_array = data.out[i];
    //                     var teacher_id = data_array['sno_members'];
    //                     var teacher_name = data_array['name'];
    //                     Sinner = Sinner + '<option value=' + teacher_id + '>' + teacher_name + '</option>';
    //                 }
    //                 $("#" + select_id).append(Sinner);


    //             }
    //         },
    //         error: function (XMLHttpRequest, textStatus, errorThrown) {
    //             alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
    //         }
    //     });
    // });


    $("#logout_btn").click(function () {
        $.ajax({
            type: "POST",
            url: "phpMod/signout_serve.php",
            async: false, ///非同步執行
            dataType: "json",
            data: { token: token },
            success: function (data) {
                if (checkMsg(data.msg)) {
                    DelCookie("token", domain);
                    DelCookie("name", domain);
                    document.location.href = "login.html";
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
    });
    $("#save_btn").click(function () {
        document.location.href = "result.html";
    });
    $("#flow_update_div").hide();

    //審核流程下拉式選單
    var plus_edit_index = 1;
    $("#flow_plus_btn").click(function () {
        $(this).parents(".review_flow").after(
            '<div class="row review_flow_plus" style="margin-top:1%;">' +
            '<div class="col-sm-12" style="padding-left: 20%">' +
            '&nbsp;<span>→</span>&nbsp;&nbsp;' +
            '<select class="review_role " data-live-search="true" id="review_role_' + plus_edit_index + '" style="width: 20%">' + main_role_option + '</select>&nbsp;' +
            '<select class="review_people " data-live-search="true" id="review_people_' + plus_edit_index + '" style="width: 20%">' + edit_teacher_option + '</select>&nbsp;' +
            '<input type="text" value="0" class="review_status" id="review_status_' + plus_edit_index + '" size="5%" style="height:30px;display:none;" />' +
            '<button class="button hollow circle plus_btn_role" data-field="quantity" data-quantity="plus" type="button">' +
            '<i aria-hidden="true" class="fa fa-plus"></i>' +
            '</button>&nbsp;&nbsp;&nbsp;' +
            '<button class="button hollow circle minus_btn_role" data-field="quantity" data-quantity="minus" type="button">' +
            '<i aria-hidden="true" class="fa fa-minus"></i>' +
            '</button>' +
            '</div>' +
            '</div>'
        );
        plus_edit_index++;
        $('select').selectpicker();
    });

    $(document).on("click", ".plus_btn_role", function (event) {
        $(this).parents(".review_flow_plus").after(
            '<div class="row review_flow_plus" style="margin-top:1%;">' +
            '<div class="col-sm-12" style="padding-left: 20%">' +
            '&nbsp;<span>→</span>&nbsp;&nbsp;' +
            '<select class="review_role " data-live-search="true" id="review_role_' + plus_edit_index + '" style="width: 20%">' + main_role_option + '</select>&nbsp;' +
            '<select class="review_people " data-live-search="true" id="review_people_' + plus_edit_index + '" style="width: 20%">' + edit_teacher_option + '</select>&nbsp;' +
            '<input type="text" value="0" class="review_status" id="review_status_' + plus_edit_index + '" size="5%" style="height:30px;display:none;" />' +
            '<button class="button hollow circle plus_btn_role" data-field="quantity" data-quantity="plus" type="button">' +
            '<i aria-hidden="true" class="fa fa-plus"></i>' +
            '</button>&nbsp;&nbsp;&nbsp;' +
            '<button class="button hollow circle minus_btn_role" data-field="quantity" data-quantity="minus" type="button">' +
            '<i aria-hidden="true" class="fa fa-minus"></i>' +
            '</button>' +
            '</div>' +
            '</div>'
        );
        plus_edit_index++;
        $('select').selectpicker();

    });
    $(document).on("click", ".minus_btn_role", function (event) {
        $(this).parents('.review_flow_plus').remove();

    });
    //取得退件人員下拉式選單
    $.ajax({
        type: "POST",
        url: "phpMod/paper_flow_reject_people_list.php",
        async: false, ///非同步執行
        dataType: "json",
        data: {
            token: token,
            sno_option_projects: pid
        },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                console.log(data);
                var reject_people_length = data.out.length;
                var reject_people = data.out;
                for (var i = 0; i < reject_people_length; i++) {
                    var o = new Option(reject_people[i]["name"], reject_people[i]["sno_members"]);
                    $("#reject_select").append(o);
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    $("#open_flow_update").click(function () {
        if ($("#flow_update_div").is(":visible")) {
            $("#flow_update_div").hide();
            $(this).text("編輯審核流程");
        }
        else {
            $("#flow_update_div").show();
            $(this).text("關閉");
        }

    })


    $("#detail").click(function () {
        if ($("#flow_update_div").is(":visible")) {
            $("#flow_update_div").hide();
            $(this).text("編輯審核流程");
        }
        table.clear();
        $.ajax({
            type: "POST",
            url: "phpMod/letter_get_list.php",
            async: false, ///非同步執行
            dataType: "json",
            data: {
                token: token,
                sno_option_projects: pid
            },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    console.log(data);

                    for (var i = 0; i < data.out.length; i++) {
                        var data_array = data.out;
                        var created_at = data_array[i]["created_at"];
                        var source = data_array[i]["source"];
                        var exmsg = data_array[i]["exmsg"];
                        var type = data_array[i]["type"];
                        var target = data_array[i]["target"];

                        table.row.add([created_at, source, target, exmsg, type]).draw(false);
                    }
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
    })

    //取得審核流程(顯示)
    var end_flag = 0;
    $.ajax({
        type: "POST",
        url: "phpMod/paper_flow_people_get_list.php",
        async: false, ///非同步執行
        dataType: "json",
        data: {
            token: token,
            sno_option_projects: pid
        },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var flow = data.out;
                var flow_length = data.out.length;
                if (flow_length > 0) {
                    console.log("status", flow[0]['flow_status']);
                    if (flow[flow_length - 1]['flow_status'] == 2) {
                        end_flag = 1;
                    }
                    for (var j = 0; j < flow_length; j++) {
                        if (flow[j]['flow_status'] != 2) {
                            $(".flow_display").append(
                                '<div class="col-sm-12" style="margin-left:30%;font-size:20px"><span>→</span>&nbsp;&nbsp;<span>' + flow[j]['name'] + '</span></div><br/>'
                            )
                        }
                        else if (flow[j]['flow_status'] == 2) {
                            $(".flow_display").append(
                                '<div class="col-sm-12" style="margin-left:30%;color:red;font-size:30px"><span >→</span>&nbsp;&nbsp;<span >' + flow[j]['name'] + '</span></div><br/>'
                            )
                        }

                    }
                }

            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    if (end_flag == 1) {
        $("#send_project").text("審核完成");
    }
    else {
        $("#send_project").text("送審");
    }


    //取得審核流程(編輯)
    $.ajax({
        type: "POST",
        url: "phpMod/paper_flow_people_get_list.php",
        async: false, ///非同步執行
        dataType: "json",
        data: {
            token: token,
            sno_option_projects: pid
        },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var flow = data.out;
                var flow_length = data.out.length;
                console.log(flow);
                for (var i = 0; i < flow_length; i++) {
                    if (i == 0) {
                        $("#review_role").val(flow[i]['role_id']);
                        $("#review_role").selectpicker("refresh");
                        $("#review_role").trigger("change");
                        $("#review_people").val(flow[i]['sno_members']);
                        $("#review_people").selectpicker("refresh");
                        $("#review_status").val(flow[i]['flow_status']);
                        if (flow[i]['flow_status'] != 0) {
                            $(".review_flow").children().children().attr('disabled', true);
                        }
                        if (flow[i]['flow_status'] == 2) {
                            $(".review_flow").children().children().next().next().next().attr('disabled', false);
                        }
                    }
                    else {
                        $("#flow_plus_btn").click();
                    }
                }
                //alert($("#review_role").attr("class"));
                
                for (var j = 1; j < flow.length; j++) {
                    $("#review_role_" + j).val(flow[flow.length - j]['role_id']);
					$("#review_role_" + j).selectpicker("refresh");
                    $("#review_role_" + j).trigger("change");
                    $("#review_people_" + j).val(flow[flow.length - j]['sno_members']);
                    $("#review_status_" + j).val(flow[flow.length - j]['flow_status']);
					$("#review_people_" + j).selectpicker("refresh");
                    if ($("#review_status_" + j).val() != 0) {
                        $("#review_role_" + j).attr('disabled', true);
                        $("#review_people_" + j).attr('disabled', true);
                        $("#review_status_" + j).next().attr('disabled', true);
                        $("#review_status_" + j).next().next().attr('disabled', true);
                    }
                    if ($("#review_status_" + j).val() == 2) {
                        $("#review_status_" + j).next().attr('disabled', false);
                    }
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    //更新審核流程
    $("#updata_flow").click(function () {
        $(".flow_display").empty();
        //取得流程
        var flow_number = [];
        var flow_members = [];
        var flow_status = [];
        $(".review_people select").each(function (index) {
            flow_members[index] = $(this).val();
            flow_status[index] = $(this).parent().next().val();

            flow_number[index] = index + 1;
        })
        console.log(flow_members, flow_number, flow_status);

        $.ajax({
            type: "POST",
            url: "phpMod/paper_flow_people_update.php",
            async: false, ///非同步執行
            dataType: "json",
            data: {
                token: token,
                sno_option_projects: pid,
                flow_number: flow_number,
                sno_members: flow_members,
                flow_status: flow_status
            },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    console.log(data.msg);
                    alert("更新成功");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        })

        $.ajax({
            type: "POST",
            url: "phpMod/paper_flow_people_get_list.php",
            async: false, ///非同步執行
            dataType: "json",
            data: {
                token: token,
                sno_option_projects: pid
            },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    var flow = data.out;
                    var flow_length = data.out.length;
                    if (flow_length > 0) {
                        console.log("status", flow[0]['flow_status']);
                        if (flow[flow_length - 1]['flow_status'] == 2) {
                            end_flag = 1;
                        }
                        else
                            end_flag = 0;
                        $(".flow_display").append('<div class="col-sm-3" style="text-align: right"><span style="font-size:24px">審核流程：</span></div>')
                        for (var j = 0; j < flow_length; j++) {
                            if (flow[j]['flow_status'] != 2) {
                                $(".flow_display").append(
                                    '<div class="col-sm-12" style="margin-left:30%;font-size:20px"><span>→</span>&nbsp;&nbsp;<span>' + flow[j]['name'] + '</span></div><br/>'
                                )
                            }
                            else if (flow[j]['flow_status'] == 2) {
                                $(".flow_display").append(
                                    '<div class="col-sm-12" style="margin-left:30%;color:red;font-size:30px"><span >→</span>&nbsp;&nbsp;<span >' + flow[j]['name'] + '</span></div><br/>'
                                )
                            }

                        }
                    }

                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
        if (end_flag == 1) {
            $("#send_project").text("審核完成");
        }
        else {
            $("#send_project").text("送審");
        }

    });

    //審核通過or送審
    $("#send_project").click(function () {
        var message = $("#recomand").val();
        $("body").mLoading();
        $.ajax({
            type: "POST",
            url: "phpMod/paper_flow_send.php",
            async: false, ///非同步執行
            dataType: "json",
            data: {
                token: token,
                sno_option_projects: pid,
                sender_name:user_name,
                message:message
            },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    document.location.href = "result.html";
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
    });
    //退件
    $("#reject_send_btn").click(function () {
        var message = $("#recomand").val();
        var reject_member = $("#reject_select").val();
        console.log(reject_member);
        $.ajax({
            type: "POST",
            url: "phpMod/paper_flow_reject.php",
            async: false, ///非同步執行
            dataType: "json",
            data: {
                token: token,
                sno_option_projects: pid,
                sno_members: reject_member,
                message: message,
                sender_name:user_name
            },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    alert("訊息送出");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
        table.clear();
        $.ajax({
            type: "POST",
            url: "phpMod/letter_get_list.php",
            async: false, ///非同步執行
            dataType: "json",
            data: {
                token: token,
                sno_option_projects: pid
            },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    if (data.issupervisor != 1) {
                        $("#send_access").hide();
                    }
                    for (var i = 0; i < data.out.length; i++) {
                        var data_array = data.out;
                        var created_at = data_array[i]["created_at"];
                        var source = data_array[i]["source"];
                        var exmsg = data_array[i]["exmsg"];
                        var type = data_array[i]["type"];
                        var target = data_array[i]["target"];

                        table.row.add([created_at, source, target, exmsg, type]).draw(false);
                    }
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
        document.location.href = "result3.html";
    });
    //返回編輯報告書
    $("#edit_paper_btn").click(function () {
        document.location.href = "result2.html";
    })
    $("#go_back_btn").click(function () {
        window.history.back();
    })

});
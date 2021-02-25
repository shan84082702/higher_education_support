$('#example1').DataTable({
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

function show_main_table() {
    var year = $('#index_type_year').val();
    var table = $('#example1').DataTable();
    $.ajax({
        type: "POST",
        url: "phpMod/spindles_get_list.php",
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
                    var son_id = data_array['son_id'];
                    var main_order = data_array['main_oder'];
                    var main_name = data_array['main_name'];
                    var supervisor_name = data_array['supervisor'];
                    var edit = data_array['edit_name'];
                    var review = data_array['review'];
                    var edit_name_string = "";
                    var review_name_string = "";
                    for (var j = 0; j < edit.length; j++) {
                        var edit_data = edit[j];
                        if (j > 0) {
                            edit_name_string += ",";
                        }
                        edit_name_string += edit_data['name'];
                    }
                    for (var j = 0; j < review.length; j++) {
                        var review_data = review[j];
                        if (j > 0) {
                            review_name_string += ",";
                        }
                        review_name_string += review_data['name'];
                    }
                    table.row.add([
                        son_id,
                        "<input type='checkbox' id='" + son_id + "' name='main_checkbox' />",
                        year,
                        main_order,
                        main_name,
                        supervisor_name,
                        edit_name_string,
                        review_name_string,
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

$("#main_btnadd").click(function (e) {
    main_insertORupdate(0);
});

$('#example1 tbody').on('click', 'a#editrow', function () {
    clear_main(false);
    var table = $('#example1').DataTable();
    var edit_data = table.row($(this).parents('tr')).data();
    var edit_data_id = edit_data[0];
    var year = $('#index_type_year').val();
    $.ajax({
        type: "POST",
        url: "phpMod/spindles_edit_list.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, main_pk: edit_data_id },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    document.getElementById("main_data_id").value = edit_data_id;
                    document.getElementById("main_id").value = data_array['main_oder'];
                    document.getElementById("main_name").value = data_array['main_name'];
                    document.getElementById("main_role_name").value = data_array['role_id'];
                    $("#main_role_name").selectpicker("refresh");
                    $('#main_role_name').trigger("change");
                    document.getElementById("main_teacher").value = data_array['supervisor_id'];
                    $("#main_teacher").selectpicker("refresh");
                    var edit = data_array['edit_name'];
                    if (edit.length > 0) {
                        for (var j = 0; j < edit.length; j++) {
                            if (j == 0) {
                                document.getElementById("main_edit_name").value = edit[j]['role_id'];
                                $("#main_edit_name").selectpicker("refresh");
                                $('#main_edit_name').trigger("change");
                                document.getElementById("main_edit_teacher").value = edit[j]['member_id'];
                                $("#main_edit_teacher").selectpicker("refresh");
                            }
                            else {
                                $('.plus_btn').click();
                            }
                        }
                        for (var j = 0; j < edit.length - 1; j++) {
                            $('.row_main').eq(j).children().children(".main_edit").val(edit[j + 1]['role_id']);
                            $('#' + $('.row_main').eq(j).children().children(".main_edit").attr('id')).selectpicker("refresh");
                            $('#' + $('.row_main').eq(j).children().children(".main_edit").attr('id')).trigger("change");
                            $('.row_main').eq(j).children().children(".main_edit_teacher").val(edit[j + 1]['member_id']);
                            $('#' + $('.row_main').eq(j).children().children(".main_edit_teacher").attr('id')).selectpicker("refresh");
                        }
                    }
                    else {
                        $("#main_edit_name").selectpicker("refresh");
                        $("#main_edit_teacher").selectpicker("refresh");
                    }

                    var review = data_array['review'];
                    if (review.length > 0) {
                        for (var j = 0; j < review.length; j++) {
                            if (j == 0) {
                                document.getElementById("main_review_name").value = review[j]['sno_roles'];
                                $("#main_review_name").selectpicker("refresh");
                                $('#main_review_name').trigger("change");
                                document.getElementById("main_review_teacher").value = review[j]['sno_members'];
                                $("#main_review_name").selectpicker("refresh");
                            }
                            else {
                                $('.plus_review_btn').click();
                            }
                        }
                        for (var j = 0; j < review.length - 1; j++) {
                            $('.row_riview_main').eq(j).children().children(".main_edit").val(review[j + 1]['sno_roles']);
                            $('#' + $('.row_riview_main').eq(j).children().children(".main_edit").attr('id')).selectpicker("refresh");
                            $('#' + $('.row_riview_main').eq(j).children().children(".main_edit").attr('id')).trigger("change");
                            $('.row_riview_main').eq(j).children().children(".main_edit_teacher").val(review[j + 1]['sno_members']);
                            $('#' + $('.row_riview_main').eq(j).children().children(".main_edit_teacher").attr('id')).selectpicker("refresh");
                        }
                    }
                    else {
                        $("#main_review_name").selectpicker("refresh");
                        $("#main_review_teacher").selectpicker("refresh");
                    }
                }
            }
            $("#main_btnedit").show();
            $("#main_btnadd").hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$("#main_btnedit").click(function (e) {
    var table = $('#example1').DataTable();
    main_insertORupdate(1);
});

function main_insertORupdate(type) {
    var year = $('#index_type_year').val();
    var main_order = document.getElementById("main_id").value;
    var main_name = document.getElementById("main_name").value;
    var supervisor_main = $('#main_teacher').val();
    var plus_num = $('.row_main').length;
    var edit_member = [];
    edit_member.push(document.getElementById("main_edit_teacher").value);
    for (var i = 0; i < plus_num; i++) {
        edit_member.push($('.row_main').eq(i).children().children(".main_edit_teacher").val());
    }
    var plus_review_num = $('.row_riview_main').length;
    var sno_review = [];
    sno_review.push(document.getElementById("main_review_teacher").value);
    for (var i = 0; i < plus_review_num; i++) {
        sno_review.push($('.row_riview_main').eq(i).children().children(".main_edit_teacher").val());
    }
    var canadd = true;
    var updatelist = false;
    if (main_order == '' || main_name == '') {
        canadd = false;
        alert("Please fill all the information");
    }
    if (canadd) {
        if (type == 0) {
            $.ajax({
                type: "POST",
                url: "phpMod/spindles_insert.php",
                async: false,
                dataType: "json",
                data: { token: token, option_main_order: main_order, name: main_name, supervisor_main: supervisor_main, activate_yyy: year, sno_member: edit_member, sno_review: sno_review },
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
            var id = document.getElementById("main_data_id").value;
            $.ajax({
                type: "POST",
                url: "phpMod/spindles_update.php",
                async: false,
                dataType: "json",
                data: { token: token, option_main_order: main_order, name: main_name, supervisor_main: supervisor_main, sno_option_main: id, sno_member: edit_member, sno_review: sno_review },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        updatelist = true;
                        alert("編輯成功!");
                        $("#main_btnedit").hide();
                        $("#main_btnadd").show();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                }
            });
        }
        clear_main(updatelist);
    }
}

function clear_main(updatelist) {
    var plus_num = $('.row_main').length;
    var plus_review_num = $('.row_riview_main').length;
    document.getElementById("main_id").value = "";
    document.getElementById("main_name").value = "";
    show_role();
    for (var i = 0; i < plus_num; i++) {
        $('.row_main').eq(i).parents().children('.row_main').remove();
    }
    for (var i = 0; i < plus_review_num; i++) {
        $('.row_riview_main').eq(i).parents().children('.row_riview_main').remove();
    }
    if (updatelist == true) {
        var table = $('#example1').DataTable();
        table.clear().draw();
        show_main_table();
    }
}

$('#example1 tbody').on('click', 'input[name="main_checkbox"]', function (e) {
    var table = $('#example1').DataTable();
    var $row = $(this).closest('tr');
    if (this.checked) {
        $row.addClass('selected');
    }
    else {
        $row.removeClass('selected');
    }
    main_updateDataTableSelectAllCtrl(table);
});

$("#main_CheckAll").click(function () {
    var table = $('#example1').DataTable();
    if ($("#main_CheckAll").prop("checked")) {
        $("input[name='main_checkbox']").each(function () {
            $(this).prop("checked", true);
            $(this).closest('tr').addClass('selected');
        })
    }
    else {
        $("input[name='main_checkbox']").each(function () {
            $(this).prop("checked", false);
            $(this).closest('tr').removeClass('selected');
        })
    }
});

function main_updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[name="main_checkbox"]', $table);
    var $chkbox_checked = $('tbody input[name="main_checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[id="main_CheckAll"]', $table).get(0);
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

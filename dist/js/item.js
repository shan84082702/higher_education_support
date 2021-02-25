$('#example2').DataTable({
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

function item_show_main_name() {
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
                    $("#item_main_name").html(Sinner);
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}
function show_item_table() {
    var year = $('#index_type_year').val();
    var table = $('#example2').DataTable();
    $.ajax({
        type: "POST",
        url: "phpMod/suboption_get_list.php",
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
                    var sub_pk = data_array['sub_pk'];
                    var main_id = data_array['main_id'];
                    var sub_id = data_array['sub_id'];
                    var sub_name = data_array['sub_name'];
                    var supervisor = data_array['supervisor'];
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
                        sub_pk,
                        "<input type='checkbox' id='" + sub_pk + "' name='item_checkbox' />",
                        year,
                        main_id,
                        sub_id,
                        sub_name,
                        supervisor,
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

$("#item_btnadd").click(function (e) {
    item_insertORupdate(0);
});

$('#example2 tbody').on('click', 'a#editrow', function () {
    clear_item(false);
    var table = $('#example2').DataTable();
    var edit_data = table.row($(this).parents('tr')).data();
    var edit_data_id = edit_data[0];
    var year = $('#index_type_year').val();
    $.ajax({
        type: "POST",
        url: "phpMod/suboption_edit_list.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year, sub_pk: edit_data_id },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    document.getElementById("item_data_id").value = edit_data_id;
                    document.getElementById("sub_item_id").value = data_array['sub_id'];
                    document.getElementById("item_main_name").value = data_array['main_id'];
                    document.getElementById("item_name").value = data_array['sub_name'];
                    document.getElementById("item_role_name").value = data_array['role_id'];
                    $("#item_role_name").selectpicker("refresh");
                    $('#item_role_name').trigger("change");
                    document.getElementById("item_teacher").value = data_array['supervisor_id'];
                    $("#item_teacher").selectpicker("refresh");
                    var edit = data_array['edit_name'];
                    if (edit > 0) {
                        for (var j = 0; j < edit.length; j++) {
                            if (j == 0) {
                                document.getElementById("item_edit_name").value = edit[j]['role_id'];
                                $("#item_edit_name").selectpicker("refresh");
                                $('#item_edit_name').trigger("change");
                                document.getElementById("item_edit_teacher").value = edit[j]['member_id'];
                                $("#item_edit_teacher").selectpicker("refresh");
                            }
                            else {
                                $('.plus_item_btn').click();
                            }
                        }
                        for (var j = 0; j < edit.length - 1; j++) {
                            $('.row_item').eq(j).children().children(".main_edit").val(edit[j + 1]['role_id']);
                            $('#' + $('.row_item').eq(j).children().children(".main_edit").attr('id')).selectpicker("refresh");
                            $('#' + $('.row_item').eq(j).children().children(".main_edit").attr('id')).trigger("change");
                            $('.row_item').eq(j).children().children(".main_edit_teacher").val(edit[j + 1]['member_id']);
                            $('#' + $('.row_item').eq(j).children().children(".main_edit_teacher").attr('id')).selectpicker("refresh");
                        }
                    }
                    else {
                        $("#item_edit_name").selectpicker("refresh");
                        $("#item_edit_teacher").selectpicker("refresh");
                    }
                }
            }
            $("#item_btnedit").show();
            $("#item_btnadd").hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$("#item_btnedit").click(function (e) {
    var table = $('#example2').DataTable();
    item_insertORupdate(1);
});

function item_insertORupdate(type) {
    var year = $('#index_type_year').val();
    var sub_item_id = $('#sub_item_id').val();
    var item_name = $('#item_name').val();
    var item_main_id = $('#item_main_name').val();
    var supervisor = $('#item_teacher').val();
    var plus_num = $('.row_item').length;
    var edit_member = [];
    edit_member.push(document.getElementById("item_edit_teacher").value);
    for (var i = 0; i < plus_num; i++) {
        edit_member.push($('.row_item').eq(i).children().children(".main_edit_teacher").val());
    }
    var canadd = true;
    var updatelist = false;
    if (sub_item_id == '' || item_name == '') {
        canadd = false;
        alert("Please fill all the information");
    }
    if (canadd) {
        if (type == 0) {
            $.ajax({
                type: "POST",
                url: "phpMod/suboption_insert.php",
                async: false,
                dataType: "json",
                data: { token: token, option_sub_order: sub_item_id, name: item_name, supervisor_sub: supervisor, sno_option_main: item_main_id, sno_member: edit_member },
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
            var id = document.getElementById("item_data_id").value;
            $.ajax({
                type: "POST",
                url: "phpMod/suboption_update.php",
                async: false,
                dataType: "json",
                data: { token: token, option_sub_order: sub_item_id, sno_option_main: item_main_id, name: item_name, supervisor_sub: supervisor, sub_pk: id, sno_member: edit_member },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        updatelist = true;
                        alert("編輯成功!");
                        $("#item_btnedit").hide();
                        $("#item_btnadd").show();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                }
            });
        }
        clear_item(updatelist);
    }
}

function clear_item(updatelist) {
    var plus_num = $('.row_item').length;
    document.getElementById("sub_item_id").value = "";
    document.getElementById("item_name").value = "";
    show_role();
    for (var i = 0; i < plus_num; i++) {
        $('.row_item').eq(i).parents().children('.row_item').remove();
    }
    if (updatelist == true) {
        var table = $('#example2').DataTable();
        table.clear().draw();
        show_item_table();
    }
}

$('#example2 tbody').on('click', 'input[name="item_checkbox"]', function (e) {
    var table = $('#example2').DataTable();
    var $row = $(this).closest('tr');
    if (this.checked) {
        $row.addClass('selected');
    }
    else {
        $row.removeClass('selected');
    }
    item_updateDataTableSelectAllCtrl(table);
});

$("#item_CheckAll").click(function () {
    var table = $('#example2').DataTable();
    if ($("#item_CheckAll").prop("checked")) {
        $("input[name='item_checkbox']").each(function () {
            $(this).prop("checked", true);
            $(this).closest('tr').addClass('selected');
        })
    }
    else {
        $("input[name='item_checkbox']").each(function () {
            $(this).prop("checked", false);
            $(this).closest('tr').removeClass('selected');
        })
    }
});

function item_updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[name="item_checkbox"]', $table);
    var $chkbox_checked = $('tbody input[name="item_checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[id="item_CheckAll"]', $table).get(0);
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

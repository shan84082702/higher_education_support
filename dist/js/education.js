var token = GetCookie("token");
var user_name = GetCookie("user_name");
$("#username").text(user_name);
var usertitle = GetCookie("role_name");
$('#usertitle').text(usertitle);
checkToken();
var test_delete_id = [];

$("#username").text(user_name);
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
    });
});
$("#index_type_table").DataTable({
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    columnDefs: [
        {
            targets: [0],
            visible: false
        }
    ],
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
$("#index_explain_table").DataTable({
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    columnDefs: [
        {
            targets: [0],
            visible: false
        }
    ],
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
$("#education_index_table").DataTable({
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    columnDefs: [
        {
            targets: [0],
            visible: false
        }
    ],
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

$.ajax({
    type: "POST",
    url: "phpMod/menu.php",
    async: false,
    dataType: "json",
    data: { token: token, page: 4, subpage: 4 },
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
});
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
            var Sinner = "";
            for (var i = 0; i < data.out.length; i++) {
                var data_array = data.out[i];
                var database_year = data_array["year"];
                Sinner = Sinner + "<option value=" + database_year + ">" + database_year + "</option>";
            }
            $("#index_type_year").html(Sinner);
			$("#index_type_year").val(year_now);
            $("#explain_year").html(Sinner);
			$("#explain_year").val(year_now);
            $("#item_year").html(Sinner);
			$("#item_year").val(year_now);
        }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
    }
});
show_type_table();
show_explain_table();
show_item_table();

get_type_explain();

var plus_index = 1;
$(document).on("click", ".plus_btn", function (event) {
    $(this)
        .parents(".act_edit")
        .after(
            '<div class="form-class row_main" style="margin-top:0.5%;"> \
			<label for="inputEmail3" style="visibility:hidden;"> \
			<font color="red">*</font>管考欄位：</label> \
			<input type="text"  placeholder="管考欄位" style="width:60%;height:30px;margin-left:0.5%;" id="' + plus_index + '"/> \
            <input type="text" id="test_input_id' + plus_index + '"  style="width:60%;height:30px;display:none;" /> \
            <textarea rows="5" placeholder="衡量基準/計算公式(量化)/檢核方式(質化)"\
                id="formula' + plus_index + '" style="width:60%;margin-left:85px;margin-top:0.5%;" ></textarea>\
            <button type="button" class="button hollow circle row_plus_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
            <i class="fa fa-plus" aria-hidden="true"></i></button>\
			<button type="button" class="button hollow circle minus_btn" data-quantity="minus" \
			data-field="quantity" style="margin-left:1%;"><i class="fa fa-minus" aria-hidden="true"> \
			</i></button></div>'
        );
    plus_index++;
});
$(document).on("click", ".row_plus_btn", function (event) {
    $(this)
        .parents(".row_main")
        .after(
            '<div class="form-class row_main" style="margin-top:0.5%;"> \
			<label for="inputEmail3" style="visibility:hidden;"> \
			<font color="red">*</font>管考欄位：</label> \
			<input type="text"  placeholder="管考欄位" style="width:60%;height:30px;margin-left:0.5%;" id="' + plus_index + '"/> \
            <input type="text" id="test_input_id' + plus_index + '" style="width:60%;height:30px;display:none;" /> \
            <textarea rows="5" placeholder="衡量基準/計算公式(量化)/檢核方式(質化)"\
                id="formula' + plus_index + '" style="width:60%;margin-left:85px;margin-top:0.5%;" ></textarea>\
            <button type="button" class="button hollow circle row_plus_btn" data-quantity="plus" data-field="quantity" style="margin-left:1%;">\
            <i class="fa fa-plus" aria-hidden="true"></i></button>\
			<button type="button" class="button hollow circle minus_btn" data-quantity="minus" \
			data-field="quantity" style="margin-left:1%;"><i class="fa fa-minus" aria-hidden="true"> \
			</i></button></div>'
        );
    plus_index++;
});
$(document).on("click", ".minus_btn", function (event) {
	var delete_id=$(this).parents(".row_main").children().next().next().val();
	if(delete_id!=""){
		test_delete_id.push(delete_id);
	}
    $(this).parents(".row_main").remove();
});

/*-------------------------部定指標類型-------------------------*/
function show_type_table() {
    var year = $("#index_type_year").val();
    var table = $("#index_type_table").DataTable();
    $.ajax({
        type: "POST",
        url: "phpMod/indicate_get_list.php",
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
                    var sno_edu_indicators = data_array["sno_edu_indicators"];
                    var activate_yyy = data_array["activate_yyy"];
                    var name = data_array["name"];
                    var updated_at = data_array["updated_at"];
                    table.row
                        .add([
                            sno_edu_indicators,
                            "<input type='checkbox' id='" +
                            sno_edu_indicators +
                            "' name='type_checkbox'/>",
                            activate_yyy,
                            name,
                            updated_at,
                            "<a class='btn btn-warning btn-xs' id='editrow'>編輯</a>"
                        ])
                        .draw();
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    get_type_explain();
}

$(document).on("change", "#index_type_year", function (event) {
    var table = $("#index_type_table").DataTable();
    table.clear().draw();
    show_type_table();
});

$("#type_btnadd").click(function (e) {
    type_insertORupdate(0);
});

$("#index_type_table tbody").on("click", "a#editrow", function () {
    var table = $("#index_type_table").DataTable();
    var data = table.row($(this).parents("tr")).data();
    document.getElementById("type_data_id").value = data[0];
    document.getElementById("index_type_year").value = data[2];
    document.getElementById("type_name").value = data[3];
    $("#type_btnedit").show();
    $("#type_btnadd").hide();
});

$("#type_btnedit").click(function (e) {
    type_insertORupdate(1);
});

function type_insertORupdate(type) {
    var type_year = document.getElementById("index_type_year").value;
    var type_name = document.getElementById("type_name").value;
    var canadd = true;
    if (type_name == "") {
        canadd = false;
        alert("Please fill all the information");
    }
    if (canadd) {
        var table = $("#index_type_table").DataTable();
        if (type == 0) {
            table.clear().draw();
            $.ajax({
                type: "POST",
                url: "phpMod/indicate_insert.php",
                async: false,
                dataType: "json",
                data: { token: token, activate_yyy: type_year, name: type_name },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        show_type_table();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(
                        XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText
                    );
                }
            });
        } else if (type == 1) {
            var id = document.getElementById("type_data_id").value;
            table.clear().draw();
            $.ajax({
                type: "POST",
                url: "phpMod/indicate_update.php",
                async: false,
                dataType: "json",
                data: {
                    token: token,
                    activate_yyy: type_year,
                    name: type_name,
                    sno_edu_indicators: id
                },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        show_type_table();
                        alert("編輯成功!");
                        $("#type_btnedit").hide();
                        $("#type_btnadd").show();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(
                        XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText
                    );
                }
            });
        }
        document.getElementById("type_name").value = "";
    }
}

$("#index_type_table tbody").on("click", 'input[name="type_checkbox"]', function (e) {
    var table = $("#index_type_table").DataTable();
    var $row = $(this).closest("tr");
    if (this.checked) {
        $row.addClass("selected");
    } else {
        $row.removeClass("selected");
    }
    type_updateDataTableSelectAllCtrl(table);
});

$("#type_CheckAll").click(function () {
    var table = $("#index_type_table").DataTable();
    if ($("#type_CheckAll").prop("checked")) {
        $("input[name='type_checkbox']").each(function () {
            $(this).prop("checked", true);
            $(this)
                .closest("tr")
                .addClass("selected");
        });
    } else {
        $("input[name='type_checkbox']").each(function () {
            $(this).prop("checked", false);
            $(this)
                .closest("tr")
                .removeClass("selected");
        });
    }
});

$("#type_btndelete").click(function (e) {
    var delete_bool = confirm("確定要勾選資料?");
    if (delete_bool == true) {
        var table = $("#index_type_table").DataTable();
        var $table = table.table().node();
        var $chkbox_all = $('tbody input[name="type_checkbox"]', $table);
        var $chkbox_checked = $('tbody input[name="type_checkbox"]:checked', $table);
        var chkbox_select_all = $('thead input[id="type_CheckAll"]', $table).get(0);
        var Data = table.rows(".selected").data();
        var id = "0";
        for (var i = 0; i < Data.length; i++) {
            if (id == "0") id = Data[i][0];
            else id += "," + Data[i][0];
        }
        table.clear().draw();
        $.ajax({
            type: "POST",
            url: "phpMod/indicate_delete.php",
            async: false,
            dataType: "json",
            data: { token: token, sno_edu_indicators: id },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    show_type_table();
                }
                chkbox_select_all.checked = false;
                chkbox_select_all.indeterminate = false;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
    }
});

function type_updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[name="type_checkbox"]', $table);
    var $chkbox_checked = $('tbody input[name="type_checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[id="type_CheckAll"]', $table).get(0);

    if ($chkbox_checked.length === 0) {
        chkbox_select_all.checked = false;
        if ("indeterminate" in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
    } else if ($chkbox_checked.length === $chkbox_all.length) {
        chkbox_select_all.checked = true;
        if ("indeterminate" in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
    } else {
        chkbox_select_all.checked = true;
        if ("indeterminate" in chkbox_select_all) {
            chkbox_select_all.indeterminate = true;
        }
    }
}

/*-------------------------部定指標說明-------------------------*/
function show_explain_table() {
    var year = $("#explain_year").val();
    var table = $("#index_explain_table").DataTable();
    $.ajax({
        type: "POST",
        url: "phpMod/indicate2_get_list.php",
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
                    var sno_edu_indicators_sub = data_array["sno_edu_indicators_sub"];
                    var activate_yyy = data_array["activate_yyy"];
                    var name = data_array["name"];
                    var updated_at = data_array["updated_at"];
                    table.row
                        .add([
                            sno_edu_indicators_sub,
                            "<input type='checkbox' id='" +
                            sno_edu_indicators_sub +
                            "' name='explain_checkbox'/>",
                            activate_yyy,
                            name,
                            updated_at,
                            "<a class='btn btn-warning btn-xs' id='editrow'>編輯</a>"
                        ])
                        .draw();
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    get_type_explain();
}

$(document).on("change", "#explain_year", function (event) {
    var table = $("#index_explain_table").DataTable();
    table.clear().draw();
    show_explain_table();
});

$("#explain_btnadd").click(function (e) {
    explain_insertORupdate(0);
});

$("#index_explain_table tbody").on("click", "a#editrow", function () {
    var table = $("#index_explain_table").DataTable();
    var data = table.row($(this).parents("tr")).data();
    document.getElementById("explain_data_id").value = data[0];
    document.getElementById("explain_year").value = data[2];
    document.getElementById("explain_name").value = data[3];
    $("#explain_btnedit").show();
    $("#explain_btnadd").hide();
});

$("#explain_btnedit").click(function (e) {
    explain_insertORupdate(1);
});

function explain_insertORupdate(type) {
    var explain_year = document.getElementById("explain_year").value;
    var explain_name = document.getElementById("explain_name").value;
    var canadd = true;
    if (explain_name == "") {
        canadd = false;
        alert("Please fill all the information");
    }
    if (canadd) {
        var table = $("#index_explain_table").DataTable();
        if (type == 0) {
            table.clear().draw();
            $.ajax({
                type: "POST",
                url: "phpMod/indicate2_insert.php",
                async: false,
                dataType: "json",
                data: { token: token, activate_yyy: explain_year, name: explain_name },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        show_explain_table();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(
                        XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText
                    );
                }
            });
        } else if (type == 1) {
            var id = document.getElementById("explain_data_id").value;
            table.clear().draw();
            $.ajax({
                type: "POST",
                url: "phpMod/indicate2_update.php",
                async: false,
                dataType: "json",
                data: {
                    token: token,
                    activate_yyy: explain_year,
                    name: explain_name,
                    sno_edu_indicators_sub: id
                },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        show_explain_table();
                        alert("編輯成功!");
                        $("#explain_btnedit").hide();
                        $("#explain_btnadd").show();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(
                        XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText
                    );
                }
            });
        }
        document.getElementById("explain_name").value = "";
    }
}

$("#index_explain_table tbody").on("click", 'input[name="explain_checkbox"]', function (e) {
    var table = $("#index_explain_table").DataTable();
    var $row = $(this).closest("tr");
    if (this.checked) {
        $row.addClass("selected");
    } else {
        $row.removeClass("selected");
    }
    explain_updateDataTableSelectAllCtrl(table);
});

$("#explain_CheckAll").click(function () {
    var table = $("#index_explain_table").DataTable();
    if ($("#explain_CheckAll").prop("checked")) {
        $("input[name='explain_checkbox']").each(function () {
            $(this).prop("checked", true);
            $(this)
                .closest("tr")
                .addClass("selected");
        });
    } else {
        $("input[name='explain_checkbox']").each(function () {
            $(this).prop("checked", false);
            $(this)
                .closest("tr")
                .removeClass("selected");
        });
    }
});

$("#explain_btndelete").click(function (e) {
    var delete_bool = confirm("確定要勾選資料?");
    if (delete_bool == true) {
        var table = $("#index_explain_table").DataTable();
        var $table = table.table().node();
        var $chkbox_all = $('tbody input[name="explain_checkbox"]', $table);
        var $chkbox_checked = $('tbody input[name="explain_checkbox"]:checked', $table);
        var chkbox_select_all = $('thead input[id="explain_CheckAll"]', $table).get(0);
        var Data = table.rows(".selected").data();
        var id = "0";
        for (var i = 0; i < Data.length; i++) {
            if (id == "0") id = Data[i][0];
            else id += "," + Data[i][0];
        }
        table.clear().draw();
        $.ajax({
            type: "POST",
            url: "phpMod/indicate2_delete.php",
            async: false,
            dataType: "json",
            data: { token: token, sno_edu_indicators_sub: id },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
                    show_explain_table();
                }
                chkbox_select_all.checked = false;
                chkbox_select_all.indeterminate = false;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        });
    }
});

function explain_updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[name="explain_checkbox"]', $table);
    var $chkbox_checked = $('tbody input[name="explain_checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[id="explain_CheckAll"]', $table).get(0);
    if ($chkbox_checked.length === 0) {
        chkbox_select_all.checked = false;
        if ("indeterminate" in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
    } else if ($chkbox_checked.length === $chkbox_all.length) {
        chkbox_select_all.checked = true;
        if ("indeterminate" in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
    } else {
        chkbox_select_all.checked = true;
        if ("indeterminate" in chkbox_select_all) {
            chkbox_select_all.indeterminate = true;
        }
    }
}

/*-------------------------部定指標項目-------------------------*/
function get_type_explain() {
    var year = $("#item_year").val();
    var type_option = "";
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
            var Sinner = "";
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out1.length; i++) {
                    var data_array = data.out1[i];
                    var sno_edu_indicators = data_array["sno_edu_indicators"];
                    var name = data_array["name"];
                    Sinner = Sinner + "<option value=" + sno_edu_indicators + ">" + name + "</option>";
                }
                $("#item_type").html(Sinner);
            }
            //$('#item_type').trigger("change");
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    //var edu_indicators = $('#item_type').val();
    $.ajax({
        type: "POST",
        url: "phpMod/indicate2_get_list.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year},
		success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                Sinner="";
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var sno_edu_indicators_sub = data_array["sno_edu_indicators_sub"];
                    var name = data_array["name"];
					Sinner = Sinner + '<option value=' + sno_edu_indicators_sub + '>' + name + '</option>';
                }
				 $("#item_explain").html(Sinner);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}
/*
$("#item_type").on("change",function(event){
    var year = $("#index_type_year").val();
    var edu_indicators = $('#item_type').val();
    $.ajax({
        type: "POST",
        url: "phpMod/strategy_get_indicators_direction.php",
        async: false,
        dataType: "json",
        data: { token: token, year: year,sno_edu_indicators:edu_indicators },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var Sinner="";
                if (checkMsg(data.msg)) {
                    for (var i = 0; i < data.out.length; i++) {
                        var data_array = data.out[i];
                        var sno_edu_indicators_sub = data_array['sno_edu_indicators_sub'];
                        var name = data_array['name'];
                        Sinner = Sinner + '<option value=' + sno_edu_indicators_sub + '>' + name + '</option>';
                   }
                    $("#item_explain").html(Sinner);
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });


});*/

function show_item_table() {
    var year = $("#item_year").val();
    var table = $("#education_index_table").DataTable();
    $.ajax({
        type: "POST",
        url: "phpMod/indicate3_get_list.php",
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
                    var sno_edu_indicators_detail = data_array["sno_edu_indicators_detail"];
                    var activate_yyy = data_array["activate_yyy"];
                    var edu_name = data_array["edu_name"];
                    var edu_sub_name = data_array["edu_sub_name"];
                    var edu_detail = data_array["edu_detail"];
                    var rule = data_array["edu_indicators_detail_rule"]; //公式
                    var str = data_array["str"]; //管考欄位
                    table.row
                        .add([
                            sno_edu_indicators_detail,
                            "<input type='checkbox' id='" + sno_edu_indicators_detail +"' name='checkbox'/>",
                            activate_yyy,
                            edu_name,
                            edu_sub_name,
                            edu_detail,
                            str,
                            rule,
                            "<a class='btn btn-warning btn-xs' id='editrow'>編輯</a>"
                        ])
                        .draw();
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
}

$(document).on("change", "#item_year", function (event) {
    var table = $("#education_index_table").DataTable();
    table.clear().draw();
    show_item_table();
    get_type_explain();
});

$("#btnadd").click(function (e) {
    insertORupdate(0);
});

$("#education_index_table tbody").on("click", "a#editrow", function () {
	clear(false);
    var table = $("#education_index_table").DataTable();
    var data = table.row($(this).parents("tr")).data();
    var edit_data_id = data[0];
    $.ajax({
        type: "POST",
        url: "phpMod/indicate3_get_edit.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_edu_indicators_detail: edit_data_id },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
					console.log(data_array);
                    document.getElementById("item_year").value = data_array["activate_yyy"];
                    document.getElementById("item_type").value = data_array["edu_id"];
                    document.getElementById("item_explain").value = data_array["edu_sub_id"];
                    document.getElementById("education_index").value = data_array["edu_detail"];
                    document.getElementById("data_id").value = edit_data_id;
                    var test_name = data_array["name"];
                    for (var j = 0; j < test_name.length; j++) {
                        if (j == 0) {
                            document.getElementById("test_input").value = test_name[j]["name"];
                            document.getElementById("formula").value = test_name[j]["edu_indicators_detail_rule"];
							document.getElementById("test_input_id").value = test_name[j]["sno_edu_management"];
                        } else {
                            $(".plus_btn").click();
                        }
                    }
                    for (var j = 0; j < test_name.length - 1; j++) {
                        $(".row_main").eq(j).children().next().val(test_name[j + 1]["name"]);
						$(".row_main").eq(j).children().next().next().val(test_name[j + 1]["sno_edu_management"]);
                        $(".row_main").eq(j).children().next().next().next().val(test_name[j + 1]["edu_indicators_detail_rule"]);
                    }
                }
            }
            $("#btnedit").show();
            $("#btnadd").hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$("#btnedit").click(function (e) {
    insertORupdate(1);
});

function insertORupdate(type) {
    var year = $("#item_year").val();
    var item_type = document.getElementById("item_type").value;
    var item_explain = document.getElementById("item_explain").value;
    var education_index = document.getElementById("education_index").value;
    var formula = document.getElementById("formula").value;
    var plus_num = $(".row_main").length;
    var canadd = true;
    var updatelist = false;
    if (education_index == "" || formula == "" || document.getElementById("test_input").value == "") {
        canadd = false;
    }
    for (var i = 0; i < plus_num; i++) {
        if ($(".row_main").eq(i).children().next().val() == "" || $(".row_main").eq(i).children().next().next().next().val() == "" )
            canadd = false;
    }
    if (canadd == false) alert("Please fill all the information");
    else {
		if(type==0){
            var test_input_name = [];
            var test_input_formula = [];
            test_input_name.push(document.getElementById("test_input").value);
            test_input_formula.push(document.getElementById("formula").value);
			for (var i = 0; i < plus_num; i++) {
                test_input_name.push($(".row_main").eq(i).children().next().val());
                test_input_formula.push($(".row_main").eq(i).children().next().next().next().val());
            }
		}
		else if(type==1){
            var update_new_input_name = [];
            var update_new_input_formula = [];
            var update_old_input_name = [];
            var update_old_input_formula = [];
			var update_old_input_id = [];
            update_old_input_name.push(document.getElementById("test_input").value);
            update_old_input_formula.push(document.getElementById("formula").value);
			update_old_input_id.push(document.getElementById("test_input_id").value);
			for (var i = 0; i < plus_num; i++) {
				var id=$(".row_main").eq(i).children().next().next().val();
				if(id==""){
                    update_new_input_name.push($(".row_main").eq(i).children().next().val());
                    update_new_input_formula.push($(".row_main").eq(i).children().next().next().next().val());
                }
                else{
                    update_old_input_name.push($(".row_main").eq(i).children().next().val());
                    update_old_input_formula.push($(".row_main").eq(i).children().next().next().next().val());
					update_old_input_id.push(id);
				}
            }
		}
        
    }
    if (canadd) {
        if (type == 0) {
            $.ajax({
                type: "POST",
                url: "phpMod/indicate3_insert.php",
                async: false,
                dataType: "json",
                data: {
                    token: token,
                    activate_yyy: year,
                    name: education_index,
                    edu_indicators_detail_rule: formula,
                    sno_edu_indicators: item_type,
                    edu_indicators_sub: item_explain,
                    names: test_input_name,
                    edu_indicators_detail_rule: test_input_formula
                },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        updatelist = true;
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(
                        XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText
                    );
                }
            });
        } else if (type == 1) {
            var id = document.getElementById("data_id").value;
            $.ajax({
                type: "POST",
                url: "phpMod/indicate3_update.php",
                async: false,
                dataType: "json",
                data: {
                    token: token,
                    activate_yyy: year,
                    name: education_index,
                    edu_indicators_detail_rule: formula,
                    sno_edu_indicators: item_type,
                    edu_indicators_sub: item_explain,
                    sno_edu_indicators_detail: id,
                    insert_names: update_new_input_name,
                    insert_edu_indicators_detail_rule: update_new_input_formula,
					edit_id: update_old_input_id,
                    edit_names: update_old_input_name,
                    edit_edu_indicators_detail_rule: update_old_input_formula,
					delete_id: test_delete_id
                },
                success: function (data) {
                    if (!checkMsg(data.msg)) {
                        delete_all_cookie();
                    }
                    if (checkMsg(data.msg)) {
                        updatelist = true;
                        alert("編輯成功!");
                        $("#btnedit").hide();
                        $("#btnadd").show();
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(
                        XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText
                    );
                }
            });
        }
        clear(updatelist);
    }
}

function clear(updatelist) {
    var plus_num = $(".row_main").length;
    get_type_explain();
    document.getElementById("education_index").value = "";
    document.getElementById("formula").value = "";
    document.getElementById("data_id").value = "";
    document.getElementById("test_input").value = "";
    for (var i = 0; i < plus_num; i++) {
        $(".row_main")
            .eq(i)
            .parents()
            .children(".row_main")
            .remove();
    }
    if (updatelist == true) {
        var table = $("#education_index_table").DataTable();
        table.clear().draw();
        show_item_table();
    }
}

$("#education_index_table tbody").on("click", 'input[name="checkbox"]', function (e) {
    var table = $("#education_index_table").DataTable();
    var $row = $(this).closest("tr");
    if (this.checked) {
        $row.addClass("selected");
    } else {
        $row.removeClass("selected");
    }
    updateDataTableSelectAllCtrl(table);
});

$("#CheckAll").click(function () {
    var table = $("#education_index_table").DataTable();
    if ($("#CheckAll").prop("checked")) {
        $("input[name='checkbox']").each(function () {
            $(this).prop("checked", true);
            $(this)
                .closest("tr")
                .addClass("selected");
        });
    } else {
        $("input[name='checkbox']").each(function () {
            $(this).prop("checked", false);
            $(this)
                .closest("tr")
                .removeClass("selected");
        });
    }
});

$("#btndelete").click(function (e) {
    var delete_bool = confirm("確定要勾選資料?");
    if (delete_bool == true) {
        var table = $("#education_index_table").DataTable();
        var $table = table.table().node();
        var $chkbox_all = $('tbody input[name="checkbox"]', $table);
        var $chkbox_checked = $('tbody input[name="checkbox"]:checked', $table);
        var chkbox_select_all = $('thead input[id="CheckAll"]', $table).get(0);
        var Data = table.rows(".selected").data();
        var id = "0";
        for (var i = 0; i < Data.length; i++) {
            if (id == "0") id = Data[i][0];
            else id += "," + Data[i][0];
        }
        table.clear().draw();
        $.ajax({
            type: "POST",
            url: "phpMod/indicate3_delete.php",
            async: false,
            dataType: "json",
            data: { token: token, sno_edu_indicators_detail: id },
            success: function (data) {
                if (!checkMsg(data.msg)) {
                    delete_all_cookie();
                }
                if (checkMsg(data.msg)) {
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
});

function updateDataTableSelectAllCtrl(table) {
    var $table = table.table().node();
    var $chkbox_all = $('tbody input[name="checkbox"]', $table);
    var $chkbox_checked = $('tbody input[name="checkbox"]:checked', $table);
    var chkbox_select_all = $('thead input[id="CheckAll"]', $table).get(0);

    if ($chkbox_checked.length === 0) {
        chkbox_select_all.checked = false;
        if ("indeterminate" in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
    } else if ($chkbox_checked.length === $chkbox_all.length) {
        chkbox_select_all.checked = true;
        if ("indeterminate" in chkbox_select_all) {
            chkbox_select_all.indeterminate = false;
        }
    } else {
        chkbox_select_all.checked = true;
        if ("indeterminate" in chkbox_select_all) {
            chkbox_select_all.indeterminate = true;
        }
    }
}

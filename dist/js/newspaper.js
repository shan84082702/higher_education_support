var token = GetCookie("token");
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
    });
});
function newspaper_init() {
    $.ajax({
        type: "POST",
        url: "phpMod/menu.php",
        async: false, ///非同步執行
        dataType: "json",
        data: { token: token, page: 8, subpage: 0 },
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

    $("#activity_table").DataTable({
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
                $("#index_year").html(Sinner);
                $("#index_year").val(year_now);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    $("#index_year").trigger("change");
}

$(document).on("change", "#index_year", function (event) {
    $.ajax({
        type: "POST",
        url: "phpMod/bbs_get_spindles.php",
        async: false,
        dataType: "json",
        data: { token: token, year: $("#index_year").val() },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                var Sinner = "";
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    Sinner = Sinner + "<option value=" + data_array["sno_option_main"] + ">" + data_array["name"] + "</option>";
                }
                $("#index_main").html(Sinner);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});

$("#search").click(function (e) {
    var area = document.getElementById("table_area");
    area.style.display = "block";
    show_table();
});

function show_table() {
    var table = $("#activity_table").DataTable();
    table.clear().draw();
    $.ajax({
        type: "POST",
        url: "phpMod/bbs_get_list.php",
        async: false,
        dataType: "json",
        data: { token: token, spindle: $("#index_main").val() },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    var fill_result=0;
                    var pushed_state=0;
                    var btn="";
                    if(data_array["isfilledresult"]==0){
                        fill_result="否"
                    }
                    else if(data_array["isfilledresult"]==1){
                        fill_result="是"
                    }
                    if(data_array["pushed"]==0){
                        pushed_state="未推播"
                        btn="<a class='btn btn-warning btn-xs' id='push_btn'>推播</a>"
                    }
                    else if(data_array["pushed"]==1){
                        pushed_state="已推播"
                        btn="<a class='btn btn-warning btn-xs' id='push_btn'>取消推播</a>"
                    }
                    table.row
                        .add([
                            data_array["sno_option_projects"],
                            data_array["name"],
                            data_array["updated_at"],
                            data_array["activate_date"],
                            fill_result,
                            pushed_state,
                            btn
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

$("#activity_table tbody").on("click", "a#push_btn", function () {
    var table = $("#activity_table").DataTable();
    var data = table.row($(this).parents("tr")).data();
    var sno_option_projects=data[0];
    var pushed=0;
    if(data[5]=="未推播"){
        pushed=1;
    }
    else if(data[5]=="已推播"){
        pushed=0;
    }
    $.ajax({
        type: "POST",
        url: "phpMod/bbs_update.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_option_projects:sno_option_projects, pushed:pushed},
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                show_table();
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});
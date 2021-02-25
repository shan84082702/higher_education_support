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
//-----------------index_review.js---------------------
function index_review_init() {
    $.ajax({
        type: "POST",
        url: "phpMod/menu.php",
        async: false, ///非同步執行
        dataType: "json",
        data: { token: token, page: 7, subpage: 0 },
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



    $("#not_check").DataTable({
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
    $("#done_check").DataTable({
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
            },
            {
                targets: [1],
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
    $("#record_table").DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
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

    var y = new Date();
    var year_prev = y.getFullYear() - 1912;
    var year_now = y.getFullYear() - 1911;
    var year_next = y.getFullYear() - 1910;
    var Sinner = "";
    Sinner = "<option value=" + year_prev + ">" + year_prev + "</option>\
            <option value=" + year_now + ">" + year_now + "</option>\
            <option value=" + year_next + ">" + year_next + "</option>";
    $("#index_year").html(Sinner);
    $("#index_year").val(year_now);
    /*
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
    });*/
    $("#index_year").trigger("change");
}

$(document).on("change", "#index_year", function (event) {
    $.ajax({
        type: "POST",
        url: "phpMod/review_spindles.php",
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
    $("#index_main").trigger("change");
});

$(document).on("change", "#index_main", function (event) {
    show_table();
});

function show_table() {
    var table = $("#not_check").DataTable();
    var table2 = $("#done_check").DataTable();
    table.clear().draw();
    table2.clear().draw();
    $.ajax({
        type: "POST",
        url: "phpMod/review_get_list.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_option_main: $("#index_main").val() },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                for (var i = 0; i < data.out1.length; i++) {
                    var data_array = data.out1[i];
                    table.row
                        .add([
                            data_array["sno_option_projects"],
                            data_array["order"],
                            data_array["name"],
                            data_array["updated_at"],
                            data_array["activate_date"],
                            data_array["isfilledresult"],
                            "<a class='btn btn-success btn-xs' id='detail'>詳細資料</a>"
                        ])
                        .draw();
                }
                for (var i = 0; i < data.out2.length; i++) {
                    var data_array = data.out2[i];
                    table2.row
                        .add([
                            data_array["sno_option_projects"],
                            data_array["sno_reviewed"],
                            data_array["order"],
                            data_array["name"],
                            data_array["updated_at"],
                            data_array["activate_date"],
                            data_array["isfilledresult"],
                            data_array["need_reply"],
                            "<a class='btn btn-warning btn-xs' id='record' data-toggle='modal' data-target='#modal-record'>審查紀錄</a>"
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

$("#not_check tbody").on("click", "a#detail", function () {
    var domain = window.location.hostname;
    var table = $("#not_check").DataTable();
    var data = table.row($(this).parents("tr")).data();
    SetCookie("pid", data[0], 1, domain);
    document.location.href = 'index_review2.html';
});

$("#done_check tbody").on("click", "a#record", function () {
    var domain = window.location.hostname;
    var table = $("#done_check").DataTable();
    var table3 = $("#record_table").DataTable();
    table3.clear().draw();
    var data = table.row($(this).parents("tr")).data();
    SetCookie("pid", data[0], 1, domain);
    SetCookie("review_id",data[1],1,domain);
    document.getElementById("project_name").innerHTML = "<a href='index_review2.html'>"+data[2]+"</a>";
    $.ajax({
        type: "POST",
        url: "phpMod/review_record_get_list.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_reviewed:data[1] },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                console.log(data.out);
                for (var i = 0; i < data.out.length; i++) {
                    var data_array = data.out[i];
                    table3.row
                        .add([
                            data_array["updated_at"],
                            data_array["name"],
                            data_array["message"],
                        ])
                        .draw();
                }
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
    //document.location.href = 'index_review2.html';
});



//index_review2.html
$("#btnsendreview").click(function (e) {
    if ($('#review_type').val() == "null") {
        alert("請選擇一選項");
        eval("document.getElementById('review_type').focus()");
        return false;
    }
    $.ajax({
        type: "POST",
        url: "phpMod/review_send.php",
        async: false,
        dataType: "json",
        data: { token: token, sno_option_projects: GetCookie("pid"), need_reply: $('#review_type').val(), message: $('#review_text').val() },
        success: function (data) {
            if (!checkMsg(data.msg)) {
                delete_all_cookie();
            }
            if (checkMsg(data.msg)) {
                alert("審查成功!");
                document.location.href = 'index_review.html';
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    });
});


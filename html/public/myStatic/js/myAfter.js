const dictOfCheckpointsNumbers = {//因为需要转置所以不能出现重复值，关卡调整只需调整此处、数据库和模板文件结构
    "1_1": "intro-platform",
    "1_2": "intro-xss",
    "2_1": "basis-reflect",
    "2_2": "basis-stored",
    "2_3": "basis-dom",
    "3_1": "scan-manual",
    "3_2": "scan-auto",
    "4_1": "repair-io",
    "4_2": "repair-httpOnly",
    "5_1": "exercise-choose",
    "5_2": "exercise-errorset",
}
var dictOfCheckpoints = {}
for (let k in dictOfCheckpointsNumbers) {//reverse dict
    let value = dictOfCheckpointsNumbers[k];
    dictOfCheckpoints[value] = k;
}


let totalTime;
let sec;
let min;
let hour;

function updateTime(addTime=0){
    totalTime+=addTime;
    sec=parseInt(totalTime%60);
    min=parseInt(totalTime/60)%60;
    hour=parseInt(totalTime/60/60);
    $(".time-hour").text(hour);
    $(".time-min").text(min);
    $(".time-sec").text(sec);
}
function updateTimeRemote(){
    $.get({
        url:"/time/addTime",
        async:true,
        success:function(data){
            if(!data){
                toastr.warning("学习时长数据远程更新失败");
                reloadPage();
            }
    }});
}
//初始化toaster : success,warning,error
var toaster = $('#toaster');
if (toaster.length != 0) {
    if (document.dir != "rtl") {
        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: false,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
    } else {
        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: false,
            progressBar: true,
            positionClass: "toast-top-left",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
    }
}
//Modal funtions
function modalUpdate() {
    var status;
    var userEmail = $("#updateEmail").val();
    $.post({
        url: "/user/update",
        async: false,
        data: {
            email: $("#updateEmail").val(),
            password: $("#updatePassword").val(),
        },
        success: function (data) {
            if (data["status"]) {
                $("#modalUpdateMessage").slideUp();
                status = true;
            } else {
                $("#modalUpdateMessage").slideUp();
                $("#modalUpdateMessage").text(data['message']);
                $("#modalUpdateMessage").removeClass()
                $("#modalUpdateMessage").addClass("alert alert-danger");
                $("#modalUpdateMessage").slideDown();
                status = false;
            }
        }
    });
    if (status) {
        if (userEmail != "") {
            $(".user-email").text(userEmail);
        }
        toastr.success("您的注册信息已更改!", "操作成功!");
        $(".close").click();
    }
    return false;
}

//cuReUrl:当前的相对路径 cuReUrlOnlyAlpha:当前的相对路径无/
var url = document.location.toString();
var arrUrl = url.split("//");
var start = arrUrl[1].indexOf("/");
var cuReUrl = arrUrl[1].substring(start);//stop省略，截取从start开始到结尾的所有字符
if (cuReUrl.indexOf("?") != -1) {
    cuReUrl = cuReUrl.split("?")[0];
}
var cuReUrlOnlyAlpha = cuReUrl.substring(1);
//cuCpNum:当前关卡数字
var cuCpNum = dictOfCheckpoints[cuReUrlOnlyAlpha]

var totalCp1 = 6;
var maxCp = Array(totalCp1);
function initial() {
    url = document.location.toString();
    arrUrl = url.split("//");
    start = arrUrl[1].indexOf("/");
    cuReUrl = arrUrl[1].substring(start);//stop省略，截取从start开始到结尾的所有字符
    if (cuReUrl.indexOf("?") != -1) {
        cuReUrl = cuReUrl.split("?")[0];
    }
    cuReUrlOnlyAlpha = cuReUrl.substring(1);
    //cuCpNum:当前关卡数字
    cuCpNum = dictOfCheckpoints[cuReUrlOnlyAlpha]
    
    totalCp1 = 6;
    maxCp = Array(totalCp1);

    $(".expand>.sidenav-item-link").attr("disabled",true).css("pointer-events","none");  
    if(getCookie("first")){
        
    }
    //侧边栏高亮
    var passedCheckpointCount = 0;
    var totalCheckpointCount = 0;
    var sidebarMenu = $("#sidebar-menu");
    var cuTotalCp = Array(totalCp1);
    for (let i = 0; i < maxCp.length; i++) {
        maxCp[i] = 0;
        cuTotalCp[i] = 0;
    }
    //已学栏目高亮
    $.post({
        url: "/progress/getCheckpointData",
        async: true,
        success: function (data) {
            var learnt = [], finished = []; // 学会的栏目，学会的大类
            if (data['status'] == true) {
                var cp1, cp2;
                data = data['data'];
                for (checkpoint of Object.keys(data)) {//项 checkpoint 值 data[checkpoint]
                    cp1 = parseInt(checkpoint.substring(0, checkpoint.indexOf("_")));
                    cp2 = parseInt(checkpoint.substring(checkpoint.indexOf("_") + 1));
                    if (data[checkpoint]) {
                        passedCheckpointCount++;
                        learnt.push(sidebarMenu.children().eq(cp1 - 1).find(".sub-menu").children().eq(cp2 - 1).children()[0].text.trim());
                        if (maxCp[cp1] < cp2) {
                            cuTotalCp[cp1]++;
                        }
                    }
                    maxCp[cp1] = cp2;
                    totalCheckpointCount++;
                }
                for (let i = 1; i < maxCp.length; i++) {
                    if (maxCp[i] == cuTotalCp[i]) {
                        finished.push(sidebarMenu.children().eq(i - 1).children()[0].text.trim());
                    }
                }
                $(".progress-innerText").text(Math.round(passedCheckpointCount / totalCheckpointCount * 100) + "%");
                $("#progress").css("width", passedCheckpointCount / totalCheckpointCount * 100 + "%");
                if(passedCheckpointCount==0){
                    $(".progress-innerText").text("0%");
                    $("#progress").css("width", "3em");
                }
            } else {
                console.log(data['data'])
            }
            var cuItem = $("#sidebar-menu").find("[href='" + cuReUrl + "']");
            // 设置栏目和大类高亮
            var sm = sidebarMenu.children();
            for (var i = 0; i < sm.length; ++i) {
                outer = sm.eq(i).find(".sub-menu").children();
                for (var j = 0; j < outer.length; ++j) {
                    var inner = outer.eq(j).children();
                    if (inner[0].text.trim() === cuItem[0].text.trim()) inner.css("color", "gold");
                    else if (learnt.indexOf(inner[0].text.trim()) >= 0) inner.css("color", "#4c84ff");
                    else inner.css("color", "#b7c0cd");
                }
                outer = sm.eq(i).children();
                if (finished.indexOf(outer[0].text.trim()) >= 0) outer.css("color", "#4c84ff");
                else outer.css("color", "#a6aab4");
            }
            //提交按钮设置
            var submitBtn = $("#setCpStatusBtn")
            if (submitBtn) {
                if (cuCpNum.indexOf("5_") >= 0) {
                    submitBtn.css("display", "none");
                }
                else{
                    submitBtn.css("display", "inline-block");
                    submitBtn.removeClass("btn-secondary");
                    if (data[dictOfCheckpoints[cuReUrlOnlyAlpha]]) {
                        submitBtn.addClass("btn-danger");
                        submitBtn.removeClass("btn-primary");
                        submitBtn.text("我要重学");
                    } else {
                        submitBtn.addClass("btn-primary");
                        submitBtn.removeClass("btn-danger");
                        submitBtn.text("我学会了");
                    }
                }
            }
        }
    });
    if (cuReUrl == "/basis-stored") {
        $("#2_2_virtualenv_unstarted").hide();
        $("#2_2_virtualenv_started").hide();
        if (getCookie("virtualEnvStarted") == "") {
            $("#2_2_virtualenv_unstarted").show();
        } else {
            $("#2_2_virtualenv_started").show();
        }
    }
    if(getCookie("first")=="true"){
        clearCookie("first");
        toastr.success("欢迎来到跨站脚本教学辅助平台！", "登陆成功！");
    }
    Prism.highlightAll();
}
//页面加载后立刻运行
$(function () {
    initial();
    $.post({
        url: "/time/getTotalTime",
        async: true,
        success: function (data) {
            if(data['status']==true){
                totalTime=data['data'];
                updateTime();
            }else{
                console.error("获取学习时间信息失败");
            }
        }
    })
    setInterval("updateTime(1);",1*1000);//单位毫秒
    setInterval("updateTimeRemote();",5*1000);//单位毫秒
    //end 页面加载后立刻运行
});
//Button functions
function setCpStatus() {
    var status;
    var submitBtn = $("#setCpStatusBtn")
    if ($("#setCpStatusBtn").text() === "我要重学") {
        status = "false";
    } else if ($("#setCpStatusBtn").text() === "我学会了") {
        status = "true";
    }
    var dataToSend = {}
    dataToSend[cuCpNum] = status
    $.post({
        url: "/progress/setCheckpoint",
        async: true,
        data: dataToSend,
        success: function (data) {
            if (data["status"] == true) {
                if (status === "true") {
                    submitBtn.removeClass();
                    submitBtn.addClass("btn btn-square btn-danger ");
                    submitBtn.text("我要重学");
                    showModal("恭喜你成功掌握了本节内容，将自动为您跳转到下一节");
                    if (parseInt(dictOfCheckpoints[cuReUrlOnlyAlpha].substring(dictOfCheckpoints[cuReUrlOnlyAlpha].indexOf("_") + 1)) + 1 <= maxCp[dictOfCheckpoints[cuReUrlOnlyAlpha].substring(0, dictOfCheckpoints[cuReUrlOnlyAlpha].indexOf("_"))]) {
                        toPage("/" + dictOfCheckpointsNumbers[dictOfCheckpoints[cuReUrlOnlyAlpha].substring(0, dictOfCheckpoints[cuReUrlOnlyAlpha].indexOf("_") + 1) + (parseInt(dictOfCheckpoints[cuReUrlOnlyAlpha].substring(dictOfCheckpoints[cuReUrlOnlyAlpha].indexOf("_") + 1)) + 1)]);
                        return;
                    } else {
                        if(dictOfCheckpointsNumbers[parseInt(dictOfCheckpoints[cuReUrlOnlyAlpha].substring(0, dictOfCheckpoints[cuReUrlOnlyAlpha].indexOf("_") ))+1+'_1']==undefined){
                            reloadPage();//规避最后一节的报错
                            return;
                        }
                        toPage("/" + dictOfCheckpointsNumbers[(parseInt(dictOfCheckpoints[0, cuReUrlOnlyAlpha].substring(0, dictOfCheckpoints[cuReUrlOnlyAlpha].indexOf("_"))) + 1) + '_1']);
                        return;
                    }

                } else if (status === "false") {
                    showModal("本节学习记录已重置");
                    submitBtn.removeClass();
                    submitBtn.addClass("btn btn-square btn-primary ");
                    submitBtn.text("我学会了");
                    reloadPage();
                }
            } else {
                if (data['data'] === 'uid in session not found') {
                    showModal("请先登录！");
                } else {
                    showModal("提交失败！错误信息：" + data['message']);
                }
            }
        }
    });
}

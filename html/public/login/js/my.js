function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires + ";" + "path=/";
}

$('.text').focus(function() {
    $(this).parent().addClass('border-highlight');
});
$('.text').blur(function() {
    $(this).parent().removeClass('border-highlight');
});

function Login() {
    $("#login-form .submit").attr("disabled",true).css("pointer-events","none").text("登录中,请稍候...");
    var status;
    $.post({
        url: "/user/login",
        async: true,
        data: {
            user: $("#login-form input[name='username']").val(),
            password: $("#login-form input[name='password']").val(),
        },
        success: function (data) {
            if (data["status"]) {
                setCookie('username', $("#login-form input[name='username']").val());
                setCookie('first', true);
                if(data["message"]=="Admin"){
                    window.location.href="/progressManage";
                }
                else {
                    window.location.href="/";
                }
                status = true;
            } else {
                alert("登录失败:"+data['message']);
                $("#login-form .submit").removeAttr("disabled").css("pointer-events","").text("登录");
                status = false;
            }
        }
    });
    return status;
}
function Register() {
    $("#register-form .submit").attr("disabled",true).css("pointer-events","none").text("注册中,请稍候...");
    $.post({
        url: "/user/register",
        async: true,
        data: {
            username: $("#register-form input[name='username']").val(),
            realName: $("#register-form input[name='realName']").val(),
            password: $("#register-form input[name='password']").val(),
        },
        success: function (data) {
            if (data["status"]) {
                alert("注册成功!请登录.");
                window.location.href="/login/";
                status = true;
            } else {
                alert("注册失败:"+data['message']);
                $("#register-form .submit").removeAttr("disabled").css("pointer-events","").text("注册");
                status = false;
            }
        }
    });
    return false;
}
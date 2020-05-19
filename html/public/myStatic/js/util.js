//Cookie函数
function getCookie(name) {
  var cookies = document.cookie;
  var list = cookies.split("; ");          // 解析出名/值对列表
  for (var i = 0; i < list.length; i++) {
      var arr = list[i].split("=");          // 解析出名和值
      if (arr[0] == name)
          return decodeURIComponent(arr[1]);   // 对cookie值解码
  }
  return "";
}
function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + "; " + expires;
}
function clearCookie(name) {
  setCookie(name, "", -1);
}

function logout() {
  $.get({url:'/user/logout',async:false});
  clearCookie("PHPSESSID");
  window.location.href = "/"
}

function reloadPage() {
  $.pjax.reload("#pjax-container", {fragment:'#pjax-container', timeout:8000});
}

function toPage(href) {
  //$.pjax({url, container: "#pjax-container", options: {fragment:'#pjax-container', timeout:8000}});
  var a = $("<a>", { href });
  $("#pjax-container").append(a);
  a.click();
}

function showModal(content) {
  $("#myModalContent").text(content);
  $("#showModal").click();
}

function showModalHTML(content) {
  $("#myModalContent").html(content);
  $("#showModal").click();
}

function showModalHTMLSelf(content, title, button) {
  $("#myModalContent").html(content);
  $("#myModalTitle").text(title);
  $("#myModalButton").text(button);
  $("#showModal").click();
}

function axiosPostFormdata(u, o) {
  let fd = new FormData();
  for (let key of Object.keys(o)) {
    fd.append(key, o[key]);
  }
  return axios.post(u, fd, { headers:{ "Content-Type": "multipart/form-data" } });
}

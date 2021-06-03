<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>laravel-casbin-admin {{$app_name}} 授权登录中....</title>
</head>
<body>
<div style="text-align: center;margin: 100px auto;height: 500px;width: 400px">授权登陆中...</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script><script>
    window.onload = function () {
        //窗口通信函数api 将token发送给前一个页面 文档说明地址 https://developer.mozilla.org/zh-CN/docs/Web/API/Window/postMessage
        window.opener.postMessage("{{ $token }}", "{{ $domain }}");
        window.close();
    }
</script>
</body>
</html>

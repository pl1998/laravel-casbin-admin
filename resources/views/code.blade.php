<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://g.alicdn.com/dingding/dinglogin/0.0.5/ddLogin.js"></script>
    <title>钉钉授权登录二维码</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>

    </style>
</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="content">
        <div class="title m-b-md" id="login" style="text-align: center">
        </div>
    </div>
</div>
</body>
<script>
    /*
    * 解释一下goto参数，参考以下例子：
    * var url = encodeURIComponent('http://localhost.me/index.php?test=1&aa=2');
    * var goto = encodeURIComponent('https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid=appid&response_type=code&scope=snsapi_login&state=STATE&redirect_uri='+url)
    */
    var goto = '{!! urlencode('https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid=dingoa0yqggudy87gc9alo&response_type=code&scope=snsapi_login&state=STATE&redirect_uri='.$redirect_uri) !!}';
    var obj = DDLogin({
        id:"login",//这里需要你在自己的页面定义一个HTML标签并设置id，例如<div id="login_container"></div>或<span id="login_container"></span>
        goto: goto, //请参考注释里的方式
        style: "border:none;background-color:#FFFFFF;",
        width : "365",
        height: "400"
    });

    var handleMessage = function (event) {
        var origin = event.origin;
        console.log("origin", event.origin);
        if( origin == "https://login.dingtalk.com" ) { //判断是否来自ddLogin扫码事件。
            var loginTmpCode = event.data;
            //获取到loginTmpCode后就可以在这里构造跳转链接进行跳转了
            console.log("loginTmpCode", loginTmpCode);
            var appid = '{!! env('DT_AUTH_APPID') !!}'
            var redirect_uri = '{!! $redirect_uri !!}';
            var url = `https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid=${appid}&response_type=code&scope=snsapi_login&state=STATE&redirect_uri=${redirect_uri}&loginTmpCode=${loginTmpCode}`;
            window.parent.location.href = url;
        }
    };
    if (typeof window.addEventListener != 'undefined') {
        window.addEventListener('message', handleMessage, false);
    } else if (typeof window.attachEvent != 'undefined') {
        window.attachEvent('onmessage', handleMessage);
    }

</script>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <title>商管云.开发管理系统</title>

    <style>
        body {
            background: #ededed;
        }
        .title p {
            width: 100%;
            text-align: center;
            color: #3a3b3d;
            font-size: 48px;
            font-weight: 700;
            margin: 130px 0 45px 0;
        }
        .login-content {
            width: 580px;
            margin: 0 auto;
        }
        .login-content input[name="login-name"],.login-content input[name="password"],
        .login-content button {
            width: 100%;
            height: 60px;
            border-radius: 2px;
            box-sizing: border-box;
        }
        .login-content input[name="login-name"],.login-content input[name="password"] {
            line-height: 60px;
            border: 1px solid #d4d4d4;
            padding-left: 60px;
            font-size: 18px;
            color: #444;
        }
        .login-content input[name="login-name"] {
            background: #fff url("/images/develop/login-personal.png") no-repeat 23px 17px;
        }
        .login-content input[name="password"] {
            margin-top: 20px;
            background: #fff url("/images/develop/login-password.png") no-repeat 23px 16px;
        }
        .login-content input.in-edit {
            border: 1px solid #358fff;
            box-shadow: 0 0 10px #d5d5d5;
        }
        .login-content p {
            margin: 8px 0 0 0;
            height: 20px;
            line-height: 20px;
            color: #ff0000;
            font-size: 12px;
            padding-left: 30px;
            background: url("/images/develop/login-err.png") no-repeat 5px center;
        }
        .login-content .icheck-box {
            margin: 20px 0 25px 0;
            height: 20px;
        }
        .icheck-box .icheck {
            position: relative;
            display: inline-block;
            width: 20px;
            height: 20px;
        }
        .icheck input[type=checkbox]{
            visibility: hidden;
            position: absolute;
            z-index:9999;
        }
        .icheck label {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #fff;
            border: 1px solid #9f9b9b;
            border-radius: 2px;
            box-sizing: border-box;
        }
        .icheck label:after{
            content: '';
            display:inline-block;
            background:url("/images/develop/icheck.png") no-repeat 0 3px ;
            overflow: hidden;
            width:20px;
            height:20px;
            visibility: hidden;
        }
        .icheck input:checked + label:after {
            visibility: visible;
        }
        .login-content span {
            position: absolute;
            margin: 3px 0 0 5px;
            font-size: 12px;
            color: #999;
        }
        .login-content button {
            font-size: 24px;
            border: 1px solid #0055be;
            background: #358fff;
            color: #fff;
            text-align: center;
        }

    </style>

</head>

<body>

<div>
    <div class="title"><p>商管云.开发管理系统</p></div>
    <div class="login-content">
        <input type="text" class="in-edit" name="login-name" id="login_name" value="" placeholder="请输入登录账号">
        <div class="loginName"></div>
        <input type="password" name="password" id="password" value="" placeholder="请输入密码（区分大小写）" >
        <div class="passWord"></div>
        <button style="margin-top:30px;" id="login">登录</button>
    </div>
</div>


<script src="http://cdn.bootcss.com/jquery/2.2.2/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/layer/3.0.1/layer.min.js"></script>

<script>

    $(function(){

        $('#login_name').focus(function () {
            $('input').removeAttr('class');
            $(this).attr('class','in-edit');
        }).keyup(function(){
            $('.loginName').html('');
        });

        $('#password').keyup(function(){
            $('.passWord').html('');
        }).focus(function(){
            $('input').removeAttr('class');
            $('#password').attr('class','in-edit');
        });

        $('#login').click(function(){

            var login_name = $.trim($('#login_name').val());
            var password = $.trim($('#password').val());

            if ( login_name == '' ) {
                $('.loginName').html('<p>请输入正确的账号</p>');
                $('#login_name').focus();
                return false;
            }

            if (password == '') {
                $('.passWord').html('<p>请输入正确的密码</p>');
                $('#password').focus();
                return false;
            }

            var layer_index = layer.load();
            $.ajax({
                type: 'GET',
                url:'/develop/login/do',
                data:{
                    user_name : login_name,
                    pwd : password
                },
                dataType: 'json',
                success: function(obj) {
                    layer.close(layer_index);
                    if (obj.code == 200) {
                        self.location = '/develop/release';
                    }else{
                        layer.alert(obj.message,{icon:2});
                    }
                }
            });

        });
    });

</script>

</body>
</html>
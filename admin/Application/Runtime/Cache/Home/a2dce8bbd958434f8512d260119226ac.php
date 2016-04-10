<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Cat Push多平台消息推送系统</title>

        <!-- Bootstrap core CSS -->
        <link href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="/Public/css/main.css" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="/Public/js/ie-emulation-modes-warning.js"></script>
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">Cat Push多平台消息推送系统  v1.0.0</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="javascript:;">欢迎您，<?php echo ($_SESSION["_USER_"]); ?></a></li>
                        <li><a href="javascript:;" onclick="window.location.href='/login/logout'">退出</a></li>
                    </ul>
                </div>
            </div>            
        </nav>
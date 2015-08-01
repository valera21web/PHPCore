<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?=__("Logo name")?></title>

        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-responsive.css">
        <link rel="stylesheet" media="all" href="/css/animate.css">-->
        <link type="text/css" href="/css/default.css" rel="stylesheet"/>
        <link type="text/css" href="/css/navbar.css" rel="stylesheet"/>

        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>-->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
       <!--<script src="/js/wow.min.js" type="text/javascript"></script>-->
       <script src="/js/system.js" type="text/javascript"></script>
       <script src="/js/Slider.js" type="text/javascript"></script>
       <script src="/js/script.js" type="text/javascript"></script>
    </head>
    <body id="block_home">
        <?=getTemplate("topBlock")?>
        <?=getMenu("topMenu");?>
        <?=$page_content;?>
    </body>
</html>
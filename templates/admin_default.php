<!doctype html>
<html>
<head>
    <meta charset=utf-8>
    <title><?=$pageInfo['title'];?></title>
    <link type="text/css" href="/css/default.css" rel="stylesheet"/>
    <link type="text/css" href="/css/admin_default.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
    <script src="/js/system.js" type="text/javascript"></script>
    <script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script src="/js/ckeditor/config.js" type="text/javascript"></script>
    <script src="/js/ckeditor/lang/en.js" type="text/javascript"></script>
    <script src="/js/ckeditor/styles.js" type="text/javascript"></script>
    <script src="/js/ImageUploaded.js" type="text/javascript"></script>
    <script src="/js/admin_script.js" type="text/javascript"></script>
</head>
<body>
    <?=getTemplate("adminHeader");?>
    <section><?=$page_content;?></section>
    <?=getTemplate("adminFooter");?>
</body>
</html>
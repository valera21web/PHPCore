<?php
function getFiles($dir)
{
    $res = array();
    $tmp = scandir($dir);
    foreach ($tmp as $file) {
        $t = substr($file, -4);
        if($t == ".png" || $t == ".jpg")$res[] = $file;
    }
    return $res;
}

define("SP", DIRECTORY_SEPARATOR); // separator for the folders
define("DIR_HOME", str_replace(SP . "lib" . SP . "components" . SP . "languages_panel", "", __DIR__));
define("ROOT_IMAGE",DIR_HOME.SP."view".SP."images");
$tmp = scandir(ROOT_IMAGE);

$folders = array(
    'root' => getFiles(ROOT_IMAGE),
    'flags' => getFiles(ROOT_IMAGE.SP."flags"),
    'nav' => getFiles(ROOT_IMAGE.SP."nav"),
    'news' => getFiles(ROOT_IMAGE.SP."news"),
    'products' => getFiles(ROOT_IMAGE.SP."products"),
    'slider' => getFiles(ROOT_IMAGE.SP."slider"),
    'uploads' => getFiles(ROOT_IMAGE.SP."uploads"),
    'soc' => getFiles(ROOT_IMAGE.SP."soc")
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browsing Files</title>
    <style>
        .row > * { float: left; }
        .row:after { content: " "; display: table; }
        .row:after { clear: both;  }

        .select-image
        {
            position: relative;
            cursor: pointer;
            padding: 30px 5px 5px 5px;
            margin: 5px;

        }
        .del-image,
        .arrows-image,
        .link-image
        {
            width: 16px;
            height: 16px;
            position: absolute;
            top: 5px;
            right: 4px;
            opacity: 0.7;
        }
        .arrows-image
        {
            right: 34px;
        }
        .link-image
        {
            right: 60px;
        }
        .link-image:hover,
        .arrows-image:hover,
        .del-image:hover
        {
            opacity: 1;
        }
        .select-image .image
        {
            width: 80px;
            height: 80px;
        }
        .nav-folder
        {
            cursor: pointer;
            background: #334d66;
            border-radius: 0 5px 5px 0;
            line-height: 30px;
            margin: 5px 0;
            padding-left: 10px;
            color: #fff;
        }
        .folder-list
        {
            display: none;
        }
        .folders
        {
            width: 80%;
        }
        #root
        {
            background: #ccc;
        }
        #dir_root
        {
            display: block;
        }

        .dialog-arrow
        {
            position: fixed;
            top: 40%;
            left: 35%;
            line-height: 35px;;
            background: #2d7bad;
            border-radius: 5px;
            padding: 50px 20px;
            color: #fff;
        }
        .dialog-arrow .dialog-cancel,
        .dialog-arrow .dialog-ok
        {
            padding: 0 10px;
            border-radius: 5px;
            background: #c83939;
            margin-left: 20px;
            cursor: pointer;
        }
        .dialog-arrow .dialog-ok
        {
            background: #478500;
        }
    </style>
</head>
<body>
    <section class="row">
        <nav style="width: 20%">
            <div class="nav-folder" id="root">Root</div>
            <div class="nav-folder" id="flags">Flags</div>
            <div class="nav-folder" id="nav">Navigation</div>
            <div class="nav-folder" id="news">News</div>
            <div class="nav-folder" id="products">Products</div>
            <div class="nav-folder" id="slider">Slider</div>
            <div class="nav-folder" id="uploads">Uploads</div>
            <div class="nav-folder" id="soc">Soc</div>
        </nav>
        <div class="folders">
<?php
        foreach ($folders as $key => $files)
        {
            echo '<div class="folder-list row" id="dir_'.$key.'">';
            $key = $key == "root" ? "" : "/".$key;
            foreach ($files as $file)
            {
                echo "<div class='select-image' data-val='$key/$file'  >
                    <img class='del-image' src='/languages/content/delete.png'>
                    <img class='arrows-image' src='/languages/content/arrows.png'>
                    <img class='link-image' src='/languages/content/link.png'>
                    <img class='image' onclick='returnFileUrl(\"/img$key/$file\")' src='/img$key/$file' />
                </div>";
            }
            echo '</div>';
        }
?>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="application/javascript">
        var flagSelectMove = 0;
        var imageMove = '';
        function getUrlParam( paramName )
        {
            var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
            var match = window.location.search.match( reParam );

            return ( match && match.length > 1 ) ? match[1] : null;
        }
        function returnFileUrl(url)
        {
            window.opener.CKEDITOR.tools.callFunction( getUrlParam( 'CKEditorFuncNum' ), url );
            window.close();
        }

        $(document).ready(function ()
        {
            $('.nav-folder').on('click', function ()
            {
                var folder = $(this).attr('id');
                if(flagSelectMove == 0)
                {
                    $('.dialog-arrow').remove();
                    $('.folder-list').hide();
                    $("#dir_" + folder).show();
                    $('.nav-folder').css('background', '#334d66');
                    $("#"+folder).css('background', '#ccc');
                }
                else
                {
                    if(confirm('Are you sure you want move image?'))
                    {
                        $.ajax({
                            url: '/languages/move-image',
                            type: 'POST',
                            dataType: "json",
                            data: {
                                image: imageMove,
                                new_folder: folder
                            },
                            success: function(data)
                            {
                                if (data.status == 1)
                                {
                                    $('.select-image[data-val="' + imageMove + '"]').remove();
                                    $("#dir_" + folder).append("<div class='select-image' data-val='"+data.data+"'> " +
                                        "<img class='del-image' src='/languages/content/delete.png'> " +
                                        "<img class='arrows-image' src='/languages/content/arrows.png'> " +
                                        "<img class='link-image' src='/languages/content/link.png'> " +
                                        "<img class='image' onclick='returnFileUrl(\"/img"+data.data+")' src='/img"+data.data+"' /> "+
                                        "</div>");
                                    flagSelectMove = 0;
                                    $('.dialog-arrow').remove();
                                }
                            }
                        });
                    }
                }
            });

            $(".del-image").on('click', function ()
            {
                flagSelectMove = 0;
                $('.dialog-arrow').remove();

                var _id = $(this).parent('.select-image').attr("data-val");

                if(confirm('Are you sure you want to delete this image?'))
                {
                    $.ajax({
                        url: '/languages/del-image',
                        type: 'POST',
                        dataType: "json",
                        data: {
                            image: _id
                        },
                        success: function(data)
                        {
                            if (data.status == 1)
                            {
                                $('.select-image[data-val="' + _id + '"]').remove();
                            }
                        }
                    });
                }
            });

            $(".arrows-image").on('click', function ()
            {
                flagSelectMove = 0;
                $('.dialog-arrow').remove();

                imageMove = $(this).parent('.select-image').attr("data-val");
                $('body').append("<div class='dialog-arrow row'><div>Select folder to move image please.</div><div class='dialog-cancel'>Cancel</div></div>");
                flagSelectMove = 1;
                $(".dialog-cancel").on('click', function () { flagSelectMove = 0; $('.dialog-arrow').remove(); });
            });

            $(".link-image").on('click', function ()
            {
                flagSelectMove = 0;
                $('.dialog-arrow').remove();

                var url = $(this).parent('.select-image').find('.image').attr("src");
                $('body').append("<div class='dialog-arrow row'><div>Link for this image: "+url+" </div> <div class='dialog-ok'>Ok</div></div>");
                $(".dialog-ok").on('click', function () { $('.dialog-arrow').remove(); });
            });
        });
    </script>
</body>
</html>
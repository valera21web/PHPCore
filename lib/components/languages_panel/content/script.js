var list_lang_vars =  Array();
$(function()
{
    $("aside > nav > ul > li > a").each(function(k,v)
    {
        var tmp = $(this).text();
        if(tmp != "New") {
            list_lang_vars[k] = {"index": k,"text": tmp}
        }
    });

    $("#search_lang").keyup(function()
    {
        filterLanguagesVarsBySearch($(this).val());
    });
});

function filterLanguagesVarsBySearch(str)
{
    if(str != null && str != "") {
        str = str.toLowerCase();
        $("aside > nav > ul > li > a").each(function()
        {
            var tmp = $(this).text().toLowerCase();
            if(tmp != "new")
            {
                if(tmp.indexOf(str) == -1)
                {
                    $(this).parent("li").hide();
                }
            } else {
                $(this).parent("li").hide();
            }
        });
    } else {
        $("aside > nav > ul > li").show();
    }
}


$(document).ready(function()
{
    if($('#textarea_en').size())
        CKEDITOR.replace('textarea_en' , {
            language: 'en',
            enterMode: Number(2),
            extraPlugins: 'uploadimage,uploadwidget,notificationaggregator,notification,button,widget,toolbar,clipboard,dialog,filetools',
            filebrowserBrowseUrl: '/languages/browse?type=Files',
            filebrowserUploadUrl: '/languages/upload?type=Files'
        });
    if($('#textarea_pl').size())
        CKEDITOR.replace('textarea_pl' , {
            language: 'pl',
            enterMode: Number(2),
            extraPlugins: 'uploadimage,uploadwidget,notificationaggregator,notification,button,widget,toolbar,clipboard,dialog,filetools',
            filebrowserBrowseUrl: '/languages/browse?type=Files',
            filebrowserUploadUrl: '/languages/upload?type=Files'
        });
    if($('#textarea_ru').size())
        CKEDITOR.replace('textarea_ru' , {
            language: 'ru',
            enterMode: Number(2),
            extraPlugins: 'uploadimage,uploadwidget,notificationaggregator,notification,button,widget,toolbar,clipboard,dialog,filetools',
            filebrowserBrowseUrl: '/languages/browse?type=Files',
            filebrowserUploadUrl: '/languages/upload?type=Files'
        });
    if($('#textarea_ua').size())
        CKEDITOR.replace('textarea_ua' , {
            language: 'ua',
            enterMode: Number(2),
            extraPlugins: 'uploadimage,uploadwidget,notificationaggregator,notification,button,widget,toolbar,clipboard,dialog,filetools',
            filebrowserBrowseUrl: '/languages/browse?type=Files',
            filebrowserUploadUrl: '/languages/upload?type=Files'
        });
    if($('#textarea_de').size())
        CKEDITOR.replace('textarea_de' , {
            language: 'de',
            enterMode: Number(2),
            extraPlugins: 'uploadimage,uploadwidget,notificationaggregator,notification,button,widget,toolbar,clipboard,dialog,filetools',
            filebrowserBrowseUrl: '/languages/browse?type=Files',
            filebrowserUploadUrl: '/languages/upload?type=Files'
        });


    $('.nav-top > div').hover(function () {
        var path = $(this).find(".nav-link").attr("data-path");
        if($(".path-" + path + "-sub").size())
        {
            $(".nav-sub-links.active").hide();
            $(".path-" + path + "-sub").show();
        }

    }, function () {
        var path = $(this).find(".nav-link").attr("data-path");
        if($(".path-" + path + "-sub").size())
        {
            $(".path-" + path + "-sub").hide()
            $(".nav-sub-links.active").show();
        }
    });

    $("#button_delete").on('click', function () {
        return confirm('Are you sure you want to delete this variable?');
    })
});
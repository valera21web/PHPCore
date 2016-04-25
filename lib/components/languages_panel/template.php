<?php
$ACTIVE_PAGE = !empty($_GET['p']) ? $_GET['p'] : null;
$ACTIVE_SUB_PAGE = !empty($_GET['sp']) ? $_GET['sp'] : null;
function activePage($page)
{
    global $ACTIVE_PAGE;
    return $page == $ACTIVE_PAGE ? " active" : "";
}
function activeSubPage($page, $subPage)
{
    global $ACTIVE_PAGE, $ACTIVE_SUB_PAGE;
    return $page == $ACTIVE_PAGE && $ACTIVE_SUB_PAGE == $subPage ? " active" : "";
}
function getActiveName()
{
    global $ACTIVE_PAGE, $ACTIVE_SUB_PAGE;
    if($ACTIVE_SUB_PAGE != null)
        return strtoupper($ACTIVE_PAGE ." ". $ACTIVE_SUB_PAGE);
    else if($ACTIVE_PAGE != null)
        return strtoupper($ACTIVE_PAGE);
    else
        return null;
}

$fullList = $LANG->getNames();
asort($fullList, SORT_STRING);

$filterList = array();
$activeFilter = getActiveName();
if($activeFilter != null)
{
    foreach ($fullList AS $item)
    {
        if(strpos($item, $activeFilter) === 0)
        {
            $filterList[] = $item;
        }
    }
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8>
        <title>Languages panel 2.0</title>
        <link type="text/css" href="/languages/content/style.css" rel="stylesheet"/>
    </head>
    <body>
        <header>
            <nav>
                <div class="nav-top row">
                    <div>
                        <a class="nav-link<?=activePage("page")?>" data-path="page">PAGES</a>
                        <div class="nav-sub-links path-page-sub row<?=activePage("page")?>">
                            <a href="/languages/page/link" class="nav-sub-link<?=activeSubPage("page", "link")?>">LINKS</a>
                            <a href="/languages/page/title" class="nav-sub-link<?=activeSubPage("page", "title")?>">TITLE</a>
                            <a href="/languages/page/description" class="nav-sub-link<?=activeSubPage("page", "description")?>">DESCRIPTION</a>
                            <a href="/languages/page/content" class="nav-sub-link<?=activeSubPage("page", "content")?>">CONTENT</a>
                        </div>
                    </div>
                    <div>
                        <a class="nav-link<?=activePage("product")?>" data-path="product">PRODUCT</a>
                        <div class="nav-sub-links path-product-sub row<?=activePage("product")?>">
                            <a href="/languages/product/title" class="nav-sub-link<?=activeSubPage("product", "title")?>">TITLE</a>
                            <a href="/languages/product/description" class="nav-sub-link<?=activeSubPage("product", "description")?>">DESCRIPTION</a>
                            <a href="/languages/product/text" class="nav-sub-link<?=activeSubPage("product", "text")?>">TEXT</a>
                        </div>
                    </div>
                    <div>
                        <a class="nav-link<?=activePage("news")?>" data-path="news">NEWS</a>
                        <div class="nav-sub-links path-news-sub row<?=activePage("news")?>">
                            <a href="/languages/news/title" class="nav-sub-link<?=activeSubPage("news", "title")?>">TITLE</a>
                            <a href="/languages/news/description" class="nav-sub-link<?=activeSubPage("news", "description")?>">DESCRIPTION</a>
                            <a href="/languages/news/text" class="nav-sub-link<?=activeSubPage("news", "text")?>">TEXT</a>
                        </div>
                    </div>
                    <div><a href="/languages/text" class="nav-link<?=activePage("text")?>">TEXT</a></div>
                    <div><a href="/languages/link" class="nav-link<?=activePage("link")?>">LINKS</a></div>
                    <div><a href="/languages/reviews" class="nav-link<?=activePage("reviews")?>">REVIEWS</a></div>
                    <div><a href="/languages/title" class="nav-link<?=activePage("title")?>">TITLES</a></div>
                </div>
            </nav>
        </header>
        <section class="row">
            <div class="left-nav">

<?php
                if(!empty($filterList))
                {
?>
                    <div><?=$activeFilter."..."?></div>
                    <div><a class="item-left-nav" href="?val=new">New</a></div>
<?php
                    foreach($filterList AS $lang)
                        echo '<div><a class="item-left-nav" href="?val='. $lang .'">'. str_replace($activeFilter,"", $lang) .'</a></div>';
                }
?>
            </div>
            <div class="content">
                <?=$page_content;?>
            </div>
        </section>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="/ckeditor/ckeditor.js"></script>
        <script src="/languages/content/script.js"></script>
    </body>
</html>
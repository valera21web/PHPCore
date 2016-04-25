<div class="topSlider">
<?php
$group = db_query("SELECT `image`,`name` FROM slider");
foreach ($group as $item)
    echo '<div class="sliderItem"><img src="/img/slider/'.$item['image'].'" alt="" /></div>';
?>
</div>
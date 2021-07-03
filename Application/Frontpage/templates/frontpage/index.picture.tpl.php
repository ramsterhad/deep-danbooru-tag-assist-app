<!-- file_url is the original (large resolution) file which has not been resized by *booru -->
<div class="row">
    <div class="col-lg-2 hidden">file_url</div>
    <div class="col-lg-10 full"><a href="<?php echo $response->get('danbooru')->getPost()->getPicOriginal();?>" target="_blank" rel="noreferrer"><span class="hidden"><?php echo $response->get('danbooru')->getPost()->getPicOriginal();?><br></span><img src="<?php echo $response->get('danbooru')->getPost()->getPicLarge(); ?>" referrerpolicy="no-referrer"></a></div>
</div>
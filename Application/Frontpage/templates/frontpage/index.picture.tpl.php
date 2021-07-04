<!-- file_url is the original (large resolution) file which has not been resized by *booru -->
<a href="<?php echo $response->get('danbooru')->getPost()->getPicOriginal();?>" target="_blank" rel="noreferrer">
    <img src="<?php echo $response->get('danbooru')->getPost()->getPicLarge(); ?>" referrerpolicy="no-referrer">
</a>

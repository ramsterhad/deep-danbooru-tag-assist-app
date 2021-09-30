<!-- file_url is the original (large resolution) file which has not been resized by *booru -->
<a href="<?php echo $response->get('post')->getPicOriginal();?>" target="_blank" rel="noreferrer">
    <img src="<?php echo $response->get('post')->getPicLarge(); ?>" referrerpolicy="no-referrer">
</a>

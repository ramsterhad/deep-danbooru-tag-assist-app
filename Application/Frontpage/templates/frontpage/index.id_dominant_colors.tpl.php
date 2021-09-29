<div class="row top-buffer">
    <div class="col-lg-1 column-header">ID</div>
    <div class="col-lg-11">
        <div class="danbooru-id">
        <a href="<?php echo $response->get('danbooruApiUrl') . 'posts/' . $response->get('post')->getId();?>" target="_blank" rel="noreferrer">
            <?php echo $response->get('post')->getId();?>
        </a>
        </div>
        <!-- <Dominant color bar> -->
        <span>
        <?php foreach ($response->get('post')->getPicture()->getDominantColors() as $color) : ?>
            <div class="dominant-colors-box" style="background-color:<?php echo $color;?>"></div>
        <?php endforeach; ?>
    </span>
        <!-- </Dominant color bar> -->
    </div>


</div>
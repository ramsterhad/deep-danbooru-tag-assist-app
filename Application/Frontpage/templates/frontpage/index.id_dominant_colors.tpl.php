<div class="row flex_cont">
    <div class="col s2 " style="margin:auto;margin-left:0">id</div>
    <div class="col s10" style="width:unset;margin:auto;margin-left:0"><a href="<?php echo $response->get('dannboruApiUrl') . 'posts/' . $response->get('danbooru')->getPost()->getId();?>" target="_blank" rel="noreferrer"><?php echo $response->get('danbooru')->getPost()->getId();?></a></div>

    <!-- <Dominant color bar> -->
    <div class="flex_cont" style="margin: auto;margin-right: 0;padding-left: 2%;">
        <?php foreach ($response->get('machineLearningPlatform')->getPicture()->getDominantColors() as $color) : ?>
            <div class="dominant-colors-box" style="background-color:<?php echo $color;?>"></div>
        <?php endforeach; ?>
        <!-- </Dominant color bar> -->
    </div>
</div>
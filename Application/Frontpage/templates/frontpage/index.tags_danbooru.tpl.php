<div class="row">
    <div class="col s2">Danbooru tags</div>
    <div class="col s10">
        <?php foreach ($response->get('danbooru')->getPost()->getTagCollection()->getTags() as $tag) : ?>
            <span class="tag"><?php echo \Ramsterhad\DeepDanbooruTagAssist\Application\Api\MachineLearningPlatform\TemplateHelper::tagsCssClassHelperColoredDanbooruTags($tag); ?></span>
        <?php endforeach; ?>
    </div>
</div>
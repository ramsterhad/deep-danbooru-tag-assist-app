<?php /* Include from index.tags_suggested.tpl.php */ ?>
<div class="col-lg-4">
    <div
        class="form-check tag mlpTag"
        data-key="<?php echo $keyIdent + $i + 1;?>"
        id="ml_new_tag_<?php echo $response->get('unknownTags')->getTags()[$i]->getName();?>"
    >
        <input
            class="form-check-input"
            type="checkbox"
            name="tag_checkbox[]"
            value="<?php echo $response->get('unknownTags')->getTags()[$i]->getName() ;?>"
            id="tag-checkbox-<?php echo $response->get('unknownTags')->getTags()[$i]->getName();?>"
        >
        <label
            class="form-check-label"
            for="tag-checkbox-<?php echo $response->get('unknownTags')->getTags()[$i]->getName();?>"
            title="<?php echo $response->get('unknownTags')->getTags()[$i]->getScore(); ?>"
        >
            <a href="https://danbooru.donmai.us/wiki_pages/<?php echo $response->get('unknownTags')->getTags()[$i]->getName();?>" target="_blank" rel="noreferrer">
                <?php echo '[ '.($i + 1).' ] ' . $response->get('unknownTags')->getTags()[$i]->getName(); ?>
            </a>
        </label>
    </div>
</div>

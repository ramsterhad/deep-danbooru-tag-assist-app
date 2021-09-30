<div class="row top-buffer">
    <div class="col-lg column-header">Suggested tags</div>
</div>
<div class="row">
    <div class="col-lg">
        <?php echo $response->getController()->tagsCssClassHelperUnknownTags($response->get('suggestedTags'), $response->get('unknownTags')); ?>
    </div>
</div>


<div class="row top-buffer">
    <div class="col-lg column-header">Tags to submit</div>
</div>
<div class="row">
    <div class="col-lg">
        <form action="index.php" method="post" id="form_submit_tags">
            <?php

            $maxTags = $response->getController()->getCountedUnknownTagsLimitedByValue(
                $response->get('unknownTags'),
                $response->get('suggestedTagsLimit')
            );
            $keyIdent = 96; // numpad 0, used in index.tags_suggested_tag_col.tpl.php to calculate num 1 to 9.
            $firstRow = true;

            for ($i = 0; $i < $maxTags; $i++) {

                if ($i % 3 === 0) {

                    if (!$firstRow) {
                        echo '</div>'; // Closes recently opened row. But not for the first row.
                    }

                    $firstRow = false;
                    echo '<div class="row new_tags_grid">';

                }

                require 'index.tags_suggested_tag_col.tpl.php';
            }

            if ($maxTags > 0) {
                echo '</div>';
            }

            ?>

            <?php foreach ($response->get('post')->getTagCollection()->getTags() as $tag) : ?>
                <input type="hidden" name="tag_checkbox_existing_tags[]" value="<?php echo $tag->getName(); ?>">
            <?php endforeach; ?>

            <input type="hidden" name="tag_checkbox_post_id" value="<?php echo $response->get('post')->getId() ;?>">
            <input type="hidden" name="r" value="pushnewtags">
            <button type="submit" id="id_tag_checkbox_submit" name="name_tag_checkbox_submit" class="tag_checkbox_submit"></button>
        </form>
    </div>
</div>
<?php declare(strict_types=1);

namespace Ramsterhad\DeepDanbooruTagAssist;

use Ramsterhad\DeepDanbooruTagAssist\Application\Api\Danbooru\Danbooru;
use Ramsterhad\DeepDanbooruTagAssist\Application\Api\MachineLearningPlatform\TemplateHelper;
use Ramsterhad\DeepDanbooruTagAssist\Application\Application;
use Ramsterhad\DeepDanbooruTagAssist\Application\Configuration\Config;

require_once '../bootstrap.php';

set_time_limit (3);

$app = Application::getInstance();
$app->run();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Deep Danbooru Tag Assist 3000</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="css/base.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>

</head>
<body>
<header></header>
<main>
<div class="container">
    <?php if (!empty($app->getError())) : ?>
        <div class="row error">
            <div class="col s2"></div>
            <div class="col s10"><?php echo $app->getError(); ?></div>
        </div>
    <?php endif; ?>
    <?php if (!$app->isAuthenticated()) : ?>
    <div class="row">&nbsp;</div>
    <div class="row">
        <div class="col s12">
            <h3 class="center-align">Deep Danbooru Tag Assist 3000</h3>
        </div>
    </div>
    <div class="row">
        <div class="col s2"></div>
        <div class="col s10">
            <div class="row">
                <form action="index.php" method="post">
                    <div class="col s4">
                        <input type="text" name="username" placeholder="username">
                    </div>
                    <div class="col s4">
                        <input type="text" name="api_key" placeholder="api key">
                    </div>
                    <div class="col s2">
                        <button type="submit" name="submit" class="btn waves-effect waves-light" >login</button>
                    </div>
                    <input type="hidden" name="c" value="auth">
                    <input type="hidden" name="a" value="checkAuthenticationRequest">
                </form>
            </div>
        </div>

        <?php else : ?>
        <div class="flex_cont">
        <div class="flex_left">
    <div class="row">
        <div class="col s12">
            <h3 class="center-align">Deep Danbooru Tag Assist 3000</h3>
        </div>
    </div>
    <div class="row">
        <div class="col s12"></div>
    </div>

    <div class="row">
        <div class="col s2 hidden">danbooru</div>
        <div class="col s10">
            <div class="row">
                <div class="col s10">
                    <form action="index.php" method="post" class="" id="form_set_danbooru_api_url">
                        <input id="input_id_danbooru_api_url" name="input_name_danbooru_api_url" type="text" value="<?php echo Danbooru::loadEndpointAddress();?>">
                        <label for="input_id_danbooru_api_url">danbooru api url</label>
                        <input type="hidden" name="set_danbooru_api_url" value="">
                        <input type="hidden" name="c" value="apiurl">
                        <input type="hidden" name="a" value="setCustomApiUrl">
                    </form>
                </div>
                <div class="col s2">
                    <button onclick="document.getElementById('form_set_danbooru_api_url').submit();" class="btn waves-effect waves-light url_form" type="submit" name="api_submit_save" title="save your custom api url"><i class="material-icons">save</i></button>
                    <form action="index.php" method="post" class="url_form" name="form2">
                        <input type="hidden" name="c" value="apiurl">
                        <input type="hidden" name="a" value="resetApiUrlToDefault">
                        <button class="btn waves-effect waves-light" type="submit" name="api_submit_reset" title="reset to default api url"><i class="material-icons">undo</i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <?php if (empty($app->getError())) : ?>

    

        <div class="row">
            <div class="col s2">id</div>
            <div class="col s10" style="width:unset"><a href="<?php echo Config::get('danbooru_api_url') . 'posts/' . $app->get('danbooru')->getPost()->getId();?>" target="_blank" rel="noreferrer"><?php echo $app->get('danbooru')->getPost()->getId();?></a></div>
        </div>
        <!--
        <div class="row">
            <div class="col s2">preview</div>
            <div class="col s10">
                <img src="<?php echo $app->get('danbooru')->getPost()->getPicPreview(); ?>"><br>
                <a href="<?php echo $app->get('danbooru')->getPost()->getPicPreview(); ?>" target="_blank" rel="noreferrer"><?php echo $app->get('danbooru')->getPost()->getPicPreview(); ?></a>
            </div>
        </div>
        -->
        <div class="row">
            <div class="col s2">Danbooru tags</div>
            <div class="col s10">
                <?php foreach ($app->get('danbooru')->getPost()->getTagCollection()->getTags() as $tag) : ?>
                    <span class="tag"><?php echo $tag->getName();?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="row">
            <div class="col s2">ML Plattform tags</div>
            <div class="col s10"><?php echo TemplateHelper::tagsCssClassHelper($app->get('machineLearningPlatform')->getCollection(), $app->get('unknownTags')); ?></div>
        </div>
        <div class="row">
            <div class="col s2">new tags</div>
            <div class="col s10">
                <form action="index.php" method="post">
                    <?php

                    $maxTags = $app->getController()->getCountedUnknownTagsLimitedByValue(
                        $app->get('unknownTags'),
                        (int) Config::get('limit_for_suggested_tags')
                    );
                    $keyIdent = 96; // numpad 0
                    /*
                     * $closeRow decides if a row gets a new column or if the row has to be closed and a new one started.
                     * It starts with 0 (new row) and directly gets increased to 2.
                     * For each column it gets decreased by 1. After it reached 0, the row will be closed and a new one
                     * will be opened and the circle starts again.
                     */
                    $closeRow = 0;

                    for ($i = 0; $i < $maxTags; $i++) : ?>

                        <?php if ($i % 3 === 0) : ?>
                        <?php $closeRow = 2; // Opens a new row. If not, a new column will be appended to the previous column. ?>
                        <div class="row new_tags_grid">
                        <?php endif; ?>

                        <div class="col s4">
                            <span
                                class="tag mlpTag"
                                data-key="<?php echo $keyIdent + $i + 1;?>"
                                id="ml_new_tag_<?php echo $app->get('unknownTags')->getTags()[$i]->getName();?>"
                            >
                               <label>
                                   <input
                                       type="checkbox"
                                       class="tag_checkbox"
                                       name="tag_checkbox[]"
                                       value="<?php echo $app->get('unknownTags')->getTags()[$i]->getName() ;?>"
                                   >
                                   <span class="new_tags_grind_col" title="<?php echo $app->get('unknownTags')->getTags()[$i]->getScore(); ?>">
                                       <a href="https://danbooru.donmai.us/wiki_pages/<?php echo $app->get('unknownTags')->getTags()[$i]->getName();?>" target="_blank" rel="noreferrer"><?php echo '[ '.($i + 1).' ] ' . $app->get('unknownTags')->getTags()[$i]->getName(); ?></a>
                                   </span>
                               </label>
                           </span>
                        </div>
                        <?php if ($closeRow === 0) : // Ends the row. ?>
                        </div>
                        <?php endif; ?>

                        <?php --$closeRow; // Decreases the counter for each column by 1. ?>
                    <?php endfor; ?>


                    <?php foreach ($app->get('danbooru')->getPost()->getTagCollection()->getTags() as $tag) : ?>
                        <input type="hidden" name="tag_checkbox_existing_tags[]" value="<?php echo $tag->getName(); ?>">
                    <?php endforeach; ?>

                    <input type="hidden" name="tag_checkbox_post_id" value="<?php echo $app->get('danbooru')->getPost()->getId() ;?>">
                    <input type="hidden" name="c" value="pushnewtags">
                    <input type="hidden" name="a" value="pushNewTagsToDanbooru">
                    <button type="submit" id="id_tag_checkbox_submit" name="name_tag_checkbox_submit" class="tag_checkbox_submit"></button>
                </form>
            </div>
        </div>
        </div>
        <div class="flex_right">
        <!-- file_url is the original (large resolution) file which has not been resized by *booru -->
        <div class="row">
            <div class="col s2 hidden">file_url</div>
            <div class="col s10 full"><a href="<?php echo $app->get('danbooru')->getPost()->getPicOriginal();?>" target="_blank" rel="noreferrer"><span class="hidden"><?php echo $app->get('danbooru')->getPost()->getPicOriginal();?><br></span><img src="<?php echo $app->get('danbooru')->getPost()->getPicLarge(); ?>" referrerpolicy="no-referrer"></a></div>
        </div>
        <!-- Show large_file by unquoting -->
        <!--
    <div class="row">
        <div class="col s2">large_file_url</div>
        <div class="col s10"><a href="<?php echo $app->get('danbooru')->getPost()->getPicLarge();?>" target="_blank" rel="noreferrer"><?php echo $app->get('danbooru')->getPost()->getPicLarge();?><br><img src="<?php echo $app->get('danbooru')->getPost()->getPicLarge(); ?>" referrerpolicy="no-referrer"></a></div>
    </div>
    -->
    </div>
        <?php endif; // error end. ?>
</div>
    <?php endif; // login check end. ?>
    </div>
</div>
</main>
<footer class="page-footer">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h5>Manual</h5>

                <div class="row footer-help-row">
                    <div class="col s2">Numpad Keys 1 - 9</div>
                    <div class="col s10">Activate the checkboxes of the tags.</div>
                </div>
                <div class="row footer-help-row">
                    <div class="col s2">Enter</div>
                    <div class="col s10">Send the new activated tags to Danbooru and load a new post.</div>
                </div>
                <div class="row footer-help-row">
                    <div class="col s2">Spacebar</div>
                    <div class="col s10">Load a new post. Do not send anything to Danbooru.</div>
                </div>
            </div>

        </div>
    </div>
    <div id="container-footer">
        <div id="a">
            &copy; <span id="year"></span> reiuyi + Ramsterhad
        </div>
        <div id="b">
            <span class="footer-copyright-right-item">
                <form method="post" action="index.php">
                    <input type="hidden" name="c" value="logout" />
                    <input type="hidden" name="a" value="index" />
                    <button type="submit" class="button-cosplays-as-link" value="Logout">> Logout</button>
                </form>
            </span>
            <span class="footer-copyright-right-item">
                <a class="text-lighten-4" href="https://github.com/Ramsterhad/DeepDanbooruTagAssist" target="_blank" rel="noreferrer">>Source Code</a>
            </span>
        </div>
    </div>
</footer>

    <script type="text/javascript">

        // Copyright year
        document.getElementById('year').innerHTML = new Date().getFullYear();

        // Listen if a key is pressed.
        // If it is a num pad key, then an event bound to the new tags, identified by the class "mlpTag" is fired.
        window.addEventListener('keydown', function(e) {

            // <register Numpad keys>
            const elements = document.getElementsByClassName("mlpTag");

            for (let i = 0; i < elements.length; i++) {
                const element = elements[i];

                // If the element attribute data-key matches the pressed key, the event is triggered.
                if (element.getAttribute('data-key') == e.keyCode) {
                    // Click the checkbox which is chained to a num pad key.
                    element.getElementsByClassName('tag_checkbox')[0].click();
                }
            }
            // </register Numpad keys>

            // Enter - execute api call to add new tags
            if (e.keyCode === 13) {
                e.preventDefault();
                document.getElementById('id_tag_checkbox_submit').focus();
                document.getElementById('id_tag_checkbox_submit').click();
            }

            // Spacebar - reload with a new post. No api call.
            if (e.keyCode === 32) {
                e.preventDefault();
                location.reload();
            }
        });
    </script>
</body>
</html>

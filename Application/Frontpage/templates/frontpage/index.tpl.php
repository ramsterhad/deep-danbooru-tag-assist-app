<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<header></header>
<main>
<div class="container">

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

            <!-- danboooru api url -->
            <?php require_once 'index.endpoint.tpl.php'; ?>

            <!-- post id and dominant color -->
            <?php require_once 'index.id_dominant_colors.tpl.php'; ?>

            <!-- preview picture, disabled by default @todo-->
            <?php require_once 'index.preview_picture.tpl.php'; ?>

            <!-- tags -->
            <?php require_once 'index.tags_danbooru.tpl.php'; ?>
            <?php require_once 'index.tags_suggested.tpl.php'; ?>

        </div> <!-- end flex_left -->
        <div class="flex_right">

            <?php require_once 'index.picture.tpl.php'; ?>

        </div> <!-- end flex_right -->

    </div> <!-- end flex content -->
</main>


<script type="text/javascript">

    // Listen if a key is pressed.
    // If it is a num pad key, then an event bound to the new tags is fired, identified by the class "mlpTag".
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

        // Enter - execute api call to add new tags or save changed API URL.
        if (e.keyCode === 13) {
            // Prevent default browser behaviour, which is to open the selected item
            e.preventDefault();

            // Check if the URL input field is focused. If yes, save the new URL.
            if (document.activeElement === document.getElementById('input_id_danbooru_api_url')) {
                document.getElementById('form_set_danbooru_api_url').submit();
            } else {
                // else fire the checked tags to danbooru
                document.getElementById('form_submit_tags').submit();
            }
        }

        // Spacebar - reload with a new post. No api call.
        if (e.keyCode === 32) {
            e.preventDefault();
            location.reload();
        }
    });
</script>


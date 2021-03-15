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
            <?php require_once 'index.tags_mlp.tpl.php'; ?>

        </div> <!-- end flex_left -->
        <div class="flex_right">

            <?php require_once 'index.picture.tpl.php'; ?>

        </div> <!-- end flex_right -->

    </div> <!-- end flex content -->
</main>


<script type="text/javascript">

    // Copyright year
    document.getElementById('year').innerHTML = new Date().getFullYear();

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

        // Enter - execute api call to add new tags
        if (e.keyCode === 13) {
            // Prevent default browser behaviour, which is to open the selected item
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


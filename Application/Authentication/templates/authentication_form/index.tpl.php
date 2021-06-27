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
                <input type="hidden" name="r" value="authenticate">
            </form>
        </div>
        <?php if ($response->has('authentication_wrong_credentials')) : ?>
        <div class="row">
            <p>Danbooru said no to your credentials. (╯︵╰,)<br>Whats your name and api key again?<br>must. know. that.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

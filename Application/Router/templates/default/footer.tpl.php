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
            &copy; <span id="year"></span> reiuyi + ramsterhad
        </div>
        <div id="b">
            <span class="footer-copyright-right-item">
                <form method="post" action="index.php">
                    <input type="hidden" name="r" value="logout" />
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
</script>
</body>
</html>
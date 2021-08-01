<footer class="footer">
    <div class="footer-wrapper">
        <div class="footer-part">
            <img src="/assets/img/serverjars.svg" alt="ServerJars Icon" class="footer-icon">
            <div class="footer-texts">
                <h3 class="footer-copy">&copy; <?php echo date('Y') ?> ServerJars</h3>
                <h3 class="footer-register">Registered in Connecticut</h3>
                <h3 class="footer-id">ID #1319191</h3>
            </div>
        </div>
        <div class="footer-part">
            <p class="footer-note">Made with love by</p>
            <a href="https://songoda.com" target="_blank"><img src="/assets/img/logo-white.svg" alt="ServerJars Logo" class="footer-logo"></a>
        </div>
    </div>
    <h1 class="footer-credit">Website created by <a target="_blank" href="https://github.com/jacobtread">Jacobtread</a></h1>
</footer>
<script>

    var $schemeToggle = $('#schemeToggle');
    var $schemeIcon = $schemeToggle.children('i').eq(0);

    $schemeToggle.on('click', function () {
        toggleDarkMode(true);
    });

    function toggleDarkMode(isLoaded) {
        var isLight = $schemeIcon.is('.fa-sun');
        var $body = $('body');
        if (isLoaded) {
            $body.addClass('body-loaded');
        }
        if (isLight) {
            $schemeIcon.removeClass('fa-sun');
            $schemeIcon.addClass('fa-moon');
            $body.addClass('dark-mode');
        } else {
            $schemeIcon.removeClass('fa-moon');
            $schemeIcon.addClass('fa-sun');
            $body.removeClass('dark-mode');
        }
        if (isLoaded) {
            $.cookie('dark', isLight, {path: '/'});
            console.log($.cookie())
        }
    }
    var oldTitle = document.title;
    document.title = "Loading...";
    $(window).on("load", function () {
        document.title = oldTitle;
        $('.loader-background').addClass('loader-removing');
        setTimeout(function () {
            $('.loader-background').remove();
        }, 500);
    });

</script>
<?php
if (isset($_COOKIE) && isset($_COOKIE['dark'])) {
    $isDark = $_COOKIE['dark'] == 'true';
    if ($isDark) {
        echo '<script>toggleDarkMode(false); </script>';
    }
}
?>
</body>
</html>
<?php
$errorCode = 404;
if (isset($_GET['code'])) {
    $errorCode = (int)$_GET['code'];
}
http_response_code($errorCode);
$_TITLE = 'Error ' . $errorCode;
include_once 'includes/templates/header.php';
?>
    <div class="welcome-wrapper">
        <img src="/assets/img/surprised_small.png" class="error-icon welcome-icon" alt="Brianna">
        <div class="welcome-box">
            <h1 class="welcome-title">That wasn't expected....</h1>
            <p class="welcome-message">
                It seems you have reached a page that does not exist or could not be found
            </p>
            <div class="stats">
                <a class="welcome-button" href="/"><i class="fa fa-home"></i><span>Home</span></a>
                <div class="welcome-button"><i class="fa fa-book-open"></i><span>Error <?php echo $errorCode ?></span></div>
            </div>
        </div>
    </div>
<?php
include_once 'includes/templates/footer.php';

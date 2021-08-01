<?php
$pageTitle = '';
if (isset($_TITLE)) {
    $pageTitle = ' - ' . $_TITLE . ' ';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Jacobtread">
    <meta name="keywords" content="minecraft jars, craftbukkit, bukkit, spigot, minecraft bukkit, bukkit server, bukkit download, server jars, jars">
    <meta name="description" content="Download the latest version of Bukkit, Spigot, Paper and many more of the best Minecraft server jars!">
    <meta property="og:title" content="ServerJars"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="https://serverjars.com/"/>
    <meta property="og:description" content="Download the latest version of Bukkit, Spigot, Paper and many more of the best Minecraft server jars!"/>
    <meta property="og:image" content="https://serverjars.com/assets/img/icon.png"/>
    <title>ServerJars <?php echo $pageTitle; ?>- Minecraft server jars.</title>
    <link rel="shortcut icon" href="/assets/img/icon_small.png" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="/assets/js/jquery.cookie.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.min.css?6">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-142405476-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());
        gtag("config", "UA-142405476-1");
    </script>
    <!-- Website Developed by Jacobtread#3770 -->
</head>
<body>
<div class="loader-background">
    <div class="loader">
        <img class="loader-image" src="/assets/img/loader.gif" alt="ServerJars Icon">
        <h1 class="loader-text">Loading...</h1>
    </div>
</div>
<header class="header">
    <div class="header-logo-wrapper">
        <img src="/assets/img/logo_white.svg" class="header-logo" alt="ServerJars Logo Full">
    </div>
    <button id="schemeToggle"><i class="fa fa-sun"></i></button>
    <nav class="nav">
        <a href="/" class="nav-button"><i class="nav-button-icon fa fa-home"></i>Home</a>
        <a href="/updater" class="nav-button"><i class="nav-button-icon fa fa-download"></i>Auto Updater</a>
        <a href="/documentation" class="nav-button"><i class="nav-button-icon fa fa-code"></i>Documentation</a>
        <a href="https://discordapp.com/invite/FP25fg9" target="_blank" class="nav-button"><i class="nav-button-icon fab fa-discord"></i>Discord</a>
    </nav>
</header>
<div class="banner-wrapper">
    <a href="https://www.skynode.pro/minecraft-hosting?utm_source=serverjars&utm_medium=banner" target="_blank">
        <img src="/assets/img/skynode.gif" alt="" class="banner">
    </a>
</div>
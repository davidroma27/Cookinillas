<?php
//file: view/layouts/default.php

$view = ViewManager::getInstance();
$currentuser = $view->getVariable("currentusername");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial scale=1.0">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" type="image/png" href="view/img/favicon.png">

    <script src="index.php?controller=language&amp;action=i18njs"></script>

    <?= $view->getFragment("css") ?>
    <?= $view->getFragment("javascript") ?>

    <title>Cookinillas &mdash; <?= i18n("Acceder") ?></title>
</head>

<body id="access_body">
    <div class="bg-video">
        <video class="bg-video__content" autoplay muted loop>
            <source src="/view/img/video.mp4" type="video/mp4">
            <source src="/view/img/video.webm" type="video/webm">
            Your browser is not supported!
        </video>
    </div>
    <?php
    include(__DIR__."/language_select_element.php");
    ?>
    <main class="access-main">
        <div class="access-container">
            <div class="access__form">
                <div class="access-logo">
                    <a href="index.php?controller=home&action=index">
                        <img src="/view/img/logo.svg" alt="cookinillas logo" class="logo">
                    </a>
                </div>

                <div id="flash">
                    <?= $view->popFlash() ?>
                </div>
                <?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
            </div>

        </div>
    </main>
</body>

</html>

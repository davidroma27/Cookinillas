<?php
    //file: view/layouts/default.php

    $view = ViewManager::getInstance();
    $currentuser = $view->getVariable("currentusername");
    $title = $view->getVariable("title");
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

    <title>Cookinillas &mdash;
        <?php if($title == "Add Recipe"){ ?>
           <?=i18n("Subir receta"); ?>
        <?php } elseif($title == "View Recipe"){ ?>
            <?= i18n("Cocinando para todos");?>
        <?php } elseif($title == "Edit Recipe"){ ?>
            <?=i18n("Editar receta");?>
        <?php } elseif($title == "Search Recipe"){ ?>
            <?=i18n("Buscar receta");?>
        <?php } elseif($title == "User home"){ ?>
            <?=i18n("Mis recetas");?>
        <?php }elseif($title == "Favorites"){ ?>
            <?=i18n("Recetas favoritas");?>
        <?php } else{ ?>
        <?=i18n("Cocinando para todos");?>
        <?php } ?>
    </title>
</head>

<body>
    <div class="container">
        <header class="header">
            <a href="index.php?controller=home&amp;action=index">
                <button class="home__button" title="Home">
                    <img src="/view/img/logo.svg" alt="cookinillas logo" class="logo">
                </button>
            </a>

            <nav class="user-nav">
                <?php if (isset($currentuser)): ?>
                    <a href="index.php?controller=recipes&amp;action=search" title="<?= i18n("Buscar receta") ?>">
                        <div class="user-nav__icon-search">
                            <svg class="search__icon">
                                <use href="/view/img/sprite.svg#icon-magnifying-glass"></use>
                            </svg>
                        </div>
                    </a>
                    <a href="index.php?controller=recipes&amp;action=add" title="<?= i18n("Subir receta") ?>">
                        <div class="user-nav__icon-box">
                            <svg id="upload_icon" class="user-nav__icon">
                                <use href="/view/img/sprite.svg#icon-upload-to-cloud"></use>
                            </svg>
                        </div>
                    </a>
                <?php endif; ?>
                <div class="user-nav__user">
                    <div class="user-nav__user-box">
                        <!--<img src="../img/user.jpg" alt="user photo" class="user-nav__user-photo">-->

                            <?php if (isset($currentuser)): ?>
                               <?= printf(i18n($currentuser))?>
                                <a href="index.php?controller=users&amp;action=logout" title="<?= i18n("Cerrar sesión") ?>">
                                    <svg class="user-nav__icon">
                                        <use href="/view/img/sprite.svg#icon-exit"></use>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <a href="index.php?controller=users&amp;action=login"><?= i18n("Acceder") ?></a>
                            <?php endif ?>
                    </div>
                    <div class="user-nav__user-dropcont">
                        <a href="index.php?controller=users&amp;action=favorites"><?= i18n("Recetas favoritas") ?></a>
                        <a href="index.php?controller=users&amp;action=home"><?= i18n("Mis recetas") ?></a>
                    </div>
                </div>
            </nav>
        </header>

        <div class="content">
            <?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
        </div>


        <footer>
            <p class="footerText">Cookinillas - Copyright (c) 2021 drmartinez</p>
            <?php
                include(__DIR__."/language_select_element.php");
            ?>
        </footer>
    </div>
</body>
</html>


<?php

//file: view/users/favorites.php
require_once(__DIR__ . "/../../core/ViewManager.php");

$view = ViewManager::getInstance();

$recipes = $view->getVariable("recipes");
$currentuser = $view->getVariable("currentusername");
$errors = $view->getVariable("errors");

$next = $view->getVariable("next");
$previous = $view->getVariable("previous");
$page = $view->getVariable("page");

$view->setVariable("title", "Recipe");


?>

<div class="title-bar">
    <div class="title-box">
        <h1 class="title-box__title"><?= i18n("Recetas favoritas") ?></h1>
    </div>
</div>

<main class="main-content">

    <div class="recipes">

        <?php foreach ($recipes as $recipe): ?>
            <div class="recipes__box">
                <a href="index.php?controller=recipes&amp;action=view&amp;id=<?= $recipe->getId() ?>" class="recipes__box-link">
                    <span><img src="media/<?= $recipe->getImg() ?>" alt="<?= $recipe->getTitle() ?>" class="recipes__box-photo"></span>
                </a>
                <h3 class="recipes__box-title">
                    <?= $recipe->getTitle() ?>
                </h3>
                <span class="recipes__box-text"><?= htmlentities($recipe->getSteps()) ?></span>

            </div>
        <?php endforeach; ?>
    </div>


    <div class="pag__box">
        <?php if (isset($previous) || isset($next)){ ?>
            <?php if (isset($previous)){ ?>
                <button class="pag__button" type="button">
                    <a href="index.php?controller=home&action=index&page=<?= $previous ?>" class="pag__box--content" id="sig_pag" >
                        <svg class="pag__box--icon">
                            <use href="view/img/sprite.svg#icon-arrow-left"></use>
                        </svg>
                    </a>
                </button>
            <?php }else{ ?>
                <button class="pag__button" type="button" disabled>
                    <a class="pag__box--content" id="sig_pag" >
                        <svg class="pag__box--icon">
                            <use href="view/img/sprite.svg#icon-arrow-left"></use>
                        </svg>
                    </a>
                </button>
            <?php } ?>

            <a href="index.php?controller=home&action=index&page=<?= $page ?>" class="pag__box--content" id="pag_num">
                <?= i18n("Página") ?> <?= $page ?>
            </a>

            <?php if (isset($next)){ ?>
                <button class="pag__button" type="button">
                    <a href="index.php?controller=home&action=index&page=<?= $next ?>" class="pag__box--content" id="prev_pag">
                        <svg class="pag__box--icon">
                            <use href="view/img/sprite.svg#icon-arrow-right"></use>
                        </svg>
                    </a>
                </button>

            <?php }else{ ?>
                <button class="pag__button" type="button" disabled>
                    <a class="pag__box--content" id="prev_pag">
                        <svg class="pag__box--icon">
                            <use href="view/img/sprite.svg#icon-arrow-right"></use>
                        </svg>
                    </a>
                </button>
            <?php } ?>
        <?php } ?>
    </div>

</main>

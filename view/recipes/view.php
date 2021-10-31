<?php
//file: view/recipes/view.php
require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$recipe = $view->getVariable("recipe");
$currentuser = $view->getVariable("currentusername");
$errors = $view->getVariable("errors");

$view->setVariable("title", "View Recipe");
?>

<div class="title-bar">
    <div class="title-box">
        <h1 class="title-box__title"><?= htmlentities($recipe->getTitle()) ?></h1>
    </div>
</div>

<main class="recipe-main">
    <div class="recipe__img">
        <img src="<?= $recipe->getImg() ?>" alt="">
    </div>
    <div class="recipe__bar">
        <svg class="recipe__bar-time__logo">
            <use href="/view/img/sprite.svg#icon-stopwatch"></use>
        </svg>
        <span class="recipe__bar-time"><?= $recipe->getTime() ?> min</span>

        <svg class="recipe__bar-user__logo">
            <use href="/view/img/sprite.svg#icon-user"></use>
        </svg>
        <span class="recipe__bar-alias"><?= $recipe->getAlias()->getAlias() ?></span>

        <button id="fav_button" class="fav__button">
            <svg class="fav__icon">
                <use href="/view/img/sprite.svg#icon-heart"></use>
            </svg>
            <span class="fav__text"><?= i18n("Me gusta") ?></span>
            <span class="fav__count">(346)</span>
        </button>

        <a href="index.php?controller=recipes&amp;action=edit&amp;id=<?= $recipe->getId() ?>" id="fav_button" class="fav__button">
            <svg class="fav__icon">
                <use href="/view/img/sprite.svg#icon-pencil"></use>
            </svg>
            <span class="fav__text"><?= i18n("Editar") ?></span>
        </a>
    </div>
    <div class="recipe__content">
        <div class="recipe__ing">
            <h3><?= i18n("Listado de ingredientes") ?></h3>
            <ul class="recipe__ing-list">
                <?php
                $arr1 = $recipe->getIngr();
                $arr2 = $recipe->getQuant();

                $res = array_combine($arr1, $arr2);
                ?>
                 <?php foreach ($res as $key => $val): ?>
                    <li> <?php print_r($key) ?>: <?php print_r($val) ?> </li>

                <?php endforeach; ?>
            </ul>
        </div>

        <div class="recipe__steps">
            <h3><?= i18n("Pasos a realizar") ?></h3>
            <ul class="recipe__steps-list">
                <p>
                    <?= htmlentities($recipe->getSteps())?>
                </p>
            </ul>
        </div>
    </div>
</main>
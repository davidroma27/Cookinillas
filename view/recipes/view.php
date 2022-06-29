<?php
//file: view/recipes/view.php
require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$recipe = $view->getVariable("recipe");
$currentuser = $view->getVariable("currentusername");
$errors = $view->getVariable("errors");
$isLike = $view->getVariable("isLike");
$view->setVariable("title", "View Recipe");
?>

<div class="title-bar">
    <div class="title-box">
        <h1 class="title-box__title"><?= htmlentities($recipe->getTitle()) ?></h1>
    </div>
</div>

<main class="recipe-main">
    <div class="recipe__img">
        <img src="media/<?= $recipe->getImg() ?>" alt="">
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

<!--        --><?php //if (isset($currentuser)){
            if($isLike) { //SI TIENE LIKE MUESTRA CORAZON LLENO Y ACTION = DISLIKE?>
                <form id="like__form" method="POST" action="index.php?controller=like&amp;action=dislike">
                    <input type="hidden" name="id" value="<?= $recipe->getId() ?>">
                    <button id="fav_button" class="fav__button" name="submit" type="submit">
                        <svg class="fav__icon">
                            <use href="/view/img/sprite.svg#icon-heart"></use>
                        </svg>
                        <span class="fav__text"><?= i18n("No me gusta") ?></span>
                        <span class="fav__count">(<?= $recipe->getNLikes() ?>)</span>
                    </button>
                </form>
            <?php } else{ //SI NO TIENE LIKE MUESTRA CORAZON VACIO Y ACTION = LIKE?>
                <form id="like__form" method="POST" action="index.php?controller=like&amp;action=like">
                    <input type="hidden" name="id" value="<?= $recipe->getId() ?>">
                    <button id="fav_button" class="fav__button" name="submit" type="submit">
                        <svg class="fav__icon">
                            <use href="/view/img/sprite.svg#icon-heart-outlined"></use>
                        </svg>
                        <span class="fav__text"><?= i18n("Me gusta") ?></span>
                        <span class="fav__count">(<?= $recipe->getNLikes() ?>)</span>
                    </button>
                </form>
            <?php } ?>
                <?php if($currentuser === $recipe->getAlias()->getAlias()){ ?>
                    <a href="index.php?controller=recipes&amp;action=edit&amp;id=<?= $recipe->getId() ?>" id="action_button" class="fav__button" title="<?= i18n("Editar receta") ?>">
                        <svg class="fav__icon">
                            <use href="/view/img/sprite.svg#icon-pencil"></use>
                        </svg>
                    </a>

                    <form method="POST" action="index.php?controller=recipes&amp;action=delete" id="del_recipe_<?= $recipe->getId(); ?>" class="del_recipe">
                        <input type="hidden" name="id" value="<?= $recipe->getId() ?>">
                        <a href="#" id="del_button" class="fav__button" onclick="
                                if (confirm('<?= i18n("¿Seguro que deseas eliminar la receta?")?>')){
                                document.getElementById('del_recipe_<?= $recipe->getId() ?>').submit()}" title="<?= i18n("Borrar receta") ?>">
                            <svg class="fav__icon">
                                <use href="/view/img/sprite.svg#icon-del"></use>
                            </svg>
                        </a>
                    </form>
                <?php } ?>

<!--    --><?php //} ?>

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
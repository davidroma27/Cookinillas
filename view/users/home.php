<?php

//file: view/users/home.php
require_once(__DIR__ . "/../../core/ViewManager.php");

$view = ViewManager::getInstance();

$recipes = $view->getVariable("recipes");
$currentuser = $view->getVariable("currentusername");
$errors = $view->getVariable("errors");

$view->setVariable("title", "User home");

?>

<div class="title-bar">
    <div class="title-box">
        <h1 class="title-box__title"><?= i18n("Mis recetas") ?></h1>
    </div>
</div>
<main class="main-content">

    <div class="recipes">
        <?php if(empty($recipes)){ ?>
            <h1>Aún no has subido ninguna receta</h1>
        <?php } ?>

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


</main>



<?php
//file: view/recipes/edit.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$recipe = $view->getVariable("recipe");
$errors = $view->getVariable("errors");
$ingredients = $view->getVariable("ingredients");

$view->setVariable("title", "Edit Recipe");
var_dump($recipe);

?>

<div class="title-bar">
    <div class="title-box">
        <h1 class="title-box__title"><?= i18n("Editar receta") ?></h1>
</div>
</div>

<main class="main-content">
    <form action="index.php?controller=recipes&amp;action=edit" method="POST" class="recipe__form" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= $recipe->getId() ?>">
        <label>
            <span><?= i18n("Nombre de la receta") ?></span>
            <input type="text" name="title" value="<?= $recipe->getTitle() ?>">
        </label>
        <label class="file-upload">
            <span><?= i18n("Imagen de la receta") ?></span>
            <input id="sel_file" type="file" name="img">
        </label>
        <label>
            <span><?= i18n("Tiempo de preparación (minutos)") ?></span>
            <input type="number" name="time" value="<?= $recipe->getTime() ?>">
        </label>
        <label>
            <span><?= i18n("Ingredientes") ?></span>
            <input list="ingredients" name="ingr" value="<?php foreach ($recipe->getIngr() as $ingr): print_r($ingr); endforeach; ?>">
            <datalist id="ingredients">
                <?php foreach ($ingredients as $ingr): ?>
                    <option value="<?php print_r($ingr) ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </label>
        <label>
            <span><?= i18n("Cantidad") ?></span>
            <input type="text" name="quant" value="<?php
                foreach ($recipe->getQuant() as $quant):
                    print_r($quant);
                endforeach;
            ?>">
        </label>
        <label>
            <span><?= i18n("Pasos para elaborar la receta") ?></span>
            <textarea name="steps" id="recipeTextArea" cols="30" rows="10" ><?= $recipe->getSteps() ?></textarea>
        </label>
        <input class="form__button" name="submit" type="submit" value="<?= i18n("Enviar") ?>">
    </form>
</main>
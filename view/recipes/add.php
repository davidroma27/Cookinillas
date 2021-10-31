<?php
//file: view/recipes/add.php
require_once(__DIR__."/../../core/ViewManager.php");
require_once(__DIR__."/../../controller/LanguageController.php");

$view = ViewManager::getInstance();

$recipe = $view->getVariable("recipe");
$ingredients = $view->getVariable("ingredients");
$errors = $view->getVariable("errors");

$view->setVariable("title", "Add Recipe");

?>

<div class="title-bar">
    <div class="title-box">
        <span class="title-box__title"><?= i18n("Subir receta") ?></span>
    </div>
</div>

<main class="main-content">
    <form action="index.php?controller=recipes&amp;action=add" method="POST" class="recipe__form" enctype="multipart/form-data">

        <label>
            <span><?= i18n("Nombre de la receta") ?></span>
            <input type="text" name="title">
        </label>
        <label class="file-upload">
            <span><?= i18n("Imagen de la receta") ?></span>
            <input id="sel_file" type="file" name="img">
        </label>
        <label>
            <span><?= i18n("Tiempo de preparación (minutos)") ?></span>
            <input type="number" name="time">
        </label>
        <label>
            <span><?= i18n("Ingredientes") ?></span>
            <input list="ingredients" name="ingr">
            <datalist id="ingredients">
                <?php foreach ($ingredients as $ingr): ?>
                    <option value="<?php print_r($ingr) ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </label>
        <label>
            <span><?= i18n("Cantidad") ?></span>
            <input type="text" name="quant">
        </label>
        <label>
            <span><?= i18n("Pasos para elaborar la receta") ?></span>
            <textarea name="steps" id="recipeTextArea" cols="30" rows="10"></textarea>
        </label>
        <input class="form__button" name="submit" type="submit" value="<?= i18n("Enviar") ?>">
    </form>
</main>
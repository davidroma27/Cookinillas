<?php
//file: view/recipes/add.php
require_once(__DIR__."/../../core/ViewManager.php");
require_once(__DIR__."/../../controller/LanguageController.php");

$view = ViewManager::getInstance();

//$recipe = $view->getVariable("recipe");
$ingredients = $view->getVariable("ingredients");
$errors = $view->getVariable("errors");

$view->setVariable("title", "Search Recipe");

?>

<div class="title-bar">
    <div class="title-box">
        <h1 class="title-box__title"><?= i18n("Buscar receta") ?></h1>
    </div>
</div>
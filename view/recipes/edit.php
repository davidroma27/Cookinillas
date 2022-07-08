<?php
//file: view/recipes/edit.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$recipe = $view->getVariable("recipe");
$ingredients = $view->getVariable("ingredients");

$errors = $view->getVariable("errors");

$view->setVariable("title", "Edit Recipe");

?>

<div class="title-bar">
    <div class="title-box">
        <h1 class="title-box__title"><?= i18n("Editar receta") ?></h1>
    </div>
</div>

<main class="main-content">
    <div class="form__box--edit">
        <form action="index.php?controller=recipes&amp;action=edit" method="POST" class="recipe__form--edit" enctype="multipart/form-data"
        name="Recipe" onsubmit="return checkEmptyForm()">

            <input type="hidden" name="id" value="<?= $recipe->getId() ?>">
            <label>
                <span><?= i18n("Nombre de la receta") ?></span>
                <input type="text" name="title" value="<?= $recipe->getTitle() ?>">
            </label>
            <label>
                <span><?= i18n("Imagen actual") ?></span>
                <img src="/media/<?= $recipe->getImg() ?>" alt="" width="150"">
            </label>
            <label class="file-upload">
                <span><?= i18n("Imagen de la receta") ?></span>
                <input id="sel_file" type="file" name="img" value="<?= $recipe->getImg() ?>"
            </label>
            <label>
                <span><?= i18n("Tiempo de preparación (minutos)") ?></span>
                <input type="number" name="time" value="<?= $recipe->getTime() ?>">
            </label>
            <div class="ingr-input">
                <label class="ingrLabel">
                    <span><?= i18n("Ingredientes") ?></span>
                    <?php foreach ($recipe->getIngr() as $ingr): ?>
                        <input class="inputIngr" list="ingredients" name="ingredientes[]" value="<?= $ingr ?>">
                    <?php endforeach; ?>
                    <datalist id="ingredients">
                        <?php foreach ($ingredients as $ingr): ?>
                            <option value="<?php print_r($ingr) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </label>
                <label class="cantLabel">
                    <span><?= i18n("Cantidad") ?></span>
                    <?php foreach ($recipe->getQuant() as $quant): ?>
                    <input class="inputCant" type="text" name="quant[]" value="<?= $quant ?>">
                    <?php endforeach; ?>
                </label>
                <button class="ingr-input__button" onclick="addInput()" type="button" title="<?= i18n("Añadir ingrediente") ?>">
                    <svg class="ingr-input__button--icon">
                        <use href="view/img/sprite.svg#icon-add-ingr"></use>
                    </svg>
                </button>
            </div>
            <label>
                <span><?= i18n("Pasos para elaborar la receta") ?></span>
                <textarea name="steps" id="recipeTextArea" cols="30" rows="10" ><?= $recipe->getSteps() ?></textarea>
            </label>
            <input class="form__button" name="submit" type="submit" value="<?= i18n("Enviar") ?>">
        </form>

    </div>

</main>

<script type="text/javascript">
    function addInput(){
        let ingrParent = document.querySelector(".ingrLabel");
        let cantParent = document.querySelector(".cantLabel");

        let ingrInput = ingrParent.querySelector(".inputIngr");
        let cantInput = cantParent.querySelector(".inputCant");

        /******* INGREDIENTS INPUT FIELD POPULATION ******/
        let newIngr = document.createElement('input');
        newIngr.type = 'text';
        newIngr.className = "inputIngr";
        newIngr.name = "ingredientes[]";
        newIngr.setAttribute("list","ingredients");

        let newDL = document.createElement('datalist');
        newDL.id= "ingredients";

        //Parse php ingredients to json and generate option values
        let json = <?php echo json_encode($ingredients); ?>;
        for (const ingr in json) {
            var option = document.createElement('option');
            option.value = json[ingr];
        }

        /******* CANTIDAD INPUT FIELD POPULATION ******/
        let newCant = document.createElement('input');
        newCant.type = 'text';
        newCant.className = "inputCant";
        newCant.name = "quant[]";
        //Creating elements in DOM
        ingrParent.appendChild(newIngr);
        newDL.appendChild(option);
        ingrParent.appendChild(newDL);
        cantParent.appendChild(newCant);
    }

    function checkEmptyForm(){
        let titleInput = document.forms["Recipe"]["title"].value;
        let timeInput = document.forms["Recipe"]["time"].value;
        let stepsInput = document.forms["Recipe"]["steps"].value;

        if(titleInput === "" || timeInput === "" || stepsInput === "") {
            alert("Todos los campos deben estar cubiertos");
            return false;
        }
    }
</script>
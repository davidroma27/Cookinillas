<?php
//file: view/recipes/search.php
require_once(__DIR__."/../../core/ViewManager.php");
require_once(__DIR__."/../../controller/LanguageController.php");

$view = ViewManager::getInstance();

$recipes = $view->getVariable("recipes");
$ingredients = $view->getVariable("ingredients");


$errors = $view->getVariable("errors");

$view->setVariable("title", "Search Recipe");
?>

<div class="title-bar">
    <div class="title-box">
        <h1 class="title-box__title"><?= i18n("Buscar por ingredientes") ?></h1>
    </div>
</div>

<main class="main-content">

    <div class="form__box">
        <form action="index.php?controller=recipes&amp;action=search" method="POST" class="recipe__form--search">

            <div class="ingr-input">
                <label class="ingrLabel">
                    <span><?= i18n("Ingredientes") ?></span>
                    <input class="inputIngr" list="ingredients" name="ingredientes[]">
                    <datalist id="ingredients">
                        <?php foreach ($ingredients as $ingr): ?>
                            <option value="<?php print_r($ingr) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </label>
                <button class="ingr-input__button" onclick="addInput()" type="button" title="<?= i18n("Añadir ingrediente") ?>">
                    <svg class="ingr-input__button--icon">
                        <use href="view/img/sprite.svg#icon-add-ingr"></use>
                    </svg>
                </button>
            </div>

            <input class="form__button--search" name="submit" type="submit" value="<?= i18n("Buscar") ?>">
        </form>
    </div>

    <div class="recipes">
        <?php if($recipes != null){ ?>
            <?php foreach ($recipes as $recipe):?>
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
        <?php }elseif($_POST != null){ ?>
                <h1>No existen recetas con esos ingredientes</h1>
        <?php } ?>
    </div>

</main>

<script>
    function addInput(){
        let ingrParent = document.querySelector(".ingrLabel");

        /******* INGREDIENTS INPUT FIELD POPULATION ******/
        let newIngr = document.createElement('input');
        newIngr.type = 'text';
        newIngr.className = "inputIngr";
        newIngr.name = "ingredientes[]";
        newIngr.setAttribute("list","ingredients");

        let newDL = document.createElement('datalist');
        newDL.id= "ingredients";

        let json = <?php echo json_encode($ingredients); ?>;
        //<input class="inputIngr" list="ingredients" name="ingr[]">

        for (const ingr in json) {
            var option = document.createElement('option');
            option.value = json[ingr];
        }

        //Creating elements in DOM
        ingrParent.appendChild(newIngr);
        newDL.appendChild(option);
        ingrParent.appendChild(newDL);
    }
</script>
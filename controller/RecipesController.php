<?php
    //file: controller/RecipeController.php

    require_once(__DIR__."/../model/Recipe.php");
    require_once(__DIR__."/../model/RecipeMapper.php");
    require_once(__DIR__."/../model/User.php");
    require_once(__DIR__."/../model/LikeMapper.php");

    require_once(__DIR__."/../core/ViewManager.php");
    require_once(__DIR__."/../controller/BaseController.php");


/**
 * Class RecipesController
 *
 * Controller to make a CRUDL of Recipes entities
 *
 * @author drmartinez
 */
    class RecipesController extends BaseController{

        /**
         * Reference to the RecipeMapper to interact
         * with the database
         *
         * @var RecipeMapper
         */
        private $recipeMapper;
        private $likeMapper;

        public function __construct() {
            parent::__construct();

            $this->recipeMapper = new RecipeMapper();
            $this->likeMapper = new LikeMapper();
        }

        /**
         * Action to view a given recipe
         *
         * This action should only be called via GET
         *
         * The expected HTTP parameters are:
         * <ul>
         * <li>id: Id of the recipe (via HTTP GET)</li>
         * </ul>
         *
         * The views are:
         * <ul>
         * <li>recipes/view: If recipe is successfully loaded (via include). Includes these view variables:</li>
         * <ul>
         *	<li>recipe: The current recipe retrieved</li>
         * </ul>
         * </ul>
         *
         * @throws Exception If no such recipe of the given id is found
         * @return void
         *
         */
        public function view(){
            if (!isset($_GET["id"])) {
                throw new Exception("id is mandatory");
            }

            $recipeid = $_GET["id"];

            // find the recipe object in the database
            $recipe = $this->recipeMapper->findById($recipeid);
            if ($recipe == NULL) {
                throw new Exception("no such post with id: ".$recipeid);
            }

            // put the recipe object to the view
            $this->view->setVariable("recipe", $recipe);

            if (isset($this->currentUser)) {
                $isLike = $this->likeMapper->isLike($_SESSION["currentuser"], $_GET["id"]);
                $this->view->setVariable("isLike", $isLike);
            }

            // render the view (/view/recipes/view.php)
            $this->view->render("recipes", "view");

        }

        /**
         * Action to add a new recipe
         *
         * When called via GET, it shows the add form
         * When called via POST, it adds the recipe to the
         * database
         *
         * The expected HTTP parameters are:
         * <ul>
         * <li>title: Title of the post (via HTTP POST)</li>
         * <li>content: Content of the post (via HTTP POST)</li>
         * </ul>
         *
         * The views are:
         * <ul>
         * <li>recipes/add: If this action is reached via HTTP GET (via include)</li>
         * <li>recipes/index: If recipe was successfully added (via redirect)</li>
         * <li>recipes/add: If validation fails (via include). Includes these view variables:</li>
         * <ul>
         *	<li>recipe: The current Recipe instance, empty or
         *	being added (but not validated)</li>
         *	<li>errors: Array including per-field validation errors</li>
         * </ul>
         * </ul>
         * @throws Exception if no user is in session
         * @return void
         */
        public function add() {
            if (!isset($this->currentUser)) {
                throw new Exception("Not in session. Adding recipes requires login");
            }

            $recipe = new Recipe();

            if (isset($_POST["submit"])) { // reaching via HTTP Post...

                try {
                    if(!empty($_FILES["img"]["name"])) { //Manage the file upload
                        $uploadImage = $this->recipeMapper->uploadImg();

                        $recipe->setImg($uploadImage["fileName"]);
                    }
                    // populate the Recipe object with data from the form
                    $recipe->setTitle($_POST["title"]);
                    $recipe->setTime($_POST["time"]);
                    $recipe->setIngr($_POST["ingr"]);
                    $recipe->setQuant($_POST["quant"]);
                    $recipe->setSteps($_POST["steps"]);
                    // The user of the Recipe is the currentUser (user in session)
                    $recipe->setAlias($this->currentUser);

                    // validate Recipe object
                    $recipe->checkIsValidForCreate(); // if it fails, ValidationException

                }catch(ValidationException $ex) {
                    if(isset($recipe)){
                        unlink(__DIR__."../../../../img/".$recipe->getImg());
                    }
                    // Get the errors array inside the exepction...
                    $errors = $ex->getErrors();
                    // And put it to the view as "errors" variable
                    $this->view->setVariable("errors", $errors);
                }


                if(empty($errors)){
                    // save the Recipe object into the database
                    $id = $this->recipeMapper->save($recipe);


                    /***** AQUI OBTENDREMOS LOS INGREDIENTES CON UN FOREACH Y SE GUARDARAN
                     * CADA UNO EN LA TABLA INGREDIENTES ******/


                    // POST-REDIRECT-GET
                    // Everything OK, we will redirect the user to the list of recipes
                    // We want to see a message after redirection, so we establish
                    // a "flash" message (which is simply a Session variable) to be
                    // get in the view after redirection.
                    $this->view->setFlash(sprintf(i18n("Recipe \"%s\" successfully added."),$recipe ->getTitle()));

                    // perform the redirection. More or less:
                    // header("Location: index.php?controller=recipes&action=index")
                    // die();
                    $queryString = "id=" . $id;
                    $this->view->redirect("recipe", "view", $queryString);

                }else{
                    if (isset($this->currentUser)) {
                        $idLikes = $this->likeMapper->findByUsername($_SESSION["currentuser"]);
                        $this->view->setVariable("idLikes", $idLikes);
                    }

                    $nRecipes = $this->recipeMapper->countRecipes();
                    $nPags = ceil($nRecipes / 6);
                    $recipe = $this->recipeMapper->findAll(0);
                    if($nPags > 1){
                        $this->view->setVariable("next", 1);
                    }
                    $this->view->setVariable("page", 0);

                    //Retrieve all available ingredients of database
                    $ingredients = $this->recipeMapper->getIngredients();
                    $this->view->setVariable("ingredients", $ingredients);

                    // Put the Recipes object visible to the view
                    //$this->view->setVariable("recipe", $recipe);

                    // render the view (/view/recipes/add.php)
                    //$this->view->render("recipes", "add");
                }

            }

            // Put the Recipes object visible to the view
            $this->view->setVariable("recipe", $recipe);

            // render the view (/view/recipes/add.php)
            $this->view->render("recipes", "add");

        }

        /**
         * Action to edit a recipe
         *
         * When called via GET, it shows an edit form
         * including the current data of the Recipe.
         * When called via POST, it modifies the recipe in the
         * database.
         *
         * The expected HTTP parameters are:
         * <ul>
         * <li>id: Id of the recipe (via HTTP POST and GET)</li>
         * <li>title: Title of the recipe (via HTTP POST)</li>
         * <li>img: image of the recipe (via HTTP POST)</li>
         * <li>time: time of the recipe (via HTTP POST)</li>
         * <li>ingr: ingredients of the recipe (via HTTP POST)</li>
         * <li>quant: quantity of each ingredient (via HTTP POST)</li>
         * <li>steps: steps of the recipe (via HTTP POST)</li>
         * </ul>
         *
         * The views are:
         * <ul>
         * <li>recipes/edit: If this action is reached via HTTP GET (via include)</li>
         * <li>recipes/index: If recipe was successfully edited (via redirect)</li>
         * <li>recipes/edit: If validation fails (via include). Includes these view variables:</li>
         * <ul>
         *	<li>recipe: The current Recipe instance, empty or being added (but not validated)</li>
         *	<li>errors: Array including per-field validation errors</li>
         * </ul>
         * </ul>
         * @throws Exception if no id was provided
         * @throws Exception if no user is in session
         * @throws Exception if there is not any recipe with the provided id
         * @throws Exception if the current logged user is not the author of the recipe
         * @return void
         */
        public function edit() {

            if (!isset($_REQUEST["id"])) {
                throw new Exception("A recipe id is mandatory");
            }

            if (!isset($this->currentUser)) {
                throw new Exception("Not in session. Editing recipes requires login");
            }

            // Get the Recipe object from the database
            $recipeid = $_REQUEST["id"];
            $recipe = $this->recipeMapper->findById($recipeid);

            // Does the recipe exist?
            if ($recipe == NULL) {
                throw new Exception("no such recipe with id: ".$recipeid);
            }

            // Check if the recipe author is the currentUser (in Session)
            if ($recipe->getAlias() != $this->currentUser) {
                throw new Exception("logged user is not the author of the recipe id ".$recipeid);
            }

            if (isset($_POST["submit"])) { // reaching via HTTP Post...
                if(!empty($_FILES["img"]["name"])) { //Manage the file upload

                    //Get file info
                    $fileName = $_FILES["img"]["name"];
                    $path = "view/img/".$fileName;

                    $recipe->setImg($path);
                }

                // populate the Recipe object with data form the form
                $recipe->setTitle($_POST["title"]);
                $recipe->setTime($_POST["time"]);
                $recipe->setIngr($_POST["ingr"]);
                $recipe->setQuant($_POST["quant"]);
                $recipe->setSteps($_POST["steps"]);

                try {
                    // validate Recipe object
                    $recipe->checkIsValidForUpdate(); // if it fails, ValidationException

                    // update the Recipe object in the database
                    $this->recipeMapper->update($recipe);

                    // POST-REDIRECT-GET
                    // Everything OK, we will redirect the user to the list of posts
                    // We want to see a message after redirection, so we establish
                    // a "flash" message (which is simply a Session variable) to be
                    // get in the view after redirection.
                    $this->view->setFlash(sprintf(i18n("Recipe \"%s\" successfully updated."),$recipe ->getTitle()));

                    $this->view->redirect("recipes", "index");

                }catch(ValidationException $ex) {
                    // Get the errors array inside the exepction...
                    $errors = $ex->getErrors();
                    // And put it to the view as "errors" variable
                    $this->view->setVariable("errors", $errors);
                }
            }

            //Retrieve all available ingredients of database
            $ingredients = $this->recipeMapper->getIngredients();
            $this->view->setVariable("ingredients", $ingredients);

            // Put the Recipe object visible to the view
            $this->view->setVariable("recipe", $recipe);

            // render the view (/view/recipes/edit.php)
            $this->view->render("recipes", "edit");
        }

        /**
         * Action to delete a recipe
         *
         * This action should only be called via HTTP POST
         *
         * The expected HTTP parameters are:
         * <ul>
         * <li>id: Id of the post (via HTTP POST)</li>
         * </ul>
         *
         * The views are:
         * <ul>
         * <li>recipe/index: If recipe was successfully deleted (via redirect)</li>
         * </ul>
         * @throws Exception if no id was provided
         * @throws Exception if no user is in session
         * @throws Exception if there is not any recipe with the provided id
         * @throws Exception if the author of the recipe to be deleted is not the current user
         * @return void
         */
        public function delete() {
            if (!isset($_POST["id"])) {
                throw new Exception("id is mandatory");
            }
            if (!isset($this->currentUser)) {
                throw new Exception("Not in session. Editing recipes requires login");
            }

            // Get the recipe object from the database
            $recipeid = $_REQUEST["id"];
            $recipe = $this->recipeMapper->findById($recipeid);

            // Does the post exist?
            if ($recipe == NULL) {
                throw new Exception("no such post with id: ".$recipeid);
            }

            // Check if the Recipe author is the currentUser (in Session)
            if ($recipe->getAlias() != $this->currentUser) {
                throw new Exception("Recipe author is not the logged user");
            }

            // Delete the Recipe object from the database
            $this->recipeMapper->delete($recipe);

            // POST-REDIRECT-GET
            // Everything OK, we will redirect the user to the list of recipes
            // We want to see a message after redirection, so we establish
            // a "flash" message (which is simply a Session variable) to be
            // get in the view after redirection.
            $this->view->setFlash(sprintf(i18n("Post \"%s\" successfully deleted."),$recipe ->getTitle()));

            // perform the redirection. More or less:
            // header("Location: index.php?controller=recipes&action=index")
            // die();
            $this->view->redirect("home", "index");

        }

    }
<!--
TODO:
    -Crear los models y sus mappers para importarlos:
        +User.php
        +Recipe.php
        +RecipeMapper.php
-->

<?php
    //file: controller/RecipeController.php

    require_once(__DIR__."/../model/Recipe.php");
    require_once(__DIR__."/../model/RecipeMapper.php");
    require_once(__DIR__."/../model/User.php");

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

        public function __construct() {
            parent::__construct();

            $this->recipeMapper = new RecipeMapper();
        }

        /**
         * Action to list recipes
         *
         * Loads all the recipes from the database.
         * No HTTP parameters are needed.
         *
         * The views are:
         * <ul>
         * <li>recipes/index (via include)</li>
         * </ul>
         */
        public function index() {

            // obtain the data from the database
            $recipes = $this->recipeMapper->findAll();

            // put the array containing Recipe object to the view
            $this->view->setVariable("recipes", $recipes);

            // render the view (/view/recipes/index.php)
            $this->view->render("recipes", "index");
        }

        /**
         * Action to view a given recipe
         *
         * This action should only be called via GET
         *
         * The expected HTTP parameters are:
         * <ul>
         * <li>id: Id of the post (via HTTP GET)</li>
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

            // check if comment is already on the view (for example as flash variable)
            // if not, put an empty Comment for the view
            //$comment = $this->view->getVariable("comment");
            //$this->view->setVariable("comment", ($comment==NULL)?new Comment():$comment);

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

                if(!empty($_FILES["img"]["name"])) { //Manage the file upload

                    //Get file info
                    $fileName = $_FILES["img"]["name"];
                    $path = "view/img/".$fileName;

                    $recipe->setImg($path);
                }
                // populate the Recipe object with data from the form
                $recipe->setTitle($_POST["title"]);
                $recipe->setTime($_POST["time"]);
                $recipe->setIngr($_POST["ingr"]);
                $recipe->setQuant($_POST["quant"]);
                $recipe->setSteps($_POST["steps"]);
                // The user of the Recipe is the currentUser (user in session)
                $recipe->setAlias($this->currentUser);
                try {
                    // validate Recipe object
                    $recipe->checkIsValidForCreate(); // if it fails, ValidationException

                    // save the Recipe object into the database
                    $this->recipeMapper->save($recipe);

                    // POST-REDIRECT-GET
                    // Everything OK, we will redirect the user to the list of recipes
                    // We want to see a message after redirection, so we establish
                    // a "flash" message (which is simply a Session variable) to be
                    // get in the view after redirection.
                    $this->view->setFlash(sprintf(i18n("Recipe \"%s\" successfully added."),$recipe ->getTitle()));

                    // perform the redirection. More or less:
                    // header("Location: index.php?controller=recipes&action=index")
                    // die();
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

            // Put the Recipes object visible to the view
            $this->view->setVariable("recipe", $recipe);

            // render the view (/view/recipes/add.php)
            $this->view->render("recipes", "add");

        }

        /**
         * Action to edit a post
         *
         * When called via GET, it shows an edit form
         * including the current data of the Post.
         * When called via POST, it modifies the post in the
         * database.
         *
         * The expected HTTP parameters are:
         * <ul>
         * <li>id: Id of the post (via HTTP POST and GET)</li>
         * <li>title: Title of the post (via HTTP POST)</li>
         * <li>content: Content of the post (via HTTP POST)</li>
         * </ul>
         *
         * The views are:
         * <ul>
         * <li>posts/edit: If this action is reached via HTTP GET (via include)</li>
         * <li>posts/index: If post was successfully edited (via redirect)</li>
         * <li>posts/edit: If validation fails (via include). Includes these view variables:</li>
         * <ul>
         *	<li>post: The current Post instance, empty or being added (but not validated)</li>
         *	<li>errors: Array including per-field validation errors</li>
         * </ul>
         * </ul>
         * @throws Exception if no id was provided
         * @throws Exception if no user is in session
         * @throws Exception if there is not any post with the provided id
         * @throws Exception if the current logged user is not the author of the post
         * @return void
         */
        public function edit() {
            if (!isset($_REQUEST["id"])) {
                throw new Exception("A post id is mandatory");
            }

            if (!isset($this->currentUser)) {
                throw new Exception("Not in session. Editing posts requires login");
            }


            // Get the Post object from the database
            $postid = $_REQUEST["id"];
            $post = $this->recipeMapper->findById($postid);

            // Does the post exist?
            if ($post == NULL) {
                throw new Exception("no such post with id: ".$postid);
            }

            // Check if the Post author is the currentUser (in Session)
            if ($post->getAuthor() != $this->currentUser) {
                throw new Exception("logged user is not the author of the post id ".$postid);
            }

            if (isset($_POST["submit"])) { // reaching via HTTP Post...

                // populate the Post object with data form the form
                $post->setTitle($_POST["title"]);
                $post->setContent($_POST["content"]);

                try {
                    // validate Post object
                    $post->checkIsValidForUpdate(); // if it fails, ValidationException

                    // update the Post object in the database
                    $this->recipeMapper->update($post);

                    // POST-REDIRECT-GET
                    // Everything OK, we will redirect the user to the list of posts
                    // We want to see a message after redirection, so we establish
                    // a "flash" message (which is simply a Session variable) to be
                    // get in the view after redirection.
                    $this->view->setFlash(sprintf(i18n("Post \"%s\" successfully updated."),$post ->getTitle()));

                    // perform the redirection. More or less:
                    // header("Location: index.php?controller=posts&action=index")
                    // die();
                    $this->view->redirect("posts", "index");

                }catch(ValidationException $ex) {
                    // Get the errors array inside the exepction...
                    $errors = $ex->getErrors();
                    // And put it to the view as "errors" variable
                    $this->view->setVariable("errors", $errors);
                }
            }

            // Put the Post object visible to the view
            $this->view->setVariable("post", $post);

            // render the view (/view/posts/add.php)
            $this->view->render("posts", "edit");
        }

        /**
         * Action to delete a post
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
         * <li>posts/index: If post was successfully deleted (via redirect)</li>
         * </ul>
         * @throws Exception if no id was provided
         * @throws Exception if no user is in session
         * @throws Exception if there is not any post with the provided id
         * @throws Exception if the author of the post to be deleted is not the current user
         * @return void
         */
        public function delete() {
            if (!isset($_POST["id"])) {
                throw new Exception("id is mandatory");
            }
            if (!isset($this->currentUser)) {
                throw new Exception("Not in session. Editing posts requires login");
            }

            // Get the Post object from the database
            $postid = $_REQUEST["id"];
            $post = $this->recipeMapper->findById($postid);

            // Does the post exist?
            if ($post == NULL) {
                throw new Exception("no such post with id: ".$postid);
            }

            // Check if the Post author is the currentUser (in Session)
            if ($post->getAuthor() != $this->currentUser) {
                throw new Exception("Post author is not the logged user");
            }

            // Delete the Post object from the database
            $this->recipeMapper->delete($post);

            // POST-REDIRECT-GET
            // Everything OK, we will redirect the user to the list of posts
            // We want to see a message after redirection, so we establish
            // a "flash" message (which is simply a Session variable) to be
            // get in the view after redirection.
            $this->view->setFlash(sprintf(i18n("Post \"%s\" successfully deleted."),$post ->getTitle()));

            // perform the redirection. More or less:
            // header("Location: index.php?controller=posts&action=index")
            // die();
            $this->view->redirect("posts", "index");

        }

    }
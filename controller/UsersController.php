<?php

require_once(__DIR__."/../core/ViewManager.php");
require_once(__DIR__."/../core/I18n.php");

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/Recipe.php");
require_once(__DIR__."/../model/RecipeMapper.php");

require_once(__DIR__."/../controller/BaseController.php");

/**
 * Class UsersController
 *
 * Controller to login, logout and user registration
 *
 * @author drmartinez
 */
class UsersController extends BaseController {

    /**
     * Reference to the UserMapper to interact
     * with the database
     *
     * @var UserMapper
     */
    private $userMapper;
    private $recipeMapper;

    public function __construct() {
        parent::__construct();

        $this->userMapper = new UserMapper();
        $this->recipeMapper = new RecipeMapper();

        // Users controller operates in a "welcome" layout
        // different to the "default" layout where the internal
        // menu is displayed
        //$this->view->setLayout("welcome");
    }

    /**
     * Action to login
     *
     * Logins a user checking its credentials against
     * the database
     *
     * When called via GET, it shows the login form
     * When called via POST, it tries to login
     *
     * The expected HTTP parameters are:
     * <ul>
     * <li>login: The username (via HTTP POST)</li>
     * <li>passwd: The password (via HTTP POST)</li>
     * </ul>
     *
     * The views are:
     * <ul>
     * <li>users/access: If this action is reached via HTTP GET (via include)</li>
     * <li>recipes/index: If login succeeds (via redirect)</li>
     * <li>users/access: If validation fails (via include). Includes these view variables:</li>
     * <ul>
     *	<li>errors: Array including validation errors</li>
     * </ul>
     * </ul>
     *
     * @return void
     */
    public function login() {
        if (isset($_POST["alias"])){ // reaching via HTTP Post...
            //process login form
            if ($this->userMapper->isValidUser($_POST["alias"], $_POST["passwd"])) {

                $_SESSION["currentuser"]=$_POST["alias"];

                // send user to the restricted area (HTTP 302 code)
                $this->view->redirect("home", "index");

            }else{
                $errors = array();
                $errors["general"] = "Username is not valid";
                $this->view->setVariable("errors", $errors);
            }
        }
        $this->view->setLayout("welcome");
        // render the view (/view/users/access.php)
        $this->view->render("users", "access");
    }

    /**
     * Action to register
     *
     * When called via GET, it shows the register form.
     * When called via POST, it tries to add the user
     * to the database.
     *
     * The expected HTTP parameters are:
     * <ul>
     * <li>login: The username (via HTTP POST)</li>
     * <li>passwd: The password (via HTTP POST)</li>
     * <li>email: The email (via HTTP POST)</li>
     * </ul>
     *
     * The views are:
     * <ul>
     * <li>users/access: If this action is reached via HTTP GET (via include)</li>
     * <li>users/access: If login succeeds (via redirect)</li>
     * <li>users/access: If validation fails (via include). Includes these view variables:</li>
     * <ul>
     *	<li>user: The current User instance, empty or being added
     *	(but not validated)</li>
     *	<li>errors: Array including validation errors</li>
     * </ul>
     * </ul>
     *
     * @return void
     */
    public function register() {

        $user = new User();

        if (isset($_POST["alias"])){ // reaching via HTTP Post...

            // populate the User object with data from the form
            $user->setAlias($_POST["alias"]);
            $user->setPassword($_POST["passwd"]);
            $user->setEmail($_POST["email"]);

            try{
                $user->checkIsValidForRegister(); // if it fails, ValidationException

                // check if user or email exists in the database
                if (!$this->userMapper->usernameExists($_POST["alias"]) AND
                    !$this->userMapper->emailExists($_POST["email"])){

                    // save the User object into the database
                    $this->userMapper->save($user);

                    // POST-REDIRECT-GET
                    // Everything OK, we will redirect the user to the list of recipes
                    // We want to see a message after redirection, so we establish
                    // a "flash" message (which is simply a Session variable) to be
                    // get in the view after redirection.
                    $this->view->setFlash("Username ".$user->getAlias()." successfully added. Please login now");

                    // perform the redirection. More or less:
                    // header("Location: index.php?controller=users&action=login")
                    // die();
                    $this->view->redirect("users", "login");
                } else {
                    $errors = array();
                    $errors["username"] = "Username or email already exists";
                    $this->view->setVariable("errors", $errors);
                }
            }catch(ValidationException $ex) {
                // Get the errors array inside the exception...
                $errors = $ex->getErrors();
                // And put it to the view as "errors" variable
                $this->view->setVariable("errors", $errors);
            }
        }

        $this->view->setLayout("welcome");
        // Put the User object visible to the view
        $this->view->setVariable("user", $user);

        // render the view (/view/users/access.php)
        $this->view->render("users", "access");

    }

    /**
     * Retrieve all recipes of the logged user and show them in private home
     */
    public function home(){
        if (!isset($_SESSION["currentuser"])) {
            $this->view->redirect("home", "index");
        }

        //$user = $this->userMapper->findByUsername($_SESSION["currentuser"]);
        $nUserRecipes = $this->recipeMapper->countRecipesByAlias($_SESSION["currentuser"]);

        $userRecipes = $this->recipeMapper->findByUsername($_SESSION["currentuser"]);

        // put the array containing recipes object to the view
        $this->view->setVariable("recipes", $userRecipes);

        // render the view (/view/users/home.php)
        $this->view->render("users", "home");
    }

    /**
     * Retrieves all liked recipes of the logged user and show them
     */
    public function favorites(){
        if (!isset($_SESSION["currentuser"])) {
            $this->view->redirect("home", "index");
        }

        $nLikedRecipes = $this->recipeMapper->countLikedRecipes($_SESSION["currentuser"]);

        $likedRecipes = $this->recipeMapper->findLikedRecipes($_SESSION["currentuser"]);

        if($nLikedRecipes > 0){
            // put the array containing recipes object to the view
            $this->view->setVariable("recipes", $likedRecipes);

            // render the view (/view/users/favorites.php)
            $this->view->render("users", "favorites");

        }else{
            $this->view->redirect("home", "index");
        }

    }

    /**
     * Action to logout
     *
     * This action should be called via GET
     *
     * No HTTP parameters are needed.
     *
     * The views are:
     * <ul>
     * <li>users/access (via redirect)</li>
     * </ul>
     *
     * @return void
     */
    public function logout() {
        session_destroy();

        // perform a redirection. More or less:
        // header("Location: index.php?controller=users&action=access")
        // die();
        $this->view->redirect("home", "index");

    }

}
<?php
//file: controller/HomeController.php

require_once(__DIR__ . "/../model/Recipe.php");

require_once(__DIR__ . "/../model/RecipeMapper.php");
require_once(__DIR__ . "/../model/UserMapper.php");

require_once(__DIR__ . "/../core/ViewManager.php");
require_once(__DIR__ . "/../controller/BaseController.php");

class HomeController extends BaseController{

    private $recipeMapper;
    private $likeMapper;
    private $userMapper;
    private $ingredientMapper;

    public function __construct() {
        parent::__construct();

        $this->recipeMapper = new RecipeMapper();
        $this->likeMapper = new LikeMapper();
        $this->userMapper = new UserMapper();
        $this->ingredientMapper = new IngredientMapper();
    }

    public function index() {
        $nRecipes = $this->recipeMapper->countRecipes();
        $nPags = ceil($nRecipes / 6);

        $page = 0;

        if(isset($_GET["page"])){
            if (preg_match('/^[0-9]+$/', $_GET["page"]) && ($temp = (int)$_GET["page"]) < $nPags) {
                $page = $temp;
            } else {
                $this->view->redirect("home", "index");
            }
        }

        $recipes = $this->recipeMapper->findAll($page);

        if ($nPags > 1) {
            $prevPage = $page - 1;
            $nextPage = $page + 1;
            if ($page == 0) {
                $this->view->setVariable("next", $nextPage);
            } elseif ($page == ($nPags - 1)) {
                $this->view->setVariable("previous", $prevPage);
            } else {
                $this->view->setVariable("next", $nextPage);
                $this->view->setVariable("previous", $prevPage);
            }
        }
        $this->view->setVariable("page", $page);
        $this->view->setVariable("recipes", $recipes);

        $this->view->render("home", "index");
    }
}
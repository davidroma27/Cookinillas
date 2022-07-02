<?php
//file: controller/HomeController.php

require_once(__DIR__ . "/../model/Recipe.php");

require_once(__DIR__ . "/../model/RecipeMapper.php");

require_once(__DIR__ . "/../core/ViewManager.php");
require_once(__DIR__ . "/../controller/BaseController.php");

class HomeController extends BaseController{

    private $recipeMapper;

    public function __construct() {
        parent::__construct();

        $this->recipeMapper = new RecipeMapper();
    }

    public function index() {

        $recipes = $this->recipeMapper->findAll();

        $this->view->setVariable("recipes", $recipes);

        $this->view->render("home", "index");
    }
}
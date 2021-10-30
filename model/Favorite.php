<?php
// file: model/Favorite.php

require_once(__DIR__."/../core/ValidationException.php");

/**
 * Class Favorite
 *
 * Represents a Liked Recipe in the web. A Like is attached
 * to a Recipe and was liked by an specific User (author)
 *
 * @author drmartinez
 */
class Favorite {

    /**
     * The id of the like
     * @var string
     */
    private $id;

    /**
     * The author of the like
     * @var User
     */
    private $alias;

    /**
     * The recipe being liked by this like
     * @var Recipe
     */
    private $recipe;

    /**
     * The constructor
     *
     * @param string $id The id of the like
     * @param User $alias The author of the like
     * @param Recipe $recipe The liked recipe
     */
    public function __construct($id=NULL, User $alias=NULL, Recipe $recipe=NULL) {
        $this->id = $id;
        $this->alias = $alias;
        $this->recipe = $recipe;
    }

    /**
     * Gets the id of this like
     *
     * @return string The id of this like
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Gets the author of this like
     *
     * @return User The author of this like
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * Sets the author of this like
     *
     * @param User $alias the author of this like
     * @return void
     */
    public function setAlias(User $alias){
        $this->alias = $alias;
    }

    /**
     * Gets the parent recipe of this like
     *
     * @return Recipe The parent recipe of this like
     */
    public function getRecipe() {
        return $this->recipe;
    }

    /**
     * Sets the parent Recipe
     *
     * @param Recipe $recipe the parent Recipe
     * @return void
     */
    public function setPost(Recipe $recipe) {
        $this->recipe = $recipe;
    }

    /**
     * Checks if the current instance is valid
     * for being inserted in the database.
     *
     * @throws ValidationException if the instance is
     * not valid
     *
     * @return void
     */
    public function checkIsValidForCreate() {
        $errors = array();

        if ($this->alias == NULL ) {
            $errors["alias"] = "alias is mandatory";
        }
        if ($this->recipe == NULL ) {
            $errors["recipe"] = "recipe is mandatory";
        }

        if (sizeof($errors) > 0){
            throw new ValidationException($errors, "comment is not valid");
        }
    }
}
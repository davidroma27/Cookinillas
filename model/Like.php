<?php
// file: model/Like.php

require_once(__DIR__."/../core/ValidationException.php");

/**
 * Class Like
 *
 * Represents a Liked Recipe in the web. A Like is attached
 * to a Recipe and was liked by an specific User (author)
 *
 * @author drmartinez
 */
class Like {

    /**
     * The author of the like
     */
    private $alias;

    /**
     * The recipe being liked by this like
     */
    private $recipe;

    /**
     * The constructor
     *
     * @param $alias The author of the like
     * @param $recipe The liked recipe
     */
    public function __construct($alias=NULL, $recipe=NULL) {
        $this->alias = $alias;
        $this->recipe = $recipe;
    }

    /**
     * Gets the author of this like
     *
     * @return The author of this like
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * Sets the author of this like
     *
     * @param $alias the author of this like
     * @return void
     */
    public function setAlias($alias){
        $this->alias = $alias;
    }

    /**
     * Gets the parent recipe of this like
     *
     * @return The parent recipe of this like
     */
    public function getRecipe() {
        return $this->recipe;
    }

    /**
     * Sets the parent Recipe
     *
     * @param $recipe the parent Recipe
     * @return void
     */
    public function setRecipe($recipe) {
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
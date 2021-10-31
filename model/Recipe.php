<?php
// file: model/Recipe.php

require_once(__DIR__ . "/../core/ValidationException.php");

/**
 * Class Recipe
 *
 * Represents a Recipe in the web
 *
 * @author drmartinez
 */
class Recipe
{

    /**
     * The id of the recipe
     * @var int
     */
    private $id;

    /**
     * The title of the recipe
     * @var string
     */
    private $title;

    /**
     * The image of the recipe
     * @var mixed
     */
    private $img;

    /**
     * The time of the recipe
     * @var int
     */
    private $time;

    /**
     * The ingredients of the recipe
     * @var string
     */
    private $ingr;

    /**
     * The quantity of each ingredient
     * @var string
     */
    private $quant;

    /**
     * The steps of the recipe
     * @var string
     */
    private $steps;

    /**
     * The author of the recipe
     * @var User
     */
    private $alias;

    /**
     * The constructor
     *
     * @param int $id The id of the recipe
     * @param string $title The title of the recipe
     * @param mixed $img The img of the recipe
     * @param int $time The time of the recipe
     * @param string $ingr The ingredients of the recipe
     * @param string $quant The quantity of each ingredient
     * @param string $steps The steps of the recipe
     * @param User $alias The author of the recipe
     */
    public function __construct($id = NULL, $title = NULL, $img = NULL, $steps = NULL, $time = NULL, $ingr = NULL, $quant = NULL, User $alias = NULL)
    {
        $this->id = $id;
        $this->title = $title;
        $this->img = $img;
        $this->steps = $steps;
        $this->ingr = $ingr;
        $this->quant = $quant;
        $this->time = $time;
        $this->alias = $alias;
    }

    /**
     * Gets the id of the recipe
     *
     * @return int The id of the recipe
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets de id of the recipe
     *
     * @param int $id The id of the recipe
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the title of the recipe
     *
     * @return string The title of the recipe
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the recipe
     *
     * @param string $title The title of the recipe
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Gets the img of the recipe
     *
     * @return mixed The img of the recipe
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Sets the img of the recipe
     *
     * @param mixed $img The img of the recipe
     * @return void
     */
    public function setImg($img)
    {
        $this->img = $img;
    }

    /**
     * Gets the time of the recipe
     *
     * @return int The time of the recipe
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Sets the time of the recipe
     *
     * @param int $time The time of the recipe
     * @return void
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Gets the ingredients of the recipe
     *
     * @return string The ingredients of the recipe
     */
    public function getIngr()
    {
        return $this->ingr;
    }

    /**
     * Sets the ingredients of the recipe
     *
     * @param string $ingr The ingredients list of the recipe
     * @return void
     */
    public function setIngr($ingr)
    {
        $this->ingr = $ingr;
    }

    /**
     * Gets the quantity of each ingredient
     *
     * @return string The quantity of the recipe
     */
    public function getQuant()
    {
        return $this->quant;
    }

    /**
     * Sets the quantity of each ingredient
     *
     * @param int $quant The quantity of each ingredient
     * @return void
     */
    public function setQuant($quant)
    {
        $this->quant = $quant;
    }

    /**
     * Gets the steps of the recipe
     *
     * @return string The steps of the recipe
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Sets the steps of the recipe
     *
     * @param string $steps The steps of the recipe
     * @return void
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;
    }

    /**
     * Gets the author of this recipe
     *
     * @return User The author of this recipe
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Sets the author of this recipe
     *
     * @param User $alias The author of this recipe
     * @return void
     */
    public function setAlias(User $alias)
    {
        $this->alias = $alias;
    }

    /**
     * Checks if the current instance is valid
     * for being created in the database.
     *
     * @throws ValidationException if the instance is
     * not valid
     *
     * @return void
     */
    public function checkIsValidForCreate() {
        $errors = array();
        if (strlen(trim($this->title)) == 0 ) {
            $errors["title"] = "title is mandatory";
        }
        if ($this->img == NULL) {
            $errors["img"] = "image is mandatory";
        }
        if ($this->time == NULL ) {
            $errors["time"] = "time is mandatory";
        }
        if (strlen(trim($this->ingr)) == 0 ) {
            $errors["ingr"] = "ingredients is mandatory";
        }
        if (strlen(trim($this->quant)) == 0 ) {
            $errors["quant"] = "quantity is mandatory";
        }
        if (strlen(trim($this->steps)) == 0 ) {
            $errors["steps"] = "steps is mandatory";
        }
        if ($this->alias == NULL ) {
            $errors["alias"] = "alias is mandatory";
        }
        if (sizeof($errors) > 0){
            throw new ValidationException($errors, "recipe is not valid");
        }
    }

    /**
     * Checks if the current instance is valid
     * for being updated in the database.
     *
     * @throws ValidationException if the instance is
     * not valid
     *
     * @return void
     */
    public function checkIsValidForUpdate() {
        $errors = array();

        if (!isset($this->id)) {
            $errors["id"] = "id is mandatory";
        }

        try{
            $this->checkIsValidForCreate();
        }catch(ValidationException $ex) {
            foreach ($ex->getErrors() as $key=>$error) {
                $errors[$key] = $error;
            }
        }
        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, "recipe is not valid");
        }
    }
}
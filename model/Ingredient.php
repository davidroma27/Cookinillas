<?php

// file: model/Ingredient.php

require_once(__DIR__ . "/../core/ValidationException.php");

/**
 * Class Ingredients
 *
 * Represents a Ingredient of a recipe
 *
 * @author drmartinez
 */

class Ingredient {

    public static $regexpIngr = "/[a-zA-Z]+/";

    /**
     * The id of the associated recipe
     * @var int
     */
    private $id;

    /**
     * The name of the ingredient recipe
     * @var string
     */
    private $nombre;

    /**
     * @param int $id The id of the recipe
     * @param string $nombre The name of the ingredient
     */
    public function __construct($id = NULL, $nombre = NULL){
        $this->id = $id;
        $this->nombre = $nombre;
    }

    /**
     * Gets the id of the associated recipe
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the associated recipe
     * @param int $id
     * @return void
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Gets the name of the ingredient
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Sets the name of the ingredient
     * @param string $nombre
     * @return void
     */
    public function setNombre(string $nombre)
    {
        $this->nombre = $nombre;
    }

    public static function isValidIngredient($nombre) {
        return preg_match(Ingredient::$regexpIngr, $nombre);
    }


    public function toArray(){
        return array(
            "id" => $this->getId(),
            "nombre" => $this->getNombre()
        );
    }
}
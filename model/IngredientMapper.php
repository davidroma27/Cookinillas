<?php
//file: model/IngredientMapper.php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/Ingredient.php");


/**
 * Class IngredientMapper
 *
 * Database interface for Ingredient entities
 *
 * @author drmartinez
 */
class IngredientMapper{

    /**
     * Reference to the PDO connection
     * @var PDO
     */
    private $db;

    public function __construct() {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Saves an Ingredient into the database
     *
     * @param Ingredient $ingredient The ingredient to be saved
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function save($ingredient) {
        $stmt = $this->db->prepare("INSERT INTO ingredientes (id_ingr, nombre) values (?,?)");
        $stmt->execute(array($ingredient->getId(), $ingredient->getNombre()));
    }

    /**
     * Deletes an Ingredient from the database
     *
     * @param Ingredient $ingredient The ingredient to be deleted
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function delete($ingredient) {
        $stmt = $this->db->prepare("DELETE FROM ingredientes WHERE id_ingr=? AND nombre=?");
        $stmt->execute(array($ingredient->getId(), $ingredient->getNombre()));
    }

    /**
     * Loads the ingredients from the database given its recipe ID
     *
     * @throws PDOException if a database error occurs
     * @return Ingredient The ingredients instances. NULL if the Ingredient is not found
     */
    public function findById($id){
        $stmt = $this->db->prepare("SELECT nombre FROM ingredientes, recetas WHERE ingredientes.id_ingr = recetas.id_receta");
        $stmt->execute(array($id));
        $ingredientes = $stmt->fetch(PDO::FETCH_ASSOC);

        if($ingredientes != null){
            return new Ingredient(null,
                $ingredientes["nombre"]
            );
        }else{
            return NULL;
        }
    }

}
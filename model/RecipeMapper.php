<?php
// file: model/RecipeMapper.php
require_once(__DIR__."/../core/PDOConnection.php");

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Recipe.php");
require_once(__DIR__."/../model/Favorite.php");

/**
 * Class RecipeMapper
 *
 * Database interface for Recipe entities
 *
 * @author drmartinez
 */
class RecipeMapper {

    /**
     * Reference to the PDO connection
     * @var PDO
     */
    private $db;

    public function __construct() {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Retrieves all recipes
     *
     * @throws PDOException if a database error occurs
     * @return mixed Array of Recipe instances
     */
    public function findAll() {
        $stmt1 = $this->db->query("SELECT recetas.*
                                            FROM recetas, usuarios
                                            WHERE recetas.alias = usuarios.alias");
        $stmt2 = $this->db->query("SELECT receta_ingrediente.nombre, receta_ingrediente.cantidad 
                                            FROM recetas, receta_ingrediente
                                            WHERE recetas.id_receta = receta_ingrediente.id_receta");

        $recipes_db = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        $ingr_db = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $recipes = array();

        foreach ($recipes_db as $recipe) {
            array_push($recipes, new Recipe($recipe["id_receta"],$recipe["titulo"], $recipe["imagen"], $recipe["tiempo"],
                new User($recipe["alias"]),$ingr_db,$ingr_db, $recipe["pasos"]));

        }
        return $recipes;
    }

    /**
     * Loads a Recipe from the database given its id
     *
     * @throws PDOException if a database error occurs
     * @return Recipe The Recipe instances. NULL if the Recipe is not found
     */
    public function findById($recipeid){
        $stmt1 = $this->db->prepare("SELECT *
                                            FROM recetas
                                            WHERE recetas.id_receta=?");
        $stmt2 = $this->db->prepare("SELECT nombre
                                            FROM receta_ingrediente, recetas
                                            WHERE recetas.id_receta=? AND receta_ingrediente.id_receta=?");
        $stmt3 = $this->db->prepare("SELECT cantidad
                                            FROM receta_ingrediente, recetas
                                            WHERE recetas.id_receta=? AND receta_ingrediente.id_receta=?");
        $stmt1->execute(array($recipeid));
        $stmt2->execute(array($recipeid,$recipeid));
        $stmt3->execute(array($recipeid,$recipeid));

        $recipe = $stmt1->fetch(PDO::FETCH_ASSOC);
        $nombre = $stmt2->fetchAll(PDO::FETCH_COLUMN);
        $cant = $stmt3->fetchAll(PDO::FETCH_COLUMN);

        if($recipe != null) {
            return new Recipe(
                $recipe["id_receta"],
                $recipe["titulo"],
                $recipe["imagen"],
                $recipe["tiempo"],
                new User($recipe["alias"]),
                $nombre,
                $cant,
                $recipe["pasos"]
                );
        } else {
            return NULL;
        }
    }

    /**
     * Retrieves all existing ingredients in database
     *
     * @throws PDOException if a database error occurs
     * @return mixed an array of existing ingredients
     */
    public function getIngredients(){
        $stmt = $this->db->query("SELECT nombre FROM ingredientes");
        $stmt->execute();

        $ingr = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if($ingr != NULL){
            return $ingr;
        }
        else return null;
    }

    /**
     * Count likes adding to db who liked a recipe
     *
     * @throws PDOException if a database error occurs
     * @return void
     *
    public function countLikes(){
        if(isset())

    }
     */

    /**
     * Loads a Recipe from the database given its ids (With LIKES)
     *
     * @throws PDOException if a database error occurs
     * @return Recipe The Recipe instances. NULL if the Recipe is not found
     *
    public function findByIdWithLikes($recipeid){
        $stmt = $this->db->prepare("SELECT
            R.id_receta AS 'recetas.id_receta',
            R.titulo AS 'recetas.titulo',
            R.imagen AS 'recetas.imagen',
            R.tiempo AS 'recetas.tiempo',
            R.pasos AS 'recetas.pasos',
            R.alias AS 'recetas.alias',
            F.id_rec_fav AS 'receta_fav.id_rec_fav',
            F.id_receta AS 'receta_fav.id_receta',
            F.alias AS 'receta_fav.alias'
        FROM
            recetas R
        LEFT OUTER JOIN receta_fav F ON
            R.id_receta = F.id_receta
        WHERE
            R.id_receta = ?");

        $stmt->execute(array($recipeid));
        $recipe_wt_likes= $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (sizeof($recipe_wt_likes) > 0) {
            $recipe = new Recipe($recipe_wt_likes[0]["recetas.id_receta"],
                $recipe_wt_likes[0]["recetas.titulo"],
                $recipe_wt_likes[0]["recetas.imagen"],
                $recipe_wt_likes[0]["recetas.tiempo"],
                $recipe_wt_likes[0]["recetas.pasos"],
                new User($recipe_wt_likes[0]["recetas.alias"]));
            $likes_array = array();
            if ($recipe_wt_likes[0]["receta_fav.id_rec_fav"]!=null) {
                foreach ($recipe_wt_likes as $like){
                    $like = new Favorite( $like["receta_fav.id_rec_fav"],
                        new User($like["receta_fav.alias"]),
                        $like["receta_fav.id_receta"],
                        $recipe);
                    array_push($likes_array, $like);
                }
            }
            $recipe->setFavorite($likes_array);

            return $recipe;
        }else {
            return NULL;
        }
    }
     */

    /**
     * Saves a Recipe into the database
     *
     * @param Recipe $recipe The recipe to be saved
     * @throws PDOException if a database error occurs
     * @return int The new recipe id
     */
    public function save(Recipe $recipe) {
        $stmt1 = $this->db->prepare("INSERT INTO recetas(titulo, imagen, tiempo, pasos, alias) values (?,?,?,?,?)");
        $stmt2 = $this->db->prepare("INSERT INTO ingredientes(nombre) VALUES (?) ON DUPLICATE KEY UPDATE nombre = nombre");
        $stmt3 = $this->db->prepare("INSERT INTO receta_ingrediente(id_receta, nombre, cantidad) VALUES (?,?,?)");

        $stmt1->execute(array($recipe->getTitle(), $recipe->getImg(), $recipe->getTime(), $recipe->getSteps(), $recipe->getAlias()->getAlias()));
        $recipeid = $this->db->lastInsertId();

        $stmt2->execute(array($recipe->getIngr()));
        $stmt3->execute(array($recipeid, $recipe->getIngr(), $recipe->getQuant()));

        return $this->db->lastInsertId();
    }

    /**
     * Updates a Recipe in the database
     *
     * @param Recipe $recipe The recipe to be saved
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function update(Recipe $recipe) {
        var_dump($recipe);
        $stmt1 = $this->db->prepare("UPDATE recetas set titulo=?, imagen=?, tiempo=?, pasos=? where id_receta=?");
        $stmt2 = $this->db->prepare("INSERT INTO ingredientes(nombre) VALUES (?) ON DUPLICATE KEY UPDATE nombre = nombre");
        $stmt3 = $this->db->prepare("UPDATE receta_ingrediente set nombre=?, cantidad=? WHERE id_receta=?");

        $stmt1->execute(array($recipe->getTitle(), $recipe->getImg(), $recipe->getTime(), $recipe->getSteps(), $recipe->getId()));
        $stmt2->execute(array($recipe->getIngr()));
        $stmt3->execute(array($recipe->getIngr(), $recipe->getQuant(), $recipe->getId()));

    }

    /**
     * Deletes a Recipe into the database
     *
     * @param Recipe $recipe The recipe to be deleted
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function delete(Recipe $recipe) {
        $stmt = $this->db->prepare("DELETE from recetas WHERE id_receta=?");
        $stmt->execute(array($recipe->getId()));
    }

}
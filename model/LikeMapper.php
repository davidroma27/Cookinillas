
<?php
//file: model/LikeMapper.php

require_once(__DIR__ . "/../core/PDOConnection.php");
require_once(__DIR__ . "/../model/Like.php");

class LikeMapper
{

    private $db;

    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    public function findByUsername($alias)
    {
        $stmt = $this->db->prepare("SELECT alias FROM receta_fav WHERE alias=?");
        $stmt->execute(array($alias));
        $username = array();
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $value) {
            array_push($username, $value);
        }
        return $username;
    }

    public function isLike($alias, $receta)
    {
        $stmt = $this->db->prepare("SELECT id_receta FROM receta_fav WHERE alias=? and id_receta=?");
        $stmt->execute(array($alias, $receta));
        return $stmt->fetchColumn() > 0;
    }

    public function save($like)
    {
        $stmt = $this->db->prepare("INSERT INTO receta_fav(alias, id_receta) values (?,?)");
        $stmt->execute(array($like->getRecipe(), $like->getAlias()));
        return $this->db->lastInsertId();
    }

    public function delete($like)
    {
        $stmt = $this->db->prepare("DELETE FROM receta_fav WHERE id_receta=? AND alias=?");
        $stmt->execute(array($like->getRecipe(), $like->getAlias()));
    }

}
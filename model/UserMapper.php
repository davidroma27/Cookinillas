<?php
// file: model/UserMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

/**
 * Class UserMapper
 *
 * Database interface for User entities
 *
 * @author drmartinez
 */
class UserMapper {

    /**
     * Reference to the PDO connection
     * @var PDO
     */
    private $db;

    public function __construct() {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Saves a User into the database
     *
     * @param User $user The user to be saved
     * @throws PDOException if a database error occurs
     * @return void
     */
    public function save($user) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (alias, password, email) values (?,?,?)");
        $stmt->execute(array($user->getAlias(), $user->getPasswd(), $user->getEmail()));
    }

    /**
     * Checks if a given username is already in the database
     *
     * @param string $alias the username to check
     * @return boolean true if the username exists, false otherwise
     */
    public function usernameExists($alias) {
        $stmt = $this->db->prepare("SELECT count(alias) FROM usuarios where alias=?");
        $stmt->execute(array($alias));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }

    /**
     * Checks if a given email is already in the database
     *
     * @param string $email the email to check
     * @return boolean true if the email exists, false otherwise
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT count(email) FROM usuarios where email=?");
        $stmt->execute(array($email));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }

    /**
     * Checks if a given pair of username/password exists in the database
     *
     * @param string $alias the username
     * @param string $passwd the password
     * @return boolean true the username/password/email exists, false otherwise.
     */
    public function isValidUser($alias, $passwd) {
        $stmt = $this->db->prepare("SELECT count(alias) FROM usuarios where alias=? and password=?");
        $stmt->execute(array($alias, $passwd));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }
}
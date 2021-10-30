<?php
// file: model/User.php

require_once(__DIR__ . "/../core/ValidationException.php");

/**
 * Class User
 *
 * Represents a User in the web
 *
 * @author drmartinez
 */
class User
{

    /**
     * The username of the user
     * @var string
     */
    private $alias;

    /**
     * The password of the user
     * @var string
     */
    private $passwd;

    /**
     * The email of the user
     * @var string
     */
    private $email;

    /**
     * The constructor
     *
     * @param string $alias The name of the user
     * @param string $passwd The password of the user
     * @param string $email The email of the user
     */
    public function __construct($alias = NULL, $passwd = NULL, $email = NULL)
    {
        $this->alias = $alias;
        $this->passwd = $passwd;
        $this->email = $email;
    }

    /**
     * Gets the alias of this user
     *
     * @return string The alias of this user
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Sets the alias of this user
     *
     * @param string $alias The alias of this user
     * @return void
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Gets the password of this user
     *
     * @return string The password of this user
     */
    public function getPasswd()
    {
        return $this->passwd;
    }

    /**
     * Sets the password of this user
     *
     * @param string $passwd The password of this user
     * @return void
     */
    public function setPassword($passwd)
    {
        $this->passwd = $passwd;
    }

    /**
     * Gets the email of this user
     *
     * @return string The email of this user
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the alias of this user
     *
     * @param string $email The email of this user
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Checks if the current user instance is valid
     * for being registered in the database
     *
     * @return void
     * @throws ValidationException if the instance is
     * not valid
     *
     */
    public function checkIsValidForRegister()
    {
        $errors = array();
        if (strlen($this->alias) < 5) {
            $errors["alias"] = "Username must be at least 5 characters length";

        }
        if (strlen($this->passwd) < 5) {
            $errors["passwd"] = "Password must be at least 5 characters length";
        }
        if (strlen($this->email) < 5) {
            $errors["email"] = "Email must be at least 5 characters length";
        }
        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, "user is not valid");
        }
    }
}
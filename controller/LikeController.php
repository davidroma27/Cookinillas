<?php
//file: controller/LikeController.php

require_once(__DIR__ . '/../model/Like.php');
require_once(__DIR__ . '/../model/LikeMapper.php');

require_once(__DIR__ . '/../core/ViewManager.php');
require_once(__DIR__ . '/../controller/BaseController.php');

class LikeController extends BaseController{

    private $likeMapper;

    public function __construct() {
        parent::__construct();

        $this->likeMapper = new LikeMapper();
    }

    public function like(){
        if (!isset($_SESSION["currentuser"])) {
            $this->view->redirect("users", "login");
        }

        if (isset($_POST["id"])) {
            $like = new Like($_POST["id"], $_SESSION["currentuser"]);
            $this->likeMapper->save($like);
        }

        if (isset($_SERVER["HTTP_REFERER"])) {
            $this->view->redirectToReferer();
        } else {
            $this->view->redirect("home", "index");
        }
    }

    public function dislike(){
        if (!isset($_SESSION["currentuser"])) {
            $this->view->redirect("home", "index");
        }

        if (isset($_POST["id"])) {
            $like = new Like($_POST["id"], $_SESSION["currentuser"]);
            $this->likeMapper->delete($like);
        }

        if (isset($_SERVER["HTTP_REFERER"])) {
            $this->view->redirectToReferer();
        } else {
            $this->view->redirect("home", "index");
        }
    }


}
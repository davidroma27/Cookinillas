<?php
    //file: views/users/access.php

    require_once(__DIR__."/../../core/ViewManager.php");
    require_once(__DIR__."/../../controller/LanguageController.php");
    $view = ViewManager::getInstance();
    $errors = $view->getVariable("errors");
    $alias = $view->getVariable("alias");
    $view->setVariable("title", "Access");
?>


<form action="index.php?controller=users&amp;action=login" method="POST" class="form-signin">
    <span><?= i18n("Inicia sesión")?></span>

    <label class="username"><?= i18n("Nombre de usuario")?></label>
    <input type="text" name="alias" value="">

    <label class="username"><?= i18n("Contraseña")?></label>
    <input type="password" name="passwd" value="">

    <input class="form__button" type="submit" value="<?= i18n("Inicia sesión")?>">
</form>

<span>&mdash;&mdash;&mdash; <?= i18n("o bien")?> &mdash;&mdash;&mdash;</span>

<form action="index.php?controller=users&amp;action=register" method="POST" class="form-signup">
    <span><?= i18n("Regístrate")?></span>

    <label class="username"><?= i18n("Nombre de usuario")?></label>
    <input type="text" name="alias" value="">
    <p>
    <?= isset($errors["alias"])?i18n($errors["alias"]):"" ?><br>
    </p>

    <label class="username"><?= i18n("Contraseña")?></label>
    <input type="password" name="passwd">
    <p>
    <?= isset($errors["passwd"])?i18n($errors["passwd"]):"" ?><br>
    </p>

    <label class="username"><?= i18n("e-mail")?></label>
    <input type="text" name="email">

    <input class="form__button" type="submit" value="<?= i18n("Regístrate")?>">
</form>

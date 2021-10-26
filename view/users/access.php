<?php
    //file: views/users/access.php

    require_once(__DIR__."/../../core/ViewManager.php");
    $view = ViewManager::getInstance();
    $errors = $view->getVariable("errors");
    $alias = $view->getVariable("alias");
    $view->setVariable("title", "Access");
?>


<form action="index.php?controller=users&amp;action=login" method="POST" class="form-signin">
    <span><?= i18n("Inicia sesión")?></span>

    <label class="username"><?= i18n("Nombre de usuario")?></label>
    <input type="text" name="Nombre de usuario" value="">

    <label class="username"><?= i18n("Contraseña")?></label>
    <input type="password" name="Contraseña" value="">

    <input class="form__button" type="submit" value="<?= i18n("Inicia sesión")?>">
</form>

<span>&mdash;&mdash;&mdash; <?= i18n("o bien")?> &mdash;&mdash;&mdash;</span>

<form action="index.php?controller=users&amp;action=register" method="POST" class="form-signup">
    <span><?= i18n("Regístrate")?></span>

    <label class="username"><?= i18n("Nombre de usuario")?></label>
    <input type="text" name="Nombre de usuario" value="">
    <?= isset($errors["alias"])?i18n($errors["Nombre de usuario"]):"" ?><br>

    <label class="username"><?= i18n("Contraseña")?></label>
    <input type="password" name="Contraseña">

    <label class="username"><?= i18n("e-mail")?></label>
    <input type="text" name="e-mail">

    <input class="form__button" type="submit" value="<?= i18n("Regístrate")?>">
</form>

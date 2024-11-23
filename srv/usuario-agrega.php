<?php
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/validaNombre.php";
require_once __DIR__ . "/../lib/php/devuelveCreated.php";


ejecutaServicio(function () {
    $nombre = validaNombre(recuperaTexto("nombre"));
    $email = validaNombre(recuperaTexto("email"));
    $password = validaNombre(recuperaTexto("password"));
    $direccion = recuperaTexto("direccion");
    $telefono = recuperaTexto("telefono");
    $estatus = "activo";

    $pdo = Bd::pdo();
    $pdo->prepare("INSERT INTO USUARIO (USU_NOMBRE, USU_EMAIL, USU_PASSWORD, USU_DIRECCION, USU_TELEFONO, USU_ESTATUS) 
                   VALUES (?, ?, ?, ?, ?, ?)")
        ->execute([$nombre, $email, $password, $direccion, $telefono, $estatus]);
    
  

    $id = $pdo->lastInsertId();
    devuelveCreated("/srv/usuario.php?id=" . urlencode($id), [
        "id" => ["value" => $id],
        "nombre" => ["value" => $nombre],
        "email" => ["value" => $email]
    ]);

   
});

<?php
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/recuperaArray.php";
require_once __DIR__ . "/../lib/php/validaNombre.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/../lib/php/insertBridges.php";
require_once __DIR__ . "/../lib/php/devuelveCreated.php";
require_once __DIR__ . "/TABLA_USUARIO.php";
require_once __DIR__ . "/TABLA_ROL.php";
require_once __DIR__ . "/TABLA_USU_ROL.php";


ejecutaServicio(function () {

    $nombre = recuperaTexto("nombre");
    $email = recuperaTexto("email");
    $password = recuperaTexto("password");
    $direccion = recuperaTexto("direccion");
    $telefono = recuperaTexto("telefono");
    $estatus = "1";
    $rolIds = recuperaArray("rolIds");
    
    $nombre = validaNombre($nombre);
    $email = validaNombre($email);
    $password = validaNombre($password);
    
 $pdo = Bd::pdo();
 $pdo->beginTransaction();

        insert(pdo: $pdo, into: USUARIO, values: [USU_NOMBRE => $nombre,USU_EMAIL => $email, USU_PASSWORD => password_hash($password, PASSWORD_DEFAULT),USU_DIRECCION => $direccion, USU_TELEFONO => $telefono, USU_ESTATUS => $estatus]);
        $usuId = $pdo->lastInsertId();
        insertBridges(
         pdo: $pdo,
         into: USU_ROL,
         valuesDePadre: [USU_ID => $usuId],
         valueDeHijos: [ROL_ID => $rolIds]
        );

        $pdo->commit();
        
        $encodeUsuId = urlencode($usuId);
 devuelveCreated("/srv/usuario.php?id=$encodeUsuId", [
  "id" => ["value" => $usuId],
  "email" => ["value" => $email],
  "password" => ["value" => $password],
  "direccion" => ["value" => $direccion],
  "telefono" => ["value" => $telefono],
  "estatus" => ["value" => $estatus],
  "rolIds" => ["value" => $rolIds],
 ]);


   
});

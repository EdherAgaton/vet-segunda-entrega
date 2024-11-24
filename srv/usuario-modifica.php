<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/recuperaArray.php";
require_once __DIR__ . "/../lib/php/validaNombre.php";
require_once __DIR__ . "/../lib/php/update.php";
require_once __DIR__ . "/../lib/php/delete.php";
require_once __DIR__ . "/../lib/php/insertBridges.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_USUARIO.php";
require_once __DIR__ . "/TABLA_ROL.php";
require_once __DIR__ . "/TABLA_USU_ROL.php";

ejecutaServicio(function () {

    $usuId = recuperaIdEntero("id");
    $nombre = recuperaTexto("nombre");
    $email = recuperaTexto("email");
    $telefono = recuperaTexto("telefono");
    $direccion = recuperaTexto("direccion");
    $rolIds = recuperaArray("rolIds");

    $nombre = validaNombre($nombre);
    $email = validaNombre($email);
    $telefono = validaNombre($telefono);
    $direccion = validaNombre($direccion);

    $pdo = Bd::pdo();
    $pdo->beginTransaction();

    update(
        pdo: Bd::pdo(),
        table: USUARIO,
        set: [
            USU_NOMBRE => $nombre,
            USU_EMAIL => $email,
            USU_TELEFONO => $telefono,
            USU_DIRECCION => $direccion
        ],
        where: [USU_ID => $usuId]
    );
    delete(pdo: $pdo, from: USU_ROL, where: [USU_ID => $usuId]);
    insertBridges(
        pdo: $pdo,
        into: USU_ROL,
        valuesDePadre: [USU_ID => $usuId],
        valueDeHijos: [ROL_ID => $rolIds]
       );

       $pdo->commit();

    devuelveJson([
        "id" => ["value" => $usuId],
        "nombre" => ["value" => $nombre],
        "email" => ["value" => $email],
        "telefono" => ["value" => $telefono],
        "direccion" => ["value" => $direccion],
        "rolIds" => ["value" => $rolIds],
    ]);
});

<?php
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/validaNombre.php";
require_once __DIR__ . "/../lib/php/devuelveCreated.php";

ejecutaServicio(function () {
    $cliente = recuperaIdEntero("cliente_id");
    $nombre = validaNombre(recuperaTexto("nombre"));
    $especie = recuperaTexto("especie");
    $raza = recuperaTexto("raza");
    $fechaNac = recuperaTexto("fecha_nacimiento");
    $estatus = "activo";

    $pdo = Bd::pdo();
    $pdo->prepare("INSERT INTO MASCOTA (MAS_CLIENTE, MAS_NOMBRE, MAS_ESPECIE, MAS_RAZA, MAS_FECHA_NAC, MAS_ESTATUS)
                   VALUES (?, ?, ?, ?, ?, ?)")
        ->execute([$cliente, $nombre, $especie, $raza, $fechaNac, $estatus]);

    $id = $pdo->lastInsertId();
    devuelveCreated("/srv/mascota.php?id=" . urlencode($id), [
        "id" => ["value" => $id],
        "nombre" => ["value" => $nombre],
        "especie" => ["value" => $especie],
        "raza" => ["value" => $raza]
    ]);
});

<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/validaNombre.php";
require_once __DIR__ . "/../lib/php/update.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_MASCOTA.php";

ejecutaServicio(function () {

    $id = recuperaIdEntero("id");
    $nombre = recuperaTexto("nombre");
    $especie = recuperaTexto("especie");
    $raza = recuperaTexto("raza");
    $fecha_nac = recuperaTexto("fecha_nac");

    $nombre = validaNombre($nombre);
    $especie = validaNombre($especie);
    $raza = validaNombre($raza);
    $fecha_nac = validaNombre($fecha_nac);

    update(
        pdo: Bd::pdo(),
        table: MASCOTA,
        set: [
            MAS_NOMBRE => $nombre,
            MAS_ESPECIE => $especie,
            MAS_RAZA => $raza,
            MAS_FECHA_NAC => $fecha_nac
        ],
        where: [MAS_ID => $id]
    );

    devuelveJson([
        "id" => ["value" => $id],
        "nombre" => ["value" => $nombre],
        "especie" => ["value" => $especie],
        "raza" => ["value" => $raza],
        "fecha_nac" => ["value" => $fecha_nac],
    ]);
});

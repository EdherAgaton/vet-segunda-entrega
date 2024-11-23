<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/validaNombre.php";
require_once __DIR__ . "/../lib/php/update.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_VETERINARIO.php";

ejecutaServicio(function () {

    $id = recuperaIdEntero("id");
    $nombre = recuperaTexto("nombre");
    $email = recuperaTexto("email");
    $telefono = recuperaTexto("telefono");
    $especialidad = recuperaTexto("especialidad");

    $nombre = validaNombre($nombre);
    $email = validaNombre($email);
    $telefono = validaNombre($telefono);
    $especialidad = validaNombre($especialidad);

    update(
        pdo: Bd::pdo(),
        table: VETERINARIO,
        set: [
            VET_NOMBRE => $nombre,
            VET_EMAIL => $email,
            VET_TELEFONO => $telefono,
            VET_ESPECIALIDAD => $especialidad
        ],
        where: [VET_ID => $id]
    );

    devuelveJson([
        "id" => ["value" => $id],
        "nombre" => ["value" => $nombre],
        "email" => ["value" => $email],
        "telefono" => ["value" => $telefono],
        "especialidad" => ["value" => $especialidad],
    ]);
});

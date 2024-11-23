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
    $telefono = recuperaTexto("telefono");
    $cedula = recuperaTexto("cedula");
    $especialidad = recuperaTexto("especialidad");
    $horario = recuperaTexto("horario");
    $estatus = "activo";

    $pdo = Bd::pdo();
    $pdo->prepare("INSERT INTO VETERINARIO (VET_NOMBRE, VET_EMAIL, VET_PASSWORD, VET_TELEFONO, VET_CEDULA, VET_ESPECIALIDAD, VET_HORARIO, VET_ESTATUS)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
        ->execute([$nombre, $email, $password, $telefono, $cedula, $especialidad, $horario, $estatus]);

    $id = $pdo->lastInsertId();
    devuelveCreated("/srv/veterinario.php?id=" . urlencode($id), [
        "id" => ["value" => $id],
        "nombre" => ["value" => $nombre],
        "email" => ["value" => $email]
    ]);
});

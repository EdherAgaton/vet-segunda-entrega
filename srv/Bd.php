<?php


require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/../lib/php/insertBridges.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/TABLA_USUARIO.php";
require_once __DIR__ . "/TABLA_ROL.php";
require_once __DIR__ . "/TABLA_USU_ROL.php";
require_once __DIR__ . "/ROL_ID_CLIENTE.php";
require_once __DIR__ . "/ROL_ID_ADMINISTRADOR.php";


class Bd
{
    private static ?PDO $pdo = null;

    static function pdo(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                // cadena de conexión
                "sqlite:srvbd.db",
                // usuario
                null,
                // contraseña
                null,
                // Opciones: pdos no persistentes y lanza excepciones.
                [PDO::ATTR_PERSISTENT => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Tabla PRODUCTO
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS PRODUCTO (
                    PRO_ID INTEGER PRIMARY KEY,
                    PRO_NOMBRE TEXT NOT NULL, 
                    PRO_PRECIO REAL NOT NULL,
                    PRO_DESCRIPCION TEXT NOT NULL,
                    CONSTRAINT PRO_NOMBRE_UK UNIQUE(PRO_NOMBRE),
                    CONSTRAINT PRO_PRECIO_CK CHECK(PRO_PRECIO > 0)
                )"
            );

            // Tabla USUARIO
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS USUARIO (
                    USU_ID INTEGER PRIMARY KEY,
                    USU_NOMBRE TEXT NOT NULL,
                    USU_EMAIL TEXT NOT NULL,
                    USU_PASSWORD TEXT NOT NULL,
                    USU_DIRECCION TEXT,
                    USU_TELEFONO TEXT,
                    USU_ESTATUS TEXT NOT NULL,
                    CONSTRAINT USUARIO_EMAIL_UK UNIQUE(USU_EMAIL)
                )"
            );

            // Tabla MASCOTA
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS MASCOTA (
                    MAS_ID INTEGER PRIMARY KEY,
                    MAS_CLIENTE INTEGER NOT NULL,
                    MAS_NOMBRE TEXT NOT NULL,
                    MAS_ESPECIE TEXT NOT NULL,
                    MAS_RAZA TEXT,
                    MAS_FECHA_NAC DATE,
                    MAS_ESTATUS TEXT NOT NULL,
                    CONSTRAINT MASCOTA_CLIENTE_FK FOREIGN KEY(MAS_CLIENTE) REFERENCES CLIENTE(CLI_ID)
                )"
            );

            // Tabla VETERINARIO
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS VETERINARIO (
                    VET_ID INTEGER PRIMARY KEY,
                    VET_NOMBRE TEXT NOT NULL,
                    VET_EMAIL TEXT NOT NULL,
                    VET_PASSWORD TEXT NOT NULL,
                    VET_TELEFONO TEXT,
                    VET_CEDULA TEXT,
                    VET_ESPECIALIDAD TEXT,
                    VET_HORARIO TEXT,
                    VET_ESTATUS TEXT NOT NULL,
                    CONSTRAINT VETERINARIO_EMAIL_UK UNIQUE(VET_EMAIL)
                )"
            );

            // Tabla PEDIDO
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS PEDIDO (
                    PED_ID INTEGER PRIMARY KEY,
                    PED_CLIENTE INTEGER NOT NULL,
                    PED_PRODUCTO INTEGER NOT NULL,
                    PED_PRECIO REAL NOT NULL,
                    PED_FECHA DATE NOT NULL,
                    PED_ESTATUS TEXT NOT NULL,
                    CONSTRAINT PEDIDO_CLIENTE_FK FOREIGN KEY(PED_CLIENTE) REFERENCES CLIENTE(CLI_ID),
                    CONSTRAINT PEDIDO_PRODUCTO_FK FOREIGN KEY(PED_PRODUCTO) REFERENCES PRODUCTO(PRO_ID)
                )"
            );

            // Tabla CITA
            self::$pdo->exec(
                "CREATE TABLE IF NOT EXISTS CITA (
                    CIT_ID INTEGER PRIMARY KEY,
                    CIT_MASCOTA INTEGER NOT NULL,
                    CIT_TIPO TEXT NOT NULL,
                    CIT_HORARIO TEXT,
                    CIT_FECHA DATE NOT NULL,
                    CIT_VET INTEGER NOT NULL,
                    CIT_DESCRIPCION TEXT,
                    CIT_ESTATUS TEXT NOT NULL,
                    CONSTRAINT CITA_MASCOTA_FK FOREIGN KEY(CIT_MASCOTA) REFERENCES MASCOTA(MAS_ID),
                    CONSTRAINT CITA_VET_FK FOREIGN KEY(CIT_VET) REFERENCES VETERINARIO(VET_ID)
                )"
            );




            self::$pdo->exec(
                'CREATE TABLE IF NOT EXISTS ROL (
                  ROL_ID TEXT NOT NULL,
                  ROL_DESCRIPCION TEXT NOT NULL,
                  CONSTRAINT ROL_PK
                   PRIMARY KEY(ROL_ID),
                  CONSTRAINT ROL_ID_NV
                   CHECK(LENGTH(ROL_ID) > 0),
                  CONSTRAINT ROL_DESCR_UNQ
                   UNIQUE(ROL_DESCRIPCION),
                  CONSTRAINT ROL_DESCR_NV
                   CHECK(LENGTH(ROL_DESCRIPCION) > 0)
                 )'
               );
               self::$pdo->exec(
                'CREATE TABLE IF NOT EXISTS USU_ROL (
                   USU_ID INTEGER NOT NULL,
                   ROL_ID TEXT NOT NULL,
                   CONSTRAINT USU_ROL_PK
                    PRIMARY KEY(USU_ID, ROL_ID),
                   CONSTRAINT USU_ROL_USU_FK
                    FOREIGN KEY (USU_ID) REFERENCES USUARIO(USU_ID),
                   CONSTRAINT USU_ROL_ROL_FK
                    FOREIGN KEY (ROL_ID) REFERENCES ROL(ROL_ID)
                  )'
               );


               if (selectFirst(
                pdo: self::$pdo,
                from: ROL,
                where: [ROL_ID => ROL_ID_ADMINISTRADOR]
               ) === false) {
                insert(
                 pdo: self::$pdo,
                 into: ROL,
                 values: [
                  ROL_ID => ROL_ID_ADMINISTRADOR,
                  ROL_DESCRIPCION => "Administra el sistema."
                 ]
                );
               }
            
               if (selectFirst(self::$pdo, ROL, [ROL_ID => ROL_ID_CLIENTE]) === false) {
                insert(
                 pdo: self::$pdo,
                 into: ROL,
                 values: [
                  ROL_ID => ROL_ID_CLIENTE,
                  ROL_DESCRIPCION => "Realiza compras."
                 ]
                );
               }

               if (selectFirst(self::$pdo, USUARIO, [USU_NOMBRE => "susana"]) === false) {
                insert(
                 pdo: self::$pdo,
                 into: USUARIO,
                 values: [
                    USU_NOMBRE => "susana" ,
                    USU_EMAIL => "susana@gmail.com",
                    USU_DIRECCION => "some where",
                    USU_TELEFONO => "123",
                    USU_ESTATUS => "1",
                    USU_PASSWORD => password_hash("123", PASSWORD_DEFAULT)
                 ]
                );
                $usuId = self::$pdo->lastInsertId();
                insertBridges(
                 pdo: self::$pdo,
                 into: USU_ROL,
                 valuesDePadre: [USU_ID => $usuId],
                 valueDeHijos: [ROL_ID => [ROL_ID_ADMINISTRADOR]]
                );
               }

               if (selectFirst(self::$pdo, USUARIO, [USU_NOMBRE => "jesus"]) === false) {
                insert(
                 pdo: self::$pdo,
                 into: USUARIO,
                 values: [
                    USU_NOMBRE => "jesus" ,
                    USU_EMAIL => "jesus@gmail.com",
                    USU_DIRECCION => "some where",
                    USU_TELEFONO => "123",
                    USU_ESTATUS => "1",
                    USU_PASSWORD => password_hash("123", PASSWORD_DEFAULT)
                 ]
                );
                $usuId = self::$pdo->lastInsertId();
                insertBridges(
                 pdo: self::$pdo,
                 into: USU_ROL,
                 valuesDePadre: [USU_ID => $usuId],
                 valueDeHijos: [ROL_ID => [ROL_ID_CLIENTE]]
                );
               }
               
              
              

           

               


               
        }



        

        return self::$pdo;
    }
}

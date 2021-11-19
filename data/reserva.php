<?php
session_start();
require_once("db.php");

if ($_POST) {
    if ($_POST['accion'] == "registrar") {

        $fecha = $_POST['fecha'];
        $ambiente = $_POST['ambiente'];
        $user = $_SESSION['usuario'];
        $userId = $user['id'];
        
        $sql = "INSERT INTO reservas(fecha, ambiente_id, usuario_id)
        VALUES('$fecha', $ambiente, $userId)" ;

        $verificar_fecha=mysqli_query($db, "SELECT * FROM reservas WHERE fecha='$fecha'");

        if (mysqli_num_rows($verificar_fecha)>0) {
            echo "Ya existe";
            exit();
        }

        $result = $db->query($sql);
        if ($result > 0) {
        echo "exito";

        } else {
        echo "error";
            }
        }

    }

 else {
    if ($_GET['accion'] == "reserva_usuario") {
        $user = $_SESSION['usuario'];
        $userId = $user['id'];
        $sql = "SELECT C.nombre AS edificio, B.nombre AS ambiente, A.fecha 
                    FROM `reservas` A INNER JOIN ambientes B
                    ON ambiente_id=B.id INNER JOIN edificios C
                    ON B.edificio_id=C.id
                    WHERE A.usuario_id=$userId";
        $result = $db->query($sql);
        $reservas = array();
        if ($result->num_rows > 0) {
            while ($reserva = $result->fetch_assoc()) {
                $reservas[] = $reserva;
            }
        }
        echo json_encode($reservas);
    }
}

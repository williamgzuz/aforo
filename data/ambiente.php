<?php
require_once("db.php");


if ($_POST) {




} else {
    if ($_GET['accion'] == "listarPorEdificio") {
        $edificio = $_GET['id'];
        $ambientes = array();
        $sql = "SELECT id, nombre, aforo
                FROM ambientes
                WHERE edificio_id = $edificio";
        $result = $db->query($sql);
        while ($ambiente = $result->fetch_assoc()) {
            $ambientes[] = $ambiente;
        }
        if (count($ambientes) > 0) {
            echo json_encode($ambientes);
        } else {
            echo "NoData";
        }
        
    }
    if ($_GET['accion']=="MostrarAforoRestante") {
        $ambiente =$_GET['id'];
        $aforo=$_GET['aforo'];
        $reservas=$_GET['ambiente_id'];

        $consulta="SELECT COUNT(ambiente_id) FROM reservas INNER JOIN ambientes ON reservas.ambiente_id=ambientes.id WHERE ambiente_id=$ambiente";

        $result = $db->query($consulta);
        
        if (count($ambiente)>0) {
            echo "no hay error";
        }

    }

  

}

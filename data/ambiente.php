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
    if ($_GET['accion']=="aforo") {
        $ambiente =$_GET['id'];
        $aforo=$_GET['aforo'];
        $reservas=$_GET['ambiente_id'];

        $consulta="SELECT COUNT(ambiente_id) FROM reservas INNER JOIN ambientes ON 
        reservas.ambiente_id=ambientes.id WHERE ambiente_id=$ambiente";
        $aforos=array();
        $result = $db->query($consulta);

        while ($aforo=$result->fetch_assoc()) {
            $aforos[]=$aforo;
        }
        
        if (count($aforos)>0) {
            echo json_encode($aforos);
        }else {
            echo "NoError";
        }

    }

  

}

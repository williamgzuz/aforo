<?php
require_once("db.php");



if ($_POST) {

    $continente=$_POST['continente'];
   
    $sql="SELECT nombre,(aforo-(SELECT COUNT(id) FROM reservas WHERE ambiente_id='$continente')) 
    FROM ambientes WHERE id='$continente'";
    
    $result=mysqli_query($db,$sql);
    $cadena="<div id='aforo'>";
    while ($ver=mysqli_fetch_row($result)) {
        $cadena=$cadena.'<option value='.$ver[0].'>'.utf8_encode($ver[1]).'</option>';
    }
    echo $cadena."</div>";



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
    

  

}

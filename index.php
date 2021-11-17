<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once("includes/css_link.php")
    ?>
    <title>Reservas de ambientes</title>
</head>

<body>
    <?php
    include_once("includes/navbar.inc.php");
    ?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div id="edificios">

                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            Reserva de ambientes
                        </div>
                        <div class="card-body">
                            <h3 id="tituloEdificio"></h3>
                            <p class="lead" id="tituloAforo"></p>
                            <form>
                                <div class="form-group">
                                    <label>Fecha</label>
                                    <input type="date" id="fecha" class="form-control" placeholder="Ingrese la fecha" 
                                    aria-describedby="helpFecha">
                                    <small id="helpFecha" class="text-muted">Fecha de la reserva</small>
                                </div>
                                <div class="form-group">
                                    <label>Ambiente</label>
                                    <select class="form-control" id="ambiente" onclick="MostrarConteo()">
                                        <option value="-1">--Seleccione--</option>

                                    </select>
                                    <h5 id="aforounico" >Hola Mundo</h5>
                                    <small id="helpAmbiente" class="text-muted">Ambiente a reservar</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" id="btnRegistrar">Registrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    include_once("includes/js_link.php")
    ?>
    <script>

      

        $(document).ready(function() {
            cargarEdificios();
            $("#btnRegistrar").click(function() {
       
                const fecha = $("#fecha").val();
                const ambiente = $("#ambiente").val()

                if (fecha == "" || ambiente == -1) {
                    Swal.fire({
                        text: 'No se ingresaron los datos correctamente',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                $.post("data/reserva.php", {
                    "accion": "registrar",
                    fecha,
                    ambiente
                }).then(function(response) {
                    if (response == "exito") {
                        
                        Swal.fire({
                            text: 'La reserva ha sido registrada',
                            icon: 'info',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            text: 'No se ha podido completar el registro',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
            })
        })

        function cargarEdificios() {
            $.ajax({
                url: "data/edificio.php?accion=listar",
                type: "GET",
                async: true,
                success: function(response) {
                    if (response != "NoData") {
                        let data = JSON.parse(response);
                        let html = "";
                        for (let idx = 0; idx < data.length; idx++) {
                            const element = data[idx];
                            html += "<div class='card'>";
                            html += "<div class='card-body'>";
                            html += "<h5 class='card-title'>" + element.nombre + "</h5>";
                            html += "<h6 class='text-muted'> Aforo: " + element.aforo + "</h6>";
                            html += "<p class='text-muted'>Dirección: " + element.direccion + "</p>"
                            html += "<button type='button' class='btn btn-sm btn-outline-primary'";
                            html += "onclick='mostrarDetalleEdificio(" + element.id + ")'>Seleccionar</button>"
                            html += "</div>";
                            html += "</div>"
                        }
                        $("#edificios").html(html);
                    }
                }
            })
        }

        

        function mostrarDetalleEdificio(edificioId) {
            $("#ambiente").children('option:not(:first)').remove();
            $.when(
                $.get("data/edificio.php?accion=obtener&id=" + edificioId),
                $.get("data/ambiente.php?accion=listarPorEdificio&id=" + edificioId)
            ).then(function(edificioRequest, ambienteRequest) {
                let edificioResponse = edificioRequest[0];
                let ambienteResponse = ambienteRequest[0];
                if (edificioResponse != "NoData") {
                    let data = JSON.parse(edificioResponse);
                    $("#tituloEdificio").html(data.nombre);
                    $("#tituloAforo").html("Aforo: " + data.aforo);
                }

                if (ambienteResponse != "NoData") {
                    let data = JSON.parse(ambienteResponse);
                    for (let idx = 0; idx < data.length; idx++) {
                        const ambiente = data[idx];
                        $("#ambiente").append('<option value="' + ambiente.id + '"" onclick=MostrarConteo()">' + ambiente.nombre + ' (aforo' + ambiente.aforo + ')</option>');
                        function MostrarConteo(ambienteId) {
                            $.when(
                            $.get("data/ambiente.php?accion=aforo$aforo="+ambienteId)
                        ).then(function(ambienteRequest){
                            let ambienteResponse = ambienteRequest[0];
                            if (ambienteResponse != "NoError") {
                            let data=JSON.parse(ambienteResponse);
                            $("#aforounico").html(data.aforo);                          
                            }
                        })
                        }
                        
                        

                    }

                }

            })


        }


        
    </script>

    
</body>

</html>
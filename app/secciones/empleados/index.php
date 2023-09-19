<?php
include("../../bd.php");

//Select con subconsulta para sacar el puesto
$sentencia=$conexion->prepare("SELECT *,
(SELECT nombredelpuesto 
FROM tbl_puestos 
WHERE tbl_puestos.id=tbl_empleados.idpuesto limit 1) as puesto

FROM `tbl_empleados`");
$sentencia->execute();
$lista_tbl_empleados=$sentencia->fetchAll(PDO::FETCH_ASSOC);

//print_r($lista_tbl_puestos);
?>


<?php include("../../templates/header.php"); ?>

<br>

<div class="card">
    <div class="card-header">
        
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Registro</a>
    </div>
    <div class="card-body">
        
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Foto</th>
                        <th scope="col">CV</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Fecha de ingreso</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($lista_tbl_empleados as $registro) { ?>

                    <tr class="">
                        <td><?php echo $registro['id']; ?></td>
                        <td scope="row">

                            <?php echo $registro['primernombre']; ?> 
                            <?php echo $registro['segundonombre']; ?> 
                            <?php echo $registro['primerapellido']; ?> 
                            <?php echo $registro['segundoapellido']; ?> 
                    
                    
                        </td>
                        <td>
                            <img src="../../img/<?php echo $registro['foto']; ?>" alt="" width="50">

                        </td>
                        <td><?php echo $registro['cv']; ?></td>
                        <td><?php echo $registro['puesto']; ?></td>
                        <td><?php echo $registro['fechadeingreso']; ?></td>
                        <td><a name="" id="" class="btn btn-primary" href="#" role="button">Carta</a>|
                        <a name="" id="" class="btn btn-info" href="#" role="button">Editar</a>|
                        <a name="" id="" class="btn btn-danger" href="#" role="button">Eliminar</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        

    </div>
    <div class="card-footer text-muted">
        
    </div>
</div>



<?php include("../../templates/footer.php"); ?>
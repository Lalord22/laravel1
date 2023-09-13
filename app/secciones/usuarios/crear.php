<?php include("../../templates/header.php"); ?>

<br/>

<div class="card">
    <div class="card-header">
        Datos del usuario
    </div>
    <div class="card-body">
        
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
              <label for="usuario" class="form-label">Nombre del usuario</label>
              <input type="text"
                class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Nombre del usuario">
              
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password"
                class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Escriba su contraseña">
        
            </div>

            <div class="mb-3">
              <label for="correo" class="form-label">Correo</label>
              <input type="email"
                class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Escriba su Correo">
              
            </div>

            <button type="submit" class="btn btn-success">Agregar</button>
            <a name="" id="" class="btn btn-danger" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    
</div>

<?php include("../../templates/footer.php"); ?>
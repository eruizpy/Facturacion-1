<?php 
session_start();
if($_SESSION['rol'] !=1 and $_SESSION['rol'] !=2 ){
    header("location:sistema.php");
  }
include("includes/conexion.php");

?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <title>Lista de Proveedores</title>
</head>
<body>
  <div class="container-fluid">
   <div class="">

     <?php include("includes/header.php"); ?>
     <div class="row">
       <div class="col-8">
          <h2 class="text-secondary d-inline-block"><i class="far fa-building"></i> Lista de Proveedores</h2>
          <a href="agregar-proveedor.php" class="d-inline-block ml-1 btn btn-primary"><i class="fas fa-user-plus"></i> Agregar Proveedor</a>
          <hr>
        </div>
        <div class="col-4 mt-2">
              <form class="form-inline my-2 my-lg-0" action="buscar-proveedor.php" method="get">
                <input class="form-control mr-sm-2" type="search" name="busqueda" placeholder="Buscar" aria-label="Search" value="">
                <button class="btn btn-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
              </form> 
              <hr>
        </div>
        
      </div>

    <div class="row" id="table">
      <div class="col-12">
        <table class="table table-striped table-dark table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Nombre</th>
              <th scope="col">Contacto</th>
              <th scope="col">Teléfono</th>
              <th scope="col" class="">Dirección</th>
              <th scope="col" class="">Fecha</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <?php
          $sqlPaginador="SELECT COUNT(*) as total_registro FROM proveedor WHERE estatus=1";
          $rPaginador=mysqli_query($db,$sqlPaginador);
          $rsPaginador=mysqli_fetch_array($rPaginador);
          $total_registro=$rsPaginador['total_registro'];

          $por_pagina=10;

          if(empty($_GET['pagina'])){
            $pagina=1;
          }else{
            $pagina=$_GET['pagina'];
          }

          $desde=($pagina-1 )*$por_pagina;
          $total_paginas=ceil($total_registro/$por_pagina);//ceil redondea a entero

          $sqlLista="SELECT * FROM proveedor  WHERE estatus=1 ORDER BY codproveedor ASC LIMIT $desde,$por_pagina ";
          $rLista=mysqli_query($db,$sqlLista);
          mysqli_close($db);
          $rowsLista=mysqli_num_rows($rLista);
          if($rowsLista >0){
            while ($rsLista=mysqli_fetch_array($rLista)){
              $formato='Y-m-d H:i:s';
              $fecha=DateTime::createFromFormat($formato,$rsLista['date_add']);
        
          ?>
              <tbody>
                <tr>
                  <th scope="row"><?php echo $rsLista['codproveedor'] ?></th>
                  <td><?php echo $rsLista['proveedor'] ?></td>
                  <td><?php echo $rsLista['contacto'] ?></td>
                  <td><?php echo $rsLista['telefono'] ?></td>
                  <td><?php echo $rsLista['direccion'] ?></td>
                  <td><?php echo $fecha->format('d-m-Y') ?></td>
                  <td>
                    <a href="editar-proveedor.php?id=<?php echo $rsLista['codproveedor'] ?>" class="text-info"><i class="fas fa-edit"></i> Editar</a>
                  

                    |
                    <a href="eliminar-proveedor.php?id=<?php echo $rsLista['codproveedor'] ?>" class="text-danger"><i class="fas fa-trash-alt"></i> Eliminar</a>
                    

                  </td>
                </tr>
              </tbody>
          <?php 
              
            }
          }
        
          ?>
        </table>
      </div>
    </div>
    <div class="row">
      <div class=" col-12 paginador">
        <ul class="bg-dark">
          <?php 
            if($pagina !=1){



           ?>
          <li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
          <li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fas fa-backward"></i></a></li>
          

          <?php 
          }
          for($i=1;$i<= $total_paginas;$i++){
            if($i== $pagina){
              echo '<li class="pageSelected">'.$i.'</a></li>';
            }else{
              echo '<li class=""><a href="?pagina='.$i.'">'.$i.'</a></li>';
            }
            
          }

          if($pagina != $total_paginas){

           ?>
          
          
        
          <li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fas fa-forward"></i></a></li>
          <li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fas fa-step-forward"></i></a></li>
          <?php 
          }
           ?>
        </ul>
      </div>
    </div>
    
    <?php include("includes/footer.php"); ?>

  </div>

</div>



<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="javascript/funciones.js"></script>
</body>
</html>
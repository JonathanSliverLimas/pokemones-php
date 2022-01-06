<?php
    error_reporting(0);
    $ruta = "../";
    include_once("{$ruta}inc/cnx.php");

    $msgGeneral = "";
    $validacion = True;

    if($_POST['enviado']){
        $inputNumero = $_POST['inputNumero'];
        $inputNombre = $_POST['inputNombre'];
        $inputPeso = $_POST['inputPeso'];
        $inputAltura = $_POST['inputAltura'];

        if(!$inputNumero){
            $validacion = False;
            $valiNumero = "is-invalid";
            $msgNumero = '<div id="validationServer03Feedback" class="invalid-feedback">Campo Obligatorio</div>';
        }else{
            $valiNumero = "is-valid";
        }

        if(!$inputNombre){
            $validacion = False;
            $valiNombre = "is-invalid";
            $msgNombre = '<div id="validationServer03Feedback" class="invalid-feedback">Campo Obligatorio</div>';
        }else{
            $valiNombre = "is-valid";
        }

        if(!$inputPeso){
            $validacion = False;
            $valiPeso = "is-invalid";
            $msgPeso = '<div id="validationServer03Feedback" class="invalid-feedback">Campo Obligatorio</div>';
        }else{
            $valiPeso = "is-valid";
        }

        if(!$inputAltura){
            $validacion = False;
            $valiAltura = "is-invalid";
            $msgAltura = '<div id="validationServer03Feedback" class="invalid-feedback">Campo Obligatorio</div>';
        }else{
            $valiAltura = "is-valid";
        }

        if($validacion){
            $sqlInsert = "INSERT INTO `pokemon`(`numero_pokedex`, `nombre`, `peso`, `altura`) VALUES (?, ?, ?, ?);";
			$psInsert = $cnx->prepare($sqlInsert);
			$psInsert->execute(array($inputNumero, $inputNombre, $inputPeso, $inputAltura));
			if($psInsert->rowCount()){
				$msgGeneral = '<div class="row"><div class="col-md-12"><div class="alert alert-success" role="alert">Pokemon agregado correctamente</div></div></div>';
			}else{
				$arr = $psInsert->errorInfo();
                $msgGeneral = '<div class="row"><div class="col-md-12"><div class="alert alert-danger" role="alert">Error: Ingresar->'. $arr[2] .'</div></div></div>';
			}
            $psInsert=null;
            $cnx = null;
        }else{
            $msgGeneral = '<div class="row"><div class="col-md-12"><div class="alert alert-warning" role="alert">Los campos marcados en rojo son obligatorios</div></div></div>';
        }

    }

    include("{$ruta}inc/header.php");
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Agregar Pokemon</h1>
        </div>
    </div>
    <?php echo $msgGeneral; ?>
    <div class="row">
        <div class="col-md-12">
            <form class="row g-3" action="agregarPkm.php" method="POST">
                <div class="col-md-3">
                    <label for="inputNumero" class="form-label">Número Pokedex:</label>
                    <input type="number" class="form-control form-control-sm <?php echo $valiNumero; ?>" name="inputNumero" id="inputNumero" placeholder="Número Pokedex" min=1 step="1" value="<?php echo $inputNumero; ?>">
                    <?php echo $msgNumero; ?>
                </div>
                <div class="col-md-3">
                    <label for="inputNombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control form-control-sm <?php echo $valiNombre; ?>" name="inputNombre" id="inputNombre" placeholder="Nombre Pokemon" value="<?php echo $inputNombre; ?>">
                    <?php echo $msgNombre; ?>
                </div>
                <div class="col-md-3">
                    <label for="inputPeso" class="form-label">Peso:</label>
                    <input type="number" class="form-control form-control-sm <?php echo $valiPeso; ?>" name="inputPeso" id="inputPeso" placeholder="Peso Pokemon" min=0.1 step="0.1" value="<?php echo $inputPeso; ?>">
                    <?php echo $msgPeso; ?>
                </div>
                <div class="col-md-3">
                    <label for="inputAltura" class="form-label">Altura:</label>
                    <input type="number" class="form-control form-control-sm <?php echo $valiAltura; ?>" name="inputAltura" id="inputAltura" placeholder="Altura Pokemon" min=0.1 step="0.1" value="<?php echo $inputAltura; ?>">
                    <?php echo $msgAltura; ?>
                </div>
                <div class="col-12">
                    <input type="hidden" name="enviado" id="enviado" value="1">
                    <button type="submit" class="btn btn-primary">Agregar Pokemon</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include("{$ruta}inc/footer.php"); ?>
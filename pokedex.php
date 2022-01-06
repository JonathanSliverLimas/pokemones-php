<?php
    error_reporting(0);
    $ruta = "";
    include_once("{$ruta}inc/cnx.php");

    $where = "";

    if($_POST['enviado']){
        $inputDesdeNumero = $_POST['inputDesdeNumero'];
        $inputHastaNumero = $_POST['inputHastaNumero'];
        $inputNombre = $_POST['inputNombre'];
        $inputDesdePeso = $_POST['inputDesdePeso'];
        $inputHastaPeso = $_POST['inputHastaPeso'];
        $inputDesdeAltura = $_POST['inputDesdeAltura'];
        $inputHastaAltura = $_POST['inputHastaAltura'];

        $inputDesdeNumero = $inputDesdeNumero?$inputDesdeNumero:$inputHastaNumero;
        $inputHastaNumero = $inputHastaNumero?$inputHastaNumero:$inputDesdeNumero;
        $inputDesdePeso = $inputDesdePeso?$inputDesdePeso:$inputHastaPeso;
        $inputHastaPeso = $inputHastaPeso?$inputHastaPeso:$inputDesdePeso;
        $inputDesdeAltura = $inputDesdeAltura?$inputDesdeAltura:$inputHastaAltura;
        $inputHastaAltura = $inputHastaAltura?$inputHastaAltura:$inputDesdeAltura;

        if($inputHastaNumero < $inputDesdeNumero){$aux = $inputDesdeNumero;$inputDesdeNumero = $inputHastaNumero;$inputHastaNumero = $aux;}
        if($inputHastaPeso < $inputDesdePeso){$aux = $inputDesdePeso;$inputDesdePeso = $inputHastaPeso;$inputHastaPeso = $aux;}
        if($inputHastaAltura < $inputDesdeAltura){$aux = $inputDesdeAltura;$inputDesdeAltura = $inputHastaAltura;$inputHastaAltura = $aux;}

        $where .= $inputNombre ? " AND `pkm`.`nombre` LIKE '%$inputNombre%'" : "";
        $where .= $inputDesdeNumero || $inputHastaNumero?" AND `pkm`.`numero_pokedex` BETWEEN $inputDesdeNumero AND $inputHastaNumero":"";
        $where .= $inputDesdePeso || $inputHastaPeso?" AND `pkm`.`peso` BETWEEN $inputDesdePeso AND $inputHastaPeso":"";
        $where .= $inputDesdeAltura || $inputHastaAltura?" AND `pkm`.`altura` BETWEEN $inputDesdeAltura AND $inputHastaAltura":"";
    }

    $sqlPkm = "	SELECT DISTINCT(`pkm`.`numero_pokedex`), `pkm`.`nombre`
                FROM `pokemon` `pkm`
                JOIN `pokemon_tipo` `pkmtip` ON `pkmtip`.`numero_pokedex` = `pkm`.`numero_pokedex`
                JOIN `tipo` `tip` ON `tip`.`id_tipo` = `pkmtip`.`id_tipo`
                JOIN `pokemon_movimiento_forma` `pmf` ON `pmf`.`numero_pokedex` = `pkm`.`numero_pokedex`
                JOIN `movimiento` `mov` ON `mov`.`id_movimiento` = `pmf`.`id_movimiento`
                JOIN `estadisticas_base` `est` ON `est`.`numero_pokedex` = `pkm`.`numero_pokedex`
                WHERE 1 $where
                ORDER BY `pkm`.`numero_pokedex`;";
    //echo $sqlPkm;
    $ps = $cnx->prepare($sqlPkm);
    $ps->execute();
    $resPkm = $ps->fetchAll();

    $sqlTipo = "	SELECT `pkm`.`numero_pokedex`, `tip`.*
                    FROM `pokemon` `pkm`
                    JOIN `pokemon_tipo` `pkmtip` ON `pkm`.`numero_pokedex` = `pkmtip`.`numero_pokedex`
                    JOIN `tipo` `tip` ON `pkmtip`.`id_tipo` = `tip`.`id_tipo`
                    WHERE 1;";
    $ps = $cnx->prepare($sqlTipo);
    $ps->execute();
    $resTipo = $ps->fetchAll();

    $sqlDebi = "	SELECT `deb`.`id_debilidad`, `tip`.`id_tipo`, `tip`.`nombre`
					FROM `pokemon` `pkm`
					JOIN `pokemon_tipo` `pkmtip` ON `pkm`.`numero_pokedex` = `pkmtip`.`numero_pokedex`
					JOIN `debilidad` `deb` ON `pkmtip`.`id_tipo` = `deb`.`id_tipo`
					JOIN `tipo` `tip` ON `deb`.`id_debilidad` = `tip`.`id_tipo`
					WHERE 1;";
    $ps = $cnx->prepare($sqlDebi);
    $ps->execute();
    $resDebi = $ps->fetchAll();

    include("{$ruta}inc/header.php");
?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Pokedex</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <form class="row g-3" action="pokedex.php" method="POST">
                    <div class="col-md-12">
                        <label class="form-label">Número Pokedex</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control form-control-sm" name="inputDesdeNumero" id="inputDesdeNumero" placeholder="Desde" min=1 step=1 value="<?php echo $inputDesdeNumero; ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control form-control-sm" name="inputHastaNumero" id="inputHastaNumero" placeholder="Hasta" min=1 step=1 value="<?php echo $inputHastaNumero; ?>">
                    </div>
                    <div class="col-12">
                        <label for="inputNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control form-control-sm" name="inputNombre" id="inputNombre" placeholder="Nombre Pokemon" value="<?php echo $inputNombre; ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Peso</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control form-control-sm" name="inputDesdePeso" id="inputDesdePeso" placeholder="Desde" min=0.1 step="0.1" value="<?php echo $inputDesdePeso; ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control form-control-sm" name="inputHastaPeso" id="inputHastaPeso" placeholder="Hasta" min=0.1 step="0.1" value="<?php echo $inputHastaPeso; ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Altura</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control form-control-sm" name="inputDesdeAltura" id="inputDesdeAltura" placeholder="Desde" min=0.1 step="0.1" value="<?php echo $inputDesdeAltura; ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control form-control-sm" name="inputHastaAltura" id="inputHastaAltura" placeholder="Hasta" min=0.1 step="0.1" value="<?php echo $inputHastaAltura; ?>">
                    </div>
                    <div class="col-12">
                        <input type="hidden" name="enviado" id="enviado" value="1">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </form>
            </div>
            <div class="col-md-9">
                <table class="table table-dark table-bordered text-center align-middle">
                    <tbody>
                    <?php
                        foreach($resPkm as $datoPkm){
                            $numero = "";
                            if($datoPkm['numero_pokedex'] < 10){
                                $numero = "00".$datoPkm['numero_pokedex'];
                            }elseif($datoPkm['numero_pokedex'] > 9 && $datoPkm['numero_pokedex'] < 100){
                                $numero = "0".$datoPkm['numero_pokedex'];
                            }else{
                                $numero = $datoPkm['numero_pokedex'];
                            }
                            $rutaImg = "inc/img/pokemon/$numero.webp";
                    ?>
                        <tr>
                            <td rowspan="5" colspan="2" class="align-bottom">
                                <img class="img-fluid" src="<?php echo $rutaImg; ?>" alt="<?php echo $numero; ?>">
                                <p><?php echo "#$numero"." ".$datoPkm['nombre']; ?></p>
                            </td>
                            <td>Altura</td>
                            <td><?php echo $datoPkm['altura']; ?></td>
                            <td>PS</td>
                            <td>2</td>
                            <td>Ataque</td>
                        </tr>
                        <tr>
                            <td>Peso</td>
                            <td><?php echo $datoPkm['peso']; ?></td>
                            <td>Atq</td>
                            <td>4</td>
                            <td>Listado</td>
                        </tr>
                        <tr>
                            <td>Tipo</td>
                            <td>
                                <?php foreach($resTipo as $datoTipo){
                                        if($datoTipo['numero_pokedex'] == $datoPkm['numero_pokedex']) {?>
						                    <img class="img-fluid" src="inc/img/type/<?php echo $datoTipo['id_tipo'];?>.webp" alt="<?php echo $datoTipo['nombre'];?>" style="height: 15px; width: 15px;">
					            <?php } } ?>
                            </td>
                            <td>Def</td>
                            <td>6</td>
                            <td>x</td>
                        </tr>
                        <tr>
                            <td>Debilidad</td>
                            <td>1</td>
                            <!--td>
                            <?php foreach($resDebi as $datoDebi){
                                    if($datoDebi['id_tipo'] == $datoTipo['id_tipo']) {?>
						                <img class="img-fluid" src="inc/img/type/<?php echo $datoDebi['id_debilidad'];?>.webp" alt="<?php echo $datoDebi['nombre'];?>" style="height: 15px; width: 15px;">
					        <?php } } ?>
                            </td-->
                            <td>Especial</td>
                            <td>8</td>
                            <td>x</td>
                        </tr>
                        <tr>
                            <td><a class="link-primary" href="<?php echo $ruta; ?>CRUDPkm/modificarPkm.php?numPkm=<?php echo $datoPkm['numero_pokedex']; ?>&x=">Modificar</a></td>
                            <td>eliminar</td>
                            <td>Vel</td>
                            <td>9</td>
                            <td>x</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-1">
                resultado de filtros
            </div>
        </div>
    </div>
<?php include("{$ruta}inc/footer.php"); ?>
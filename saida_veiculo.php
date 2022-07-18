<?php
include("conn.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 0;
}

$sql = "SELECT t.nome as nomeTipo,
 e.placa, e.observacao, e.chave,
 DATE_FORMAT(e.dataEntrada, '%Y-%m-%d\T%H:%i:%s') as dataEnt,
 c.nome as nomeCor,
 cv.nome as nomeCv,
 v.valorMeiaHora, v.valorHorasAdicionais, v.valorDia
FROM estacionamento as e
inner join tipo as t on e.tipoID = t.tipoID
inner join cor as c on e.corID = c.corID
inner join convenio cv on e.convenioID = cv.convenioID
inner join valor v on t.tipoID = v.tipoID
WHERE estacionamentoID = '$id'";

$result = mysqli_query($conn, $sql);

if($_SERVER['REQUEST_METHOD']== 'POST'){
    if(isset($_POST['btnSaida'])){
        $dataSaida = $_POST['dataSaida'];
        $valorTotal = $_POST['valorTotal'];
        $obs = $_POST['obs'];

        $sql = "UPDATE estacionamento 
        SET dataSaida = '$dataSaida',
        valorTotal = '$valorTotal',
        observacao = '$obs'
        WHERE estacionamentoID = '$id'";

        if(mysqli_query($conn,$sql)){
            echo "<script> alert('Saida de veiculo efetuada!')</script>)";
            echo "<script>location.href='index.php'</script>";
        }else{
            echo "Error.".mysqli_error($conn);
        }
        
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Estacionamento - Senac</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
   <?php include('nav.php')?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Saída de Veículo</h1>
                    <ol class="breadcrumb mb-4">
                    </ol>

                    <div class="row">
                        <form class="row g-3" method="POST">
                            <?php while ($linha = mysqli_fetch_assoc($result)) {

                                $timezone = new DateTimeZone('America/Sao_Paulo');
                                $dataSaida = new DateTime('now', $timezone);
                                $dataEntrada = new DateTime($linha['dataEnt'], $timezone);
                                $diferenca = $dataSaida->diff($dataEntrada);

                                $dias = $diferenca->format('%d');
                                $horas = $diferenca->format('%H');
                                $minutos = $diferenca->format('%i');
                                $valorTotal = 0;  

                                if ($dias > 0 || $horas > 10) {
                                    $valorTotal = ($dias + 1) * $linha['valorDiaria'];
                                } else if ($horas >= 10) {
                                    $valorTotal = $linha['valorDiaria'];
                                } else {
                                    if ($minutos <= 30 && $horas == 0) {
                                        $valorTotal = $linha['valorMeiaHora'];
                                    } else if ($minutos > 30 && $horas == 0) {
                                        $valorTotal = $linha['valorHorasAdicionais'];
                                    } else if ($horas > 0) {
                                        if ($minutos > 0) {
                                            $valorTotal = ($horas + 1) * $linha['valorHorasAdicionais'];
                                        } else {
                                            $valorTotal = $horas * $linha['valorHorasAdicionais'];
                                        }
                                    }
                                }






                            ?>
                                <div class="col-md-2">
                                    <label class="form-label">Placa</label>
                                    <input type="text" name="placa" class="form-control" disabled value="<?php echo $linha['placa'] ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Data e Hora de entrada</label>
                                    <input type="datetime-local" name="dataEnt" disabled class="form-control" value="<?php echo $linha['dataEnt'] ?>">
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tipo</label>
                                    <input type="text" name="tipo" class="form-control" value="<?php echo $linha['nomeTipo'] ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Cor</label>
                                    <input type="text" name="cor" class="form-control" value="<?php echo $linha['nomeCor'] ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Convênio</label>
                                    <input type="text" name="convenio" class="form-control" value="<?php echo $linha['nomeCv'] ?>">
                                </div>
                                <div class="col-md-12">
                                    <textarea name="obs" class="form-control" placeholder="Observação..."><?php echo $linha['observacao'] ?></textarea>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input name="chave" <?php if ($linha['chave'] == "1") {
                                                                echo "checked";
                                                            } ?> class="form-check-input" type="checkbox">
                                        <label class="form-check-label" for="gridCheck">
                                            Deixou a chave?
                                        </label>
                                    </div>
                                </div>
                                <div class="linha" style=" width: 100%; border-bottom: 2px solid darkred;">

                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Data e Hora de Saída</label>
                                    <input type="datetime-local" name="dataSaida" value="<?php echo date("Y-m-d\TH:i:s") ?>" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Valor Total</label>
                                    <input type="text" name="valorTotal" value="<?php echo $valorTotal ?>" class="form-control">
                                </div>
                            <?php } ?>
                            <div class="col-12">
                                <button type="submit" name="btnSaida" class="btn btn-danger">Efetuar Saída</button>
                            </div>
                        </form>
                    </div>


            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2022</div>

                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>
<?php
include("conn.php");

$sqlTipo = "SELECT * FROM tipo";
$resTipo = mysqli_query($conn, $sqlTipo);

$sqlCor = "SELECT * FROM cor";
$resCor = mysqli_query($conn, $sqlCor);

$sqlConv = "SELECT * FROM convenio WHERE ativo = '1'";
$resConv = mysqli_query($conn, $sqlConv);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnCadastrar'])) {
        $placa = (strlen($_POST['placa']) == 7) ? $_POST['placa'] : "";
        $dataEntrada = isset($_POST['dataEnt']) ? $_POST['dataEnt'] : "";
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : "";
        $cor = isset($_POST['cor']) ? $_POST['cor'] : "";
        $convenio = isset($_POST['convenio']) ? $_POST['convenio'] : "3";
        $obs = isset($_POST['obs']) ? $_POST['obs'] : "null";
        $chave = $_POST['chave'] == true ? 1 : 0;

        $sql = "INSERT INTO estacionamento (placa,dataEntrada,tipoID,
        corID,convenioID,observacao,chave)
        VALUES ('$placa','$dataEntrada','$tipo',
        '$cor','$convenio','$obs','$chave')";


        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Entrada de veículo efetuada!')</script>";
            echo "<script>location.href='index.php'</script>";
        } else {
            echo "Erro: " . mysqli_error($conn);
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
    <?php include('nav.php');?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Entrada de Veículo</h1>
                    <ol class="breadcrumb mb-4">
                    </ol>

                    <div class="row">
                        <form class="row g-3" method="POST">
                            <div class="col-md-2">
                                <label class="form-label">Placa</label>
                                <input type="text" name="placa" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Data e Hora de entrada</label>
                                <input type="datetime-local" name="dataEnt" value="<?php echo date("Y-m-d\TH:i:s") ?>" class="form-control">
                            </div>
                            <div class="col-md-7">

                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tipo</label>
                                <select name="tipo" class="form-select">
                                    <option selected>Selecione...</option>
                                    <?php while ($linha = mysqli_fetch_assoc($resTipo)) { ?>
                                        <option value="<?php echo $linha['tipoID'] ?>"><?php echo $linha['nome'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cor</label>
                                <select name="cor" class="form-select">
                                    <option selected>Selecione...</option>
                                    <?php while ($linha = mysqli_fetch_assoc($resCor)) { ?>
                                        <option value="<?php echo $linha['corID'] ?>"><?php echo $linha['nome'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Convênio</label>
                                <select name="convenio" class="form-select">
                                    <?php while ($linha = mysqli_fetch_assoc($resConv)) { ?>
                                        <option value="<?php echo $linha['convenioID'] ?>"><?php echo $linha['nome'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <textarea name="obs" class="form-control" placeholder="Observação..."></textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input name="chave" class="form-check-input" type="checkbox">
                                    <label class="form-check-label" for="gridCheck">
                                        Deixou a chave?
                                    </label>
                                </div>
                            </div>                       
                            <div class="col-12">
                                <button type="submit" name="btnCadastrar" class="btn btn-primary">Cadastrar</button>
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
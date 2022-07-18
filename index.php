<?php
include("conn.php");

$dataAtual =  new DateTime('now');
$dataAtual = $dataAtual->format('Y-m-d');
$vagas = 18;

$sql = "SELECT e.placa, e.dataEntrada, 
               e.estacionamentoID, t.nome
        FROM estacionamento e
        inner join tipo t on t.tipoID = e.tipoID
        WHERE dataEntrada LIKE '%$dataAtual%' and dataSaida is null";
$result = mysqli_query($conn, $sql);


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
    <?php include('nav.php'); ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Estacionamento</h1>
                    <ol class="breadcrumb mb-4">
                    </ol>

                    <div class="row">
                        <div class="col-xl-2 col-md-3">
                            <div class="card bg-primary text-white mb-4">
                                <button type="button" class="btn btn-dark" onclick="location.href='cadastro_veiculo.php'" id="btn_entrada">Entrada de Veículo</button>
                            </div>
                        </div>
                    </div>

                    <style>
                        .oc:hover {
                            border-color: red;
                            cursor: pointer;
                        }

                        .disp:hover {
                            border-color: green;
                            cursor: pointer;
                        }
                    </style>

                    <div class="row">
                        <?php while ($linha = mysqli_fetch_assoc($result)) { ?>
                            <div class="card m-2 oc" style="width: 130px">
                                <a href="saida_veiculo.php?id=<?php echo $linha['estacionamentoID'] ?>"> <img src="img/vermelho.png" class="card-img-top" alt="..."></a>
                                <div class="card-body">
                                    <p class="card-text"><?php echo $linha['placa']  ?></p>
                                    <p class="card-text"><?php echo $linha['nome']  ?></p>
                                </div>
                            </div>
                        <?php }

                        for ($i = 1; $i <= ($vagas - mysqli_num_rows($result)); $i++) { ?>
                            <div class="card m-2 disp" style="width: 130px">
                                <a href="cadastro_veiculo.php"> <img src="img/verde.png" class="card-img-top" alt="..."></a>
                                    <div class="card-body">
                                        <p class="card-text">VAGA</p>
                                    </div>                                
                            </div>

                        <?php  }
                        ?>
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
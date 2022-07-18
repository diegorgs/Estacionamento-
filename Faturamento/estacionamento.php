<?php
session_start();
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "estacionamento";

$where = " where 1=1";
$dtInicial = "";
$dtFinal = "";
$tipo = "";
$filtro="";

if (isset($_POST['dtFinal'])) {
    $dtFinal = $_POST['dtFinal'];
    $_SESSION['dtFinal'] = $dtFinal;
} else if (isset($_SESSION['dtFinal'])) {
    $dtFinal = $_SESSION['dtFinal'];
}
if (isset($_POST['dtInicial'])) {
    $dtInicial = $_POST['dtInicial'];
    $_SESSION['dtInicial'] = $dtInicial;
} else if (isset($_SESSION['dtInicial'])) {
    $dtInicial = $_SESSION['dtInicial'];
}


if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
    $_SESSION['tipo'] = $tipo;
} else if (isset($_SESSION['tipo'])) {
    $tipo = $_SESSION['tipo'];
}

if (strlen($dtInicial) == 10) {
    if (strlen($dtFinal) == 10) {
        $where .= " and Entrada >= '$dtInicial' 
        and Entrada <= '$dtFinal'";
        $filtro .= sprintf("De %s até %s", 
            date("d/m/Y", strtotime($dtInicial)),
            date("d/m/Y", strtotime($dtFinal)));
    } else {
        $where .= " and Entrada = '$dtInicial'";
        $filtro .= sprintf("Data: %s", 
        date("d/m/Y", strtotime($dtInicial)));
        
    }
}
if (strlen($tipo)>0) {
    $where .= " and nome = '$tipo' ";
    $filtro .= sprintf(" Tipo de Veículo: %s", $tipo);
}
$_SESSION["filtro"] = $filtro;
$_SESSION["criterio"] = $where;

$conn = mysqli_connect($servidor, $usuario, $senha, $banco);

$sql = "SELECT 
CEIL(COUNT(*)/10)
FROM estacionamentoDiario $where";
$total = mysqli_query($conn, $sql);
$paginacao = mysqli_fetch_array($total);
$totalPaginas = $paginacao[0];

$data = "2022/05/12";
if (isset($_GET['pag'])) {
    $pagina = $_GET['pag'];
} else {
    $pagina = 1;
}

$anterior = $pagina - 1;
$dAnt = "";
$depois = "";
if ($anterior == 0) {
    $anterior = 1;
    $dAnt = "disabled";
}
$proximo = $pagina + 1;
if ($proximo > $totalPaginas) {
    $proximo = $totalPaginas;
    $depois = "disabled";
}
$posicao = ($pagina - 1) * 10;

$sql = "SELECT * 
        FROM estacionamentoDiario $where
        LIMIT $posicao,10";
$result = mysqli_query($conn, $sql);

?>
<!doctype html>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Estacionamento</title>
</head>

<body>
    <div class="container">
        <form method="post" action="estacionamento.php">
            <div class="card my-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label class="form-label">Data inicial</label>
                                <input type="date" id="dtInicial" value="<?php echo ($dtInicial); ?>" name="dtInicial" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="mb-2">Tipo de Veiculo</label>
                            <select class="form-select" aria-label="Default select example" name="tipo" id="tipo">
                                <option value="">Selecione o veiculo</option>
                                <?php $sql = "SELECT tipoID, nome,
                                            case when nome = '$tipo' then 'selected' else '' end as selecao
                                            from tipo order by nome";
                                $select = mysqli_query($conn, $sql);
                                while ($linha = mysqli_fetch_array($select)) {
                                    echo ("<option $linha[2] value='$linha[1]'>$linha[1]</option>");
                                }
                                ?>

                            </select>
                        </div>
                        <div class="col-sm-3 d-table">
                            <div class="d-table-cell align-middle text-center">
                                <div class="mb-3">
                                    <label class="form-label">Data final</label>
                                    <input type="date" id="dtFinal" value="<?php echo ($dtFinal); ?>" name="dtFinal" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary form-control" type="submit">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive container">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th scope="col" style="width: 40px;">#</th>
                    <th scope="col" class="text-center">Entrada</th>
                    <th scope="col" class="text-center">Saida</th>
                    <th scope="col">Placa</th>
                    <th scope="col" style="width: 200px;">#</>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = $pagina + 1;
                while ($linha = mysqli_fetch_assoc($result)) {
                    echo ('<tr>');
                    echo ('<td class="text-end">' . $i . '</td>');
                    echo ('<td class="text-center">' . $linha['dSaida'] . '</td>');
                    echo ('<td class="text-center">' . $linha['dEntrada'] . '</td>');
                    echo ('<td class="text-center">' . $linha['placa'] . '</td>');
                    echo ('<td class="text-center">' . $linha['valortotal'] . '</td>');
                    $i++;
                }
                ?>

            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?php echo ($dAnt) ?>">
                    <a class="page-link" href="?pag=<?php echo ($anterior); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                for ($x = 1; $x <= $totalPaginas; $x++) { ?>

                    <li class="page-item <?php echo (($x == $pagina) ? "active" : "");  ?>"><a class="page-link" href="?pag=<?php echo ($x); ?>"><?php echo ($x); ?></a></li>

                <?php } ?>
                <li class="page-item">
                    <a class="page-link" href="?pag=<?php echo ($proximo); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <a href="relatorio.php" class="btn btn-primary" target="_blank">Relatório</a>
    </div>














    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>
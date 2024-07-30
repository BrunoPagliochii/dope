<?php
if (file_exists('../lib/PHP_conecta.php')) {
    require_once '../lib/jwt_auth_functions.php';
    require_once '../lib/PHP_conecta.php';
    require_once '../functions/helpers.php';
} else if (file_exists('../../lib/PHP_conecta.php')) {
    require_once '../../lib/jwt_auth_functions.php';
    require_once '../../lib/PHP_conecta.php';
    require_once '../../functions/helpers.php';
} else if (file_exists('../../../lib/PHP_conecta.php')) {
    require_once '../../../lib/jwt_auth_functions.php';
    require_once '../../../lib/PHP_conecta.php';
    require_once '../../../functions/helpers.php';
}

// --------------------------------------------------------------------
$emailUsuario_token = validateJWTToken();
// --------------------------------------------------------------------
$query = "SELECT A.NOME, A.EMAIL, A.ID, B.RAZAO_SOCIAL, A.EMPRESA_ID FROM BD_USUARIOS A 
          INNER JOIN BD_EMPRESAS B ON B.ID = A.EMPRESA_ID 
          WHERE A.ATIVO = 'S' AND B.ATIVO = 'S' AND A.EMAIL = ?";

$stmt = $conexao->prepare($query);
$stmt->bind_param('s', $emailUsuario_token);
$stmt->execute();
$result = $stmt->get_result();

if ($dadossiderbar = $result->fetch_assoc()) {
    $IDUSUARIOMODEL = $dadossiderbar['ID'];
    $NOMEUSUARIOMODEL = $dadossiderbar['NOME'];
    $EMAILUSUARIOMODEL = $dadossiderbar['EMAIL'];
    $EMPRESAUSUARIOMODEL = $dadossiderbar['RAZAO_SOCIAL'];
    $IDEMPRESAUSUARIOMODEL = $dadossiderbar['EMPRESA_ID'];
}
// --------------------------------------------------------------------

if ($_POST["JQueryFunction"] == 'cadastrarUnidadeMedida') {
    $response = array();

    $descricao = $_POST['descricao'];
    $sigla = $_POST['sigla'];

    $query = "INSERT INTO EST_UNIDADES_MEDIDA (DESCRICAO, SIGLA, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
    VALUES ('$descricao', '$sigla', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";


    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro realizado com sucesso!.',
        );
    } else {

        $response = array(
            'status' =>  'danger',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'editarUnidadeMedida') {
    $response = array();

    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $sigla = $_POST['sigla'];


    $query = "UPDATE EST_UNIDADES_MEDIDA SET
    DESCRICAO = '$descricao',
    SIGLA = '$sigla',
    ALTERADOPOR = '$IDUSUARIOMODEL',
    ALTERADOEM = NOW()
    WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";

    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro foi alterado com Sucesso!.',
        );
    } else {

        $response = array(
            'status' =>  'danger',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

if ($_POST["JQueryFunction"] == 'deletarUnidadesMedida') {
    $response = array();

    $id = $_POST['id'];

    $query = "UPDATE EST_UNIDADES_MEDIDA SET
    ATIVO = 'N',
    ALTERADOPOR = '$IDUSUARIOMODEL',
    ALTERADOEM = NOW()
    WHERE ID = '$id' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";


    if (mysqli_query($conexao, $query)) {
        $response = array(
            'status' =>  'success',
            'msg' =>  'Cadastro foi removido com Sucesso!.',
        );
    } else {

        $response = array(
            'status' =>  'danger',
            'msg' =>  mysqli_error($conexao),
        );
    }

    echo json_encode($response);
}

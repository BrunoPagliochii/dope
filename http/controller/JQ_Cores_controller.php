<?php
if (file_exists('../lib/PHP_conecta.php')) {
  require_once '../lib/jwt_auth_functions.php';
  require_once '../lib/PHP_conecta.php';
  require_once '../functions/helpers.php';
} else {
  require_once '../../lib/jwt_auth_functions.php';
  require_once '../../lib/PHP_conecta.php';
  require_once '../../functions/helpers.php';
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

if ($_POST["JQueryFunction"] == 'novaCor') {
  $array = array();

  $hexadecimal = $_POST['hexadecimal'];
  $descricao = $_POST['descricao'];

  $query = "INSERT INTO EST_CORES (DESCRICAO, HEXADECIMAL, INCLUIDOPOR, INCLUIDOEM, EMPRESA_ID)
    VALUES ('$descricao', '$hexadecimal', '$IDUSUARIOMODEL', NOW(), '$IDEMPRESAUSUARIOMODEL')";


  if (mysqli_query($conexao, $query)) {
    $array = array(
      'status' =>  'success',
      'msg' =>  'Cadastro Realizado com Sucesso!.',
    );
  } else {

    $array = array(
      'status' =>  'error',
      'msg' =>  mysqli_error($conexao),
    );
  }

  echo json_encode($array);
}

if ($_POST["JQueryFunction"] == 'editarCor') {
  $array = array();

  $idCor = $_POST['idCor'];
  $hexadecimal = $_POST['hexadecimal'];
  $descricao = $_POST['descricao'];

  $query = "UPDATE EST_CORES SET
  DESCRICAO = '$descricao',
  HEXADECIMAL = '$hexadecimal', 
  ALTERADOPOR = '$IDUSUARIOMODEL',
  ALTERADOEM = NOW()
  WHERE ID = '$idCor' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";


  if (mysqli_query($conexao, $query)) {
    $array = array(
      'status' =>  'success',
      'msg' =>  'Cadastro foi Alterado com Sucesso!.',
    );
  } else {

    $array = array(
      'status' =>  'error',
      'msg' =>  mysqli_error($conexao),
    );
  }

  echo json_encode($array);
}

if ($_POST["JQueryFunction"] == 'deletarCor') {
  $array = array();

  $ID = $_POST['ID'];

  $query = "UPDATE EST_CORES SET
  ATIVO = 'N',
  ALTERADOPOR = '$IDUSUARIOMODEL',
  ALTERADOEM = NOW()
  WHERE ID = '$ID' AND EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL'";


  if (mysqli_query($conexao, $query)) {
    $array = array(
      'status' =>  'success',
      'msg' =>  'Cadastro foi Alterado com Sucesso!.',
    );
  } else {

    $array = array(
      'status' =>  'error',
      'msg' =>  mysqli_error($conexao),
    );
  }

  echo json_encode($array);
}

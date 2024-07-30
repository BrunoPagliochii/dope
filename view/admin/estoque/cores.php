<?php
$dados = array(
    'NomePagina' => 'Cadastro de Cores',
    'MenuModulo' => 'Estoque'
);
?>

<?php include('../../layouts/body.php') ?>


<div class="col-12">
    <div class="card">

        <div class="card-header">
            <h4><?= $dados['NomePagina'] ?></h4>
            <div class="card-header-action">
                <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroCores"></i>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelaDeCores" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="10">ID</th>
                            <th width="60">Descrição</th>
                            <th width="20">Cor</th>
                            <th width="10">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $resultado = $conexao->query("SELECT HEXADECIMAL, DESCRICAO, ID FROM EST_CORES WHERE EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL' AND ATIVO = 'S'");
                        if (!$resultado) {
                            die("Erro na consulta: " . $conexao->error);
                        }

                        while ($dadosCor = $resultado->fetch_assoc()) {
                        ?>

                            <tr>
                                <td><?= $dadosCor['ID'] ?></td>
                                <td><?= $dadosCor['DESCRICAO'] ?></td>
                                <td>
                                    <div class="card-icon" style="background-color: <?= $dadosCor['HEXADECIMAL'] ?>;">&nbsp;</div>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-action mr-1" data-bs-toggle="modal" data-bs-target="#modal-editarCores" data-id="<?= $dadosCor['ID'] ?>" data-id2="<?= $dadosCor['DESCRICAO'] ?>" data-id3="<?= $dadosCor['HEXADECIMAL'] ?>">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a class="btn btn-danger btn-action" onclick="deletarCor(<?= $dadosCor['ID'] ?>)"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../../layouts/footer.php') ?>

<!-- Modal Cadastro de Cores -->
<div class="modal fade" id="modal-cadastroCores" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="hexadecimal">Cor *</label>
                        <input type="color" id="hexadecimal" name="hexadecimal" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="descricao">Descrição *</label>
                        <input maxlength="45" type="text" id="descricao" name="descricao" class="form-control">
                    </div>
                </div>
                <button id="btnCadastrarCor" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Cores -->
<div class="modal fade" id="modal-editarCores" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="idCor" name="idCor">
                    <div class="col-12 mb-3">
                        <label for="hexadecimalEdt">Cor *</label>
                        <input type="color" id="hexadecimalEdt" name="hexadecimalEdt" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="45" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>
                </div>
                <button id="btnEditarCor" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        // Executa assim que o botão de salvar for clicado
        $('#btnCadastrarCor').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'hexadecimal',
                'descricao',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'novaCor');
            form_data.append('hexadecimal', $('#hexadecimal').val());
            form_data.append('descricao', $('#descricao').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/JQ_Cores_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();
                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });

        // Executa assim que o botão de salvar for clicado
        $('#btnEditarCor').click(function(e) {
            // Cancela o envio do formulário
            e.preventDefault();

            const fieldsToValidate = [
                'idCor',
                'hexadecimalEdt',
                'descricaoEdt',
                // Adicione outros campos que deseja validar e enviar aqui
            ];

            if (!validateForm(...fieldsToValidate)) {
                return false;
            }

            // Cria um FormData para enviar os dados
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'editarCor');
            form_data.append('idCor', $('#idCor').val());
            form_data.append('hexadecimal', $('#hexadecimalEdt').val());
            form_data.append('descricao', $('#descricaoEdt').val());

            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/JQ_Cores_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();
                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        });
    });

    $('#modal-editarCores').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idCor').val(button.data('id'));
        $('#descricaoEdt').val(button.data('id2'));
        $('#hexadecimalEdt').val(button.data('id3'));
    });

    async function deletarCor(ID) {

        const confirmed = await sweetConfirmacao('Tem certeza que excluir essa cor?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarCor');
            form_data.append('ID', ID);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/JQ_Cores_controller.php',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();
                    } else {
                        exibirToastr(response.msg, 'danger');
                    }
                }
            });
        }
    }

    $(function() {
        initDataTable('tabelaDeCores');
    });
</script>
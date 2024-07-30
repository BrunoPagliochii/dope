<?php
$dados = array(
    'NomePagina' => 'Cadastro de Unidades de Medida',
    'MenuModulo' => 'Estoque'
);
?>


<?php include('../../layouts/body.php') ?>

<section class="section">

    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h4><?= $dados['NomePagina'] ?></h4>
                <div class="card-header-action">
                    <a href="javascript:void(0)">
                        <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroUnidadesMedida"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelaDeUnidadesDeMedida" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Sigla</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $resultado = $conexao->query("SELECT DESCRICAO, ID, SIGLA FROM EST_UNIDADES_MEDIDA WHERE EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL' AND ATIVO = 'S'");
                            if (!$resultado) {
                                die("Erro na consulta: " . $conexao->error);
                            }

                            while ($dadosUnidadesMedida = $resultado->fetch_assoc()) { ?>

                                <tr>
                                    <td><?= $dadosUnidadesMedida['ID'] ?></td>
                                    <td><?= $dadosUnidadesMedida['DESCRICAO'] ?></td>
                                    <td><?= $dadosUnidadesMedida['SIGLA'] ?></td>
                                    <td>
                                        <a title="editar" class="btn btn-primary btn-action mr-1" data-id="<?= $dadosUnidadesMedida['ID'] ?>" data-sigla="<?= $dadosUnidadesMedida['SIGLA'] ?>" data-descricao="<?= $dadosUnidadesMedida['DESCRICAO'] ?>" data-bs-toggle="modal" data-bs-target="#modal-edicaoUnidadesMedida">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a title="remover" onclick="deletarUnidadesMedida(<?= $dadosUnidadesMedida['ID'] ?>)" class="btn btn-danger btn-action">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</section>

<?php include('../../layouts/footer.php') ?>

<!-- Modal Cadastro de Unidades de Medida -->
<div class="modal fade" id="modal-cadastroUnidadesMedida" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <div class="col-12">
                        <label for="descricao">Descrição *</label>
                        <input maxlength="45" type="text" id="descricao" name="descricao" class="form-control">
                    </div>
                    <div class="col-12 mt-3">
                        <label for="sigla">Sigla *</label>
                        <input maxlength="3" type="text" id="sigla" name="sigla" class="form-control">
                    </div>
                </div>
                <button onclick="cadastrarUnidadeMedida()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edição de Unidades de Medida -->
<div class="modal fade" id="modal-edicaoUnidadesMedida" tabindex="-1" role="dialog" aria-hidden="true">
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

                    <input type="hidden" id="idUnidadeMedida" name="idUnidadeMedida">

                    <div class="col-12">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="45" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>
                    <div class="col-12 mt-3">
                        <label for="siglaEdt">Sigla *</label>
                        <input maxlength="3" type="text" id="siglaEdt" name="siglaEdt" class="form-control">
                    </div>
                </div>
                <button onclick="editarUnidadeMedida()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        initDataTable('tabelaDeUnidadesDeMedida');
    });

    function cadastrarUnidadeMedida() {

        const fieldsToValidate = [
            'descricao', 'sigla'
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();

        form_data.append('JQueryFunction', 'cadastrarUnidadeMedida');
        form_data.append('descricao', $('#descricao').val());
        form_data.append('sigla', $('#sigla').val());
        

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/estoque/JQ_UnidadesMedida_controller.php',
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
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    function editarUnidadeMedida() {

        const fieldsToValidate = [
            'descricaoEdt', 'siglaEdt'
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'editarUnidadeMedida');
        form_data.append('descricao', $('#descricaoEdt').val());
        form_data.append('sigla', $('#siglaEdt').val());
        form_data.append('id', $('#idUnidadeMedida').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/estoque/JQ_UnidadesMedida_controller.php',
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
                    exibirToastr(response.msg, response.status);
                }
            }
        });
    }

    async function deletarUnidadesMedida(id) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja excluir essa Unidade de Medida?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarUnidadesMedida');
            form_data.append('id', id);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/estoque/JQ_UnidadesMedida_controller.php',
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
                        exibirToastr(response.msg, response.status);
                    }
                }
            });
        }
    }

    $('#modal-edicaoUnidadesMedida').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idUnidadeMedida').val(button.data('id'));
        $('#descricaoEdt').val(button.data('descricao'));
        $('#siglaEdt').val(button.data('sigla'));
    });
</script>



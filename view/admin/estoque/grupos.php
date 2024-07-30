<?php
$dados = array(
    'NomePagina' => 'Cadastro de Grupos',
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
                        <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#modal-cadastroGrupos"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelaDeGrupos" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $resultado = $conexao->query("SELECT DESCRICAO, ID FROM EST_GRUPOS WHERE EMPRESA_ID = '$IDEMPRESAUSUARIOMODEL' AND ATIVO = 'S'");
                            if (!$resultado) {
                                die("Erro na consulta: " . $conexao->error);
                            }

                            while ($dadosGrupo = $resultado->fetch_assoc()) { ?>

                                <tr>
                                    <td><?= $dadosGrupo['ID'] ?></td>
                                    <td><?= $dadosGrupo['DESCRICAO'] ?></td>
                                    <td>
                                        <a title="editar" class="btn btn-primary btn-action mr-1" data-id="<?= $dadosGrupo['ID'] ?>" data-descricao="<?= $dadosGrupo['DESCRICAO'] ?>" data-bs-toggle="modal" data-bs-target="#modal-edicaoGrupos">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a title="remover" onclick="deletarGrupo(<?= $dadosGrupo['ID'] ?>)" class="btn btn-danger btn-action">
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

<!-- Modal Cadastro de Grupos -->
<div class="modal fade" id="modal-cadastroGrupos" tabindex="-1" role="dialog" aria-hidden="true">
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
                </div>
                <button onclick="cadastrarGrupo()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edição de Grupos -->
<div class="modal fade" id="modal-edicaoGrupos" tabindex="-1" role="dialog" aria-hidden="true">
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

                    <input type="hidden" id="idGrupo" name="idGrupo">

                    <div class="col-12">
                        <label for="descricaoEdt">Descrição *</label>
                        <input maxlength="45" type="text" id="descricaoEdt" name="descricaoEdt" class="form-control">
                    </div>
                </div>
                <button onclick="editarGrupo()" type="button" class="btn btn-success mt-4 waves-effect">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        initDataTable('tabelaDeGrupos');
    });

    function cadastrarGrupo() {

        const fieldsToValidate = [
            'descricao',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'cadastrarGrupo');
        form_data.append('descricao', $('#descricao').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/estoque/JQ_Grupos_controller.php',
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

    function editarGrupo() {

        const fieldsToValidate = [
            'descricaoEdt',
            // Adicione outros campos que deseja validar e enviar aqui
        ];

        if (!validateForm(...fieldsToValidate)) {
            return false;
        }

        // Cria um FormData para enviar os dados
        const form_data = new FormData();
        form_data.append('JQueryFunction', 'editarGrupo');
        form_data.append('descricao', $('#descricaoEdt').val());
        form_data.append('id', $('#idGrupo').val());

        $.ajax({
            url: '<?= URL_BASE_HOST ?>/http/controller/estoque/JQ_Grupos_controller.php',
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

    async function deletarGrupo(id) {

        const confirmed = await sweetConfirmacao('Tem certeza que deseja excluir esse grupo?');

        if (confirmed) {
            const form_data = new FormData();
            form_data.append('JQueryFunction', 'deletarGrupo');
            form_data.append('id', id);

            // Método post do jQuery
            $.ajax({
                url: '<?= URL_BASE_HOST ?>/http/controller/estoque/JQ_Grupos_controller.php',
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

    $('#modal-edicaoGrupos').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        $('#idGrupo').val(button.data('id'));
        $('#descricaoEdt').val(button.data('descricao'));
    });
</script>
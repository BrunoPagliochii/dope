<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.php"> <img alt="image" src="<?= URL_BASE_HOST ?>/public/template/assets/img/logo.png" class="header-logo" /> <span class="logo-name">Aegis</span>
            </a>
        </div>
        <div class="sidebar-user">
            <div class="sidebar-user-picture">
                <img alt="image" src="<?= URL_BASE_HOST ?>/public/template/assets/img/userbig.png">
            </div>
            <div class="sidebar-user-details">
                <div class="user-name">
                    <?= $NOMEUSUARIOMODEL ?>
                </div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Principal</li>
            <li class="dropdown <?= (($dados['MenuModulo'] == 'Estoque') ? 'active' : '') ?>">

                <a href="#" class="nav-link has-dropdown">
                    <i data-feather="box"></i>  
                    <span>Estoque</span>
                </a>

                <ul class="dropdown-menu">
                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Cores') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/cores.php">
                            Cores
                        </a>
                    </li>

                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Grupos') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/grupos.php">
                            Grupos
                        </a>
                    </li>

                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Unidades de Medida') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/unidadesMedida.php">
                           Unidades de Medida
                        </a>
                    </li>
                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Tipos de Mercadoria') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/tiposMercadoria.php">
                            Tipos de Mercadoria
                        </a>
                    </li>

                </ul>
            </li>
            <li class="dropdown"><a href="#" class="nav-link has-dropdown"><i data-feather="briefcase"></i><span>Widgets</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="widget-chart.html">
                            Chart Widgets
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="widget-data.html">
                            Data Widgets
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
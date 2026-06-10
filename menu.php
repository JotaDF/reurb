<!-- Sidebar -->
<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion " id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <i class="fa fa-desktop"></i>
        </div>
        <div class="sidebar-brand-text mx-1">REURB</div>
    </a>

    <?php
    if ($usuario_logado->perfil <= 2) {
        ?>
        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Gestão de Acesso
        </div>
        <?php
        if ($usuario_logado->perfil <= 1) {
            ?>        
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="perfis.php">
                    <i class="fa fa-id-card"></i>
                    <span>Perfis</span>
                </a>
            </li>
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="equipes.php">
                    <i class="fa fa-users"></i>
                    <span>Equipes</span>
                </a>
            </li>
            <?php
        }
        ?>        
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="usuarios.php">
                <i class="fa fa-user"></i>
                <span>Usuários</span>
            </a>
        </li>
        <?php
    }
    ?>
    <!-- Divider -->
    <hr class="sidebar-divider">  
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->

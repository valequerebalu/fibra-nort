<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto align-items-center">
        
        <!-- User Info Area -->
        <li class="nav-item d-flex align-items-center">
            <div class="text-right mr-3 d-none d-md-block" style="line-height: 1.2;">
                <div class="font-weight-bold text-dark">
                    <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Usuario'; ?>
                </div>
                <small class="text-muted">
                    <?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'Invitado'; ?>
                </small>
            </div>
            <!-- Avatar Circle -->
            <div class="d-flex align-items-center justify-content-center rounded-circle mr-4" 
                 style="width: 40px; height: 40px; background-color: #e0e7ff; color: #4f46e5;">
                <i class="fas fa-user"></i>
            </div>
        </li>

        <!-- Logout Button -->
        <li class="nav-item">
            <a href="/fibra-nort/auth/auth_controller.php?op=logout" class="nav-link text-dark pl-3 border-left" title="Cerrar Sesión">
                <i class="fas fa-sign-out-alt mr-1"></i> Salir
            </a>
        </li>
    </ul>
</nav>

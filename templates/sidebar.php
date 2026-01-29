<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/AccessControl.php'; ?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="#" class="brand-link">
    <span class="brand-text font-weight-light">FibraNort</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

        <?php if (AccessControl::canAccess('dashboard')): ?>
        <li class="nav-item">
          <a href="/fibra-nort/public" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <?php endif; ?>

        <?php if (AccessControl::canAccess('cliente')): ?>
        <li class="nav-item">
          <a href="/fibra-nort/public/?view=cliente" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Clientes</p>
          </a>
        </li>
        <?php endif; ?>

        <?php if (AccessControl::canAccess('orden')): ?>
        <li class="nav-item">
          <a href="/fibra-nort/public/?view=orden" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>Ordenes</p>
          </a>
        </li>
        <?php endif; ?>

        <?php if (AccessControl::canAccess('asignacion')): ?>
          <li class="nav-item">
          <a href="/fibra-nort/public/?view=asignacion" class="nav-link">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Asignación de Técnicos</p>
          </a>
        </li>
        <?php endif; ?>

        <?php if (AccessControl::canAccess('planes')): ?>
          <li class="nav-item">
          <a href="/fibra-nort/public/?view=planes" class="nav-link">
            <i class="nav-icon fas fa-layer-group"></i>
            <p>Gestión de Planes</p>
          </a>
        </li>
        <?php endif; ?>

      </ul>
    </nav>
  </div>
</aside>

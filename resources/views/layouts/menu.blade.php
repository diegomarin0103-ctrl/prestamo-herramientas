<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('herramientas.*') ? 'active fw-semibold' : '' }}" href="{{ route('herramientas.index') }}">
        <i class="bi bi-wrench-adjustable"></i> Herramientas
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('prestamos.*') ? 'active fw-semibold' : '' }}" href="{{ route('prestamos.index') }}">
        <i class="bi bi-arrow-left-right"></i> Préstamos
    </a>
</li>

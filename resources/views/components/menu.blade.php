<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item {{ Nav::isRoute('home') }}">
  <a class="nav-link" href="{{ route('home') }}">
    <i class="fas fa-chart-pie"></i>
    <span>{{ __('Dashboard') }}</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Nav Item - Operação -->
<li class="nav-item {{ Nav::isRoute('operations') }}">
  <a class="nav-link" href="{{ route('operations.index') }}">
    <i class="fas fa-cash-register"></i>
    <span>{{ __('Operações') }}</span></a>
</li>

<!-- Nav Item - Rendimento -->
<li class="nav-item dropdown{{ Nav::isRoute('incomes') }}">
  <a class="nav-link" href="{{ route('incomes.index') }}">
    <i class="fas fa-money-bill-wave"></i>
    <span>{{ __('Rendimentos') }}</span></a>
</li>
<!-- Divider -->
<hr class="sidebar-divider">

<!-- Nav Item - Sectors -->
<li class="nav-item {{ Nav::isRoute('sectors') }}">
  <a class="nav-link" href="{{ route('sectors.index') }}">
    <i class="fas fa-bezier-curve"></i>
    <span>{{ __('Setores') }}</span></a>
</li>
<!-- Nav Item - Ativo Types -->
<li class="nav-item {{ Nav::isRoute('stockTypes') }}">
  <a class="nav-link" href="{{ route('stockTypes.index') }}">
    <i class="fas fa-layer-group"></i>
    <span>{{ __('Tipo de Ativos') }}</span></a>
</li>
<!-- Nav Item - Ativo -->
<li class="nav-item {{ Nav::isRoute('stocks') }}">
  <a class="nav-link" href="{{ route('stocks.index') }}">
    <i class="fas fa-building"></i>
    <span>{{ __('Ativos') }}</span></a>
</li>

<!-- Nav Item - Rendimento Types -->
<li class="nav-item {{ Nav::isRoute('incomeTypes') }}">
  <a class="nav-link" href="{{ route('incomeTypes.index') }}">
    <i class="fas fa-layer-group"></i>
    <span>{{ __('Tipo de Rendimentos') }}</span></a>
</li>

<!-- Nav Item - Brokers -->
<li class="nav-item {{ Nav::isRoute('brokers') }}">
  <a class="nav-link" href="{{ route('brokers.index') }}">
    <i class="fas fa-handshake-slash"></i>
    <span>{{ __('Corretoras') }}</span></a>
</li>
<!-- Divider -->
<hr class="sidebar-divider">
<!-- Nav Item - Profile -->
<li class="nav-item {{ Nav::isRoute('profile') }}">
  <a class="nav-link" href="{{ route('profile') }}">
    <i class="fas fa-fw fa-user"></i>
    <span>{{ __('Perfil') }}</span>
  </a>
</li>

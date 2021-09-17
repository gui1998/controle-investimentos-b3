<!-- Nav Item - Dashboard -->
<div>
    <!-- Nav Item - Operação -->
    <li class="nav-item {{ Nav::isRoute('operations') }}">
        <a class="nav-link" href="{{ route('operations.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Operações') }}</span></a>
    </li>

    <!-- Nav Item - Rendimento -->
    <li class="nav-item {{ Nav::isRoute('incomes') }}">
        <a class="nav-link" href="{{ route('incomes.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Rendimentos') }}</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Sectors -->
    <li class="nav-item {{ Nav::isRoute('sectors') }}">
        <a class="nav-link" href="{{ route('sectors.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Setores') }}</span></a>
    </li>
    <!-- Nav Item - Ação Types -->
    <li class="nav-item {{ Nav::isRoute('stockTypes') }}">
        <a class="nav-link" href="{{ route('stockTypes.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Tipo de Ações') }}</span></a>
    </li>
    <!-- Nav Item - Ação -->
    <li class="nav-item {{ Nav::isRoute('stocks') }}">
        <a class="nav-link" href="{{ route('stocks.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Ações') }}</span></a>
    </li>

    <!-- Nav Item - Rendimento Types -->
    <li class="nav-item {{ Nav::isRoute('incomeTypes') }}">
        <a class="nav-link" href="{{ route('incomeTypes.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Tipo de Rendimentos') }}</span></a>
    </li>

    <!-- Nav Item - Brokers -->
    <li class="nav-item {{ Nav::isRoute('brokers') }}">
        <a class="nav-link" href="{{ route('brokers.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
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
</div>

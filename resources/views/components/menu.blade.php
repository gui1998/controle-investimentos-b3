<!-- Nav Item - Dashboard -->
<li class="nav-item {{ Nav::isRoute('home') }}">
    <a class="nav-link" href="{{ route('home') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>{{ __('Dashboard') }}</span></a>
</li>

<!-- Nav Item - Stock Types -->
<li class="nav-item {{ Nav::isRoute('stockTypes') }}">
    <a class="nav-link" href="{{ route('stockTypes.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>{{ __('Tipo de Ações') }}</span></a>
</li>

<!-- Nav Item - Sectors -->
<li class="nav-item {{ Nav::isRoute('sectors') }}">
    <a class="nav-link" href="{{ route('sectors.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>{{ __('Setores') }}</span></a>
</li>

<!-- Nav Item - Stock -->
<li class="nav-item {{ Nav::isRoute('stocks') }}">
    <a class="nav-link" href="{{ route('stocks.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>{{ __('Ações') }}</span></a>
</li>

<!-- Nav Item - Income Types -->
<li class="nav-item {{ Nav::isRoute('incomeTypes') }}">
    <a class="nav-link" href="{{ route('incomeTypes.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>{{ __('Tipo de Rendimentos') }}</span></a>
</li>

<!-- Nav Item - Operation Types -->
<li class="nav-item {{ Nav::isRoute('operationTypes') }}">
    <a class="nav-link" href="{{ route('operationTypes.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>{{ __('Tipo de Operações') }}</span></a>
</li>

<!-- Nav Item - Brokers -->
<li class="nav-item {{ Nav::isRoute('brokers') }}">
    <a class="nav-link" href="{{ route('brokers.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>{{ __('Corretoras') }}</span></a>
</li>

<!-- Nav Item - Income -->
<li class="nav-item {{ Nav::isRoute('incomes') }}">
    <a class="nav-link" href="{{ route('incomes.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>{{ __('Rendimentos') }}</span></a>
</li>

<!-- Nav Item - Profile -->
<li class="nav-item {{ Nav::isRoute('profile') }}">
    <a class="nav-link" href="{{ route('profile') }}">
        <i class="fas fa-fw fa-user"></i>
        <span>{{ __('Perfil') }}</span>
    </a>
</li>

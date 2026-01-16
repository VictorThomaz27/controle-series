<x-layoutSys title="Dashboard">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title">Clientes Ativos</h6>
                    <p class="display-6 mb-0">{{ $activeClients }}</p>
                    <span class="badge bg-primary mt-2">com contrato ativo</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title">Rotas</h6>
                    <p class="display-6 mb-0">{{ $routesCount }}</p>
                    <span class="badge bg-info mt-2">cadastradas</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title">Motoristas</h6>
                    <p class="display-6 mb-0">{{ $driversCount }}</p>
                    <span class="badge bg-secondary mt-2">cadastrados</span>
                </div>
            </div>
        </div>
    </div>
</x-layoutSys>

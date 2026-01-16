<x-layoutSys title="Home">
    <div class="row g-3">
        <div class="col-12">
            <div class="alert alert-primary" role="alert">
                Bem-vindo ao sistema! Utilize o menu para navegar.
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Atalhos</h5>
                    <p class="card-text text-muted">Acesse rapidamente as áreas do sistema.</p>
                    <div class="d-grid gap-2">
                        <a href="/rotas" class="btn btn-outline-primary">Rotas</a>
                        <a href="/clientes" class="btn btn-outline-primary">Clientes</a>
                        <a href="/motoristas" class="btn btn-outline-primary">Motoristas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Configurações</h5>
                    <p class="card-text text-muted">Ajuste preferências e opções da aplicação.</p>
                    <a href="/configuracoes" class="btn btn-outline-secondary">Abrir configurações</a>
                </div>
            </div>
        </div>
    </div>
</x-layoutSys>

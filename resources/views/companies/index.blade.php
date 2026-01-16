<x-layoutSys title="Empresas">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Empresas</h5>
        <a href="/empresas/criar" class="btn btn-primary">Nova Empresa</a>
    </div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th>UF</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $c)
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->phone }}</td>
                            <td>{{ $c->city }}</td>
                            <td>{{ $c->state }}</td>
                            <td class="text-end">
                                <a href="/empresas/{{ $c->id }}/editar" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="/empresas/{{ $c->id }}" method="post" class="d-inline" onsubmit="return confirm('Confirma remover esta empresa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted">Nenhuma empresa cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layoutSys>

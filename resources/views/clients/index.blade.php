<x-layoutSys title="Clientes">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Clientes</h5>
        <a href="/clientes/criar" class="btn btn-primary">Novo Cliente</a>
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
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $c)
                        <tr>
                            <td>{{ $c->full_name }}</td>
                            <td>{{ $c->cpf }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->phone }}</td>
                            <td class="text-end">
                                <a href="/clientes/{{ $c->id }}/editar" class="btn btn-sm btn-outline-primary">Editar</a>
                                @if($c->lat && $c->lon)
                                    <a href="/routing?coords={{ $c->lat }},{{ $c->lon }}" target="_blank" class="btn btn-sm btn-outline-success">Usar no cálculo</a>
                                @endif
                                <form action="/clientes/{{ $c->id }}" method="post" class="d-inline" onsubmit="return confirm('Confirma remover este cliente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">Nenhum cliente cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layoutSys>

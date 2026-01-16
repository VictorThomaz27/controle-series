<x-layoutSys title="Rotas">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Rotas</h5>
        <a href="/rotas/criar" class="btn btn-primary">Nova Rota</a>
    </div>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Horário</th>
                        <th>Dias</th>
                        <th>KM</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $r)
                        <tr>
                            <td>{{ $r->origin_address }}</td>
                            <td>{{ $r->destination_address }}</td>
                            <td>{{ $r->start_time }} - {{ $r->end_time }}</td>
                            <td>{{ $r->days }}</td>
                            <td>{{ $r->distance_km }}</td>
                            <td class="text-end">
                                <a href="/rotas/{{ $r->id }}/editar" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="/rotas/{{ $r->id }}" method="post" class="d-inline" onsubmit="return confirm('Confirma remover esta rota?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted">Nenhuma rota cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layoutSys>

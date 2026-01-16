<x-layoutSys title="Motoristas">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Motoristas</h5>
        <a href="/motoristas/criar" class="btn btn-primary">Novo Motorista</a>
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
                        <th>CPF/CNPJ</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Veículo</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $d)
                        <tr>
                            <td>{{ $d->full_name }}</td>
                            <td>{{ $d->cpf_cnpj }}</td>
                            <td>{{ $d->email }}</td>
                            <td>{{ $d->phone }}</td>
                            <td>{{ $d->vehicle_model }} {{ $d->vehicle_plate ? '(' . $d->vehicle_plate . ')' : '' }}</td>
                            <td class="text-end">
                                <a href="/motoristas/{{ $d->id }}/editar" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="/motoristas/{{ $d->id }}" method="post" class="d-inline" onsubmit="return confirm('Confirma remover este motorista?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted">Nenhum motorista cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layoutSys>

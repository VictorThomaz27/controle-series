<x-layoutSys title="Novo Contrato">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Cadastro de Contrato</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form method="post" action="/contratos/salvar">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tipo de contrato</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_client" value="client" {{ old('type') === 'driver' ? '' : 'checked' }}>
                                        <label class="form-check-label" for="type_client">Cliente</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_driver" value="driver" {{ old('type') === 'driver' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_driver">Motorista</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="client_id" name="client_id">
                                        <option value="">Selecione...</option>
                                        @foreach ($clients as $c)
                                            <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="client_id">Cliente</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="driver_id" name="driver_id">
                                        <option value="">Selecione...</option>
                                        @foreach ($drivers as $d)
                                            <option value="{{ $d->id }}" {{ old('driver_id') == $d->id ? 'selected' : '' }}>{{ $d->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="driver_id">Motorista</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <select class="form-select" id="route_id" name="route_id">
                                        <option value="">Selecione...</option>
                                        @foreach ($routes as $r)
                                            <option value="{{ $r->id }}" {{ old('route_id') == $r->id ? 'selected' : '' }}>
                                                {{ $r->origin_address }} → {{ $r->destination_address }} ({{ $r->start_time }} - {{ $r->end_time }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="route_id">Rota</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" step="0.01" class="form-control" id="monthly_value" name="monthly_value" placeholder="Valor mensal" value="{{ old('monthly_value', 840) }}" required>
                                    <label for="monthly_value">Valor mensal (R$)</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="tolerance_minutes" name="tolerance_minutes" placeholder="Tolerância" value="{{ old('tolerance_minutes', 10) }}" required>
                                    <label for="tolerance_minutes">Tolerância (min)</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Início" value="{{ old('start_date') }}">
                                    <label for="start_date">Início</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card p-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="vacation_policy" name="vacation_policy" {{ old('vacation_policy', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="vacation_policy">Cláusula de férias (pagamento integral para garantir vaga)</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="anticorruption_acceptance" name="anticorruption_acceptance" {{ old('anticorruption_acceptance', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="anticorruption_acceptance">Aceite das leis anticorrupção</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="payer_name" name="payer_name" placeholder="Pagador" value="{{ old('payer_name', 'Brasceras S.A') }}">
                                    <label for="payer_name">Pagador</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="payer_cnpj" name="payer_cnpj" placeholder="CNPJ" value="{{ old('payer_cnpj', '04.535.453/0001-73') }}">
                                    <label for="payer_cnpj">CNPJ</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="payer_address" name="payer_address" placeholder="Endereço" value="{{ old('payer_address', 'Av. Copacabana, 238 Empresarial, Barueri SP') }}">
                                    <label for="payer_address">Endereço do pagador</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="content" name="content" placeholder="Conteúdo adicional" style="height: 160px;">{{ old('content') }}</textarea>
                                    <label for="content">Observações / cláusulas adicionais</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="/home" class="btn btn-outline-secondary">Cancelar</a>
                            <button class="btn btn-primary" type="submit">Salvar Contrato</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layoutSys>

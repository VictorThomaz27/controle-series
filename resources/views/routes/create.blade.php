<x-layoutSys title="Nova Rota">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Cadastro de Rota</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form method="post" action="/rotas/salvar">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="origin_address" name="origin_address" placeholder="Origem" value="{{ old('origin_address') }}" required>
                                    <label for="origin_address">Origem</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="destination_address" name="destination_address" placeholder="Destino" value="{{ old('destination_address') }}" required>
                                    <label for="destination_address">Destino</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="time" class="form-control" id="start_time" name="start_time" placeholder="Hora início" value="{{ old('start_time') }}">
                                    <label for="start_time">Hora início</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="time" class="form-control" id="end_time" name="end_time" placeholder="Hora fim" value="{{ old('end_time') }}">
                                    <label for="end_time">Hora fim</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="days" name="days" placeholder="Dias" value="{{ old('days', 'Segunda a Sexta') }}">
                                    <label for="days">Dias</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.1" class="form-control" id="distance_km" name="distance_km" placeholder="Distância (KM)" value="{{ old('distance_km') }}">
                                    <label for="distance_km">Distância (KM)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="driver_id" name="driver_id">
                                        <option value="">Selecione um motorista (opcional)</option>
                                        @foreach($drivers as $d)
                                            <option value="{{ $d->id }}" @selected(old('driver_id')==$d->id)>{{ $d->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="driver_id">Motorista</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Clientes (múltiplos)</label>
                                <select class="form-select" name="client_ids[]" multiple size="8">
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}">{{ $c->full_name }} @if($c->city) - {{ $c->city }}/{{ $c->state }} @endif</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Segure Ctrl/Command para múltipla seleção.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Empresas (múltiplas)</label>
                                <select class="form-select" name="company_ids[]" multiple size="8">
                                    @foreach($companies as $e)
                                        <option value="{{ $e->id }}">{{ $e->name }} @if($e->city) - {{ $e->city }}/{{ $e->state }} @endif</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Segure Ctrl/Command para múltipla seleção.</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <a href="/rotas" class="btn btn-outline-secondary">Voltar</a>
                            <button class="btn btn-primary" type="submit">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layoutSys>

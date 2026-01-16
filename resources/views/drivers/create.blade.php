<x-layoutSys title="Novo Motorista">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Cadastro de Motorista</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form method="post" action="/motoristas/salvar">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nome completo" value="{{ old('full_name') }}" required>
                                    <label for="full_name">Nome completo</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="cpf_cnpj" name="cpf_cnpj" placeholder="CPF/CNPJ" value="{{ old('cpf_cnpj') }}" required>
                                    <label for="cpf_cnpj">CPF/CNPJ</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefone" value="{{ old('phone') }}">
                                    <label for="phone">Telefone</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" placeholder="Modelo do veículo" value="{{ old('vehicle_model') }}">
                                    <label for="vehicle_model">Modelo do veículo</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate" placeholder="Placa" value="{{ old('vehicle_plate') }}">
                                    <label for="vehicle_plate">Placa</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="vehicle_capacity" name="vehicle_capacity" placeholder="Capacidade" value="{{ old('vehicle_capacity') }}">
                                    <label for="vehicle_capacity">Capacidade</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <a href="/motoristas" class="btn btn-outline-secondary">Voltar</a>
                            <button class="btn btn-primary" type="submit">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layoutSys>

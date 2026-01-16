<x-layoutSys title="Perfil">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Perfil do Usuário</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Informações básicas do seu perfil.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="profileName" placeholder="Nome" value="{{ $user->name }}" disabled>
                                <label for="profileName">Nome</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="profileEmail" placeholder="Email" value="{{ $user->email }}" disabled>
                                <label for="profileEmail">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="profileBirth" placeholder="Data de nascimento" value="{{ $user->birth_date ? $user->birth_date->format('Y-m-d') : '' }}" disabled>
                                <label for="profileBirth">Data de nascimento</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="profileFirstName" placeholder="Nome" value="{{ $user->first_name }}" disabled>
                                <label for="profileFirstName">Primeiro nome</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="profileLastName" placeholder="Sobrenome" value="{{ $user->last_name }}" disabled>
                                <label for="profileLastName">Sobrenome</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="/home" class="btn btn-outline-secondary">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layoutSys>

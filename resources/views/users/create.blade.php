<x-layout title="Cadastro de Usuário">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white text-center">
                        <h5 class="mb-0">Cadastro de novo usuário</h5>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <strong>Verifique os campos:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="/usuario/salvar">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="first_name"
                                            name="first_name"
                                            placeholder="Nome"
                                            value="{{ old('first_name') }}"
                                            required
                                            autofocus
                                        >
                                        <label for="first_name">Nome</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="last_name"
                                            name="last_name"
                                            placeholder="Sobrenome"
                                            value="{{ old('last_name') }}"
                                            required
                                        >
                                        <label for="last_name">Sobrenome</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input
                                            type="email"
                                            class="form-control"
                                            id="email"
                                            name="email"
                                            placeholder="email@exemplo.com"
                                            value="{{ old('email') }}"
                                            required
                                        >
                                        <label for="email">Email</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            name="password"
                                            placeholder="Senha"
                                            required
                                            autocomplete="new-password"
                                        >
                                        <label for="password">Senha</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            placeholder="Confirme a senha"
                                            required
                                            autocomplete="new-password"
                                        >
                                        <label for="password_confirmation">Confirma senha</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input
                                            type="date"
                                            class="form-control"
                                            id="birth_date"
                                            name="birth_date"
                                            placeholder="Data de nascimento"
                                            value="{{ old('birth_date') }}"
                                            required
                                        >
                                        <label for="birth_date">Data de nascimento</label>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary w-100 mt-4" type="submit">Cadastrar</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="/login" class="btn btn-link">Já tenho conta</a>
                        </div>
                    </div>
                </div>

                <p class="text-center text-muted mt-3 mb-0 small">© {{ date('Y') }} Controle de Séries</p>
            </div>
        </div>
    </div>
</x-layout>

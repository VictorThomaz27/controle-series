<x-layout title="Login">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-dark text-white">
                        <h5 class="mb-0">Acessar Conta</h5>
                    </div>

                    <div class="card-body p-4">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="post" action="/login">
                            @csrf

                            <div class="form-floating mb-3">
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="email@exemplo.com"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                >
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    placeholder="Senha"
                                    required
                                >
                                <label for="password">Senha</label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Manter conectado</label>
                                </div>
                                <a href="#" class="small text-decoration-none">Esqueceu a senha?</a>
                            </div>

                            <button class="btn btn-primary w-100" type="submit">Entrar</button>
                        </form>

                        <div class="text-center mt-3">
                            <span class="text-muted">Ainda não tem conta?</span>
                            <a href="/usuario/criar" class="btn btn-link">Cadastrar</a>
                        </div>
                    </div>
                </div>

                <p class="text-center text-muted mt-3 mb-0 small">© {{ date('Y') }} Controle de Séries</p>
            </div>
        </div>
    </div>
</x-layout>

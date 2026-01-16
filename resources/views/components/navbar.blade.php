<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container-fluid">
		<a class="navbar-brand" href="/home">Sistema</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Alternar navegação">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="mainNavbar">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="/dashboard">Dashboard</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/home">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/routing">Cálculo de Rotas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/rotas">Rotas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/empresas">Empresas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/clientes">Clientes</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/motoristas">Motoristas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/configuracoes">Configurações</a>
				</li>
			</ul>

			<div class="d-flex">
				<div class="dropdown">
					<button class="btn btn-outline-light d-flex align-items-center gap-2 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
						<span class="d-inline-block" aria-hidden="true">
							<!-- Ícone de usuário (SVG inline) -->
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
								<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
								<path d="M14 14s-1-1.5-6-1.5S2 14 2 14s1-4 6-4 6 4 6 4Z"/>
							</svg>
						</span>
						<span>Perfil</span>
					</button>
					<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
						<li><a class="dropdown-item" href="/perfil">Ver perfil</a></li>
						<li><hr class="dropdown-divider"></li>
						<li><a class="dropdown-item" href="/logout">Sair</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</nav>

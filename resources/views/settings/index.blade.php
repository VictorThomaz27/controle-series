<x-layoutSys title="Configurações">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Preferências do Sistema</div>
                <div class="card-body">
                    <p class="text-muted">Personalize a aparência e leitura do sistema. As preferências são salvas neste dispositivo.</p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Tema</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="theme" id="theme_light" value="light" checked>
                                        <label class="form-check-label" for="theme_light">Claro</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="theme" id="theme_dark" value="dark">
                                        <label class="form-check-label" for="theme_dark">Escuro</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Tamanho da Fonte</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="font" id="font_sm" value="sm">
                                        <label class="form-check-label" for="font_sm">Pequena</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="font" id="font_md" value="md" checked>
                                        <label class="form-check-label" for="font_md">Média</label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="font" id="font_lg" value="lg">
                                        <label class="form-check-label" for="font_lg">Grande</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button id="savePrefs" class="btn btn-primary" type="button">Salvar preferências</button>
                        <button id="resetPrefs" class="btn btn-outline-secondary" type="button">Restaurar padrão</button>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="/home" class="btn btn-link">Voltar</a>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const THEME_KEY = 'app_theme';
            const FONT_KEY = 'app_font_size';

            function applyTheme(theme){
                const t = theme === 'dark' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-bs-theme', t);
            }

            function applyFont(size){
                const map = { sm: '0.95rem', md: '1rem', lg: '1.125rem' };
                const val = map[size] || map['md'];
                document.documentElement.style.setProperty('--bs-body-font-size', val);
            }

            function loadPrefs(){
                const theme = localStorage.getItem(THEME_KEY) || 'light';
                const font = localStorage.getItem(FONT_KEY) || 'md';
                // set radios
                document.getElementById('theme_' + theme)?.setAttribute('checked', 'checked');
                document.getElementById('font_' + font)?.setAttribute('checked', 'checked');
                applyTheme(theme);
                applyFont(font);
            }

            function savePrefs(){
                const theme = document.querySelector('input[name="theme"]:checked')?.value || 'light';
                const font = document.querySelector('input[name="font"]:checked')?.value || 'md';
                localStorage.setItem(THEME_KEY, theme);
                localStorage.setItem(FONT_KEY, font);
                applyTheme(theme);
                applyFont(font);
            }

            function resetPrefs(){
                localStorage.removeItem(THEME_KEY);
                localStorage.removeItem(FONT_KEY);
                applyTheme('light');
                applyFont('md');
                // update radios
                document.getElementById('theme_light').checked = true;
                document.getElementById('font_md').checked = true;
            }

            document.addEventListener('DOMContentLoaded', function(){
                loadPrefs();
                document.getElementById('savePrefs').addEventListener('click', savePrefs);
                document.getElementById('resetPrefs').addEventListener('click', resetPrefs);
            });
        })();
    </script>
</x-layoutSys>

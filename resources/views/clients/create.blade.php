<x-layoutSys title="Novo Cliente">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Cadastro de Cliente</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <div id="geoAlert" class="alert d-none" role="alert"></div>
                    <form method="post" action="/clientes/salvar">
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
                                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" value="{{ old('cpf') }}" required>
                                    <label for="cpf">CPF</label>
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
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="address_line" name="address_line" placeholder="Endereço" value="{{ old('address_line') }}">
                                    <label for="address_line">Endereço</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="address_number" name="address_number" placeholder="Número" value="{{ old('address_number') }}">
                                    <label for="address_number">Número</label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Cidade" value="{{ old('city') }}">
                                    <label for="city">Cidade</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="state" name="state" placeholder="UF" value="{{ old('state') }}">
                                    <label for="state">UF</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="CEP" value="{{ old('zip_code') }}">
                                    <label for="zip_code">CEP</label>
                                </div>
                            </div>
                            <div class="col-md-8 d-flex align-items-center">
                                <div class="btn-group mt-3 mt-md-0" role="group">
                                    <button type="button" class="btn btn-outline-secondary" id="btnGeocodeCep">Buscar pelo CEP</button>
                                    <button type="button" class="btn btn-outline-secondary" id="btnGeocodeAddress">Buscar pelo endereço</button>
                                    <button type="button" class="btn btn-outline-primary" id="btnUseInRouting" disabled>Usar no cálculo</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.000001" class="form-control" id="lat" name="lat" placeholder="Latitude" value="{{ old('lat') }}">
                                    <label for="lat">Latitude</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.000001" class="form-control" id="lon" name="lon" placeholder="Longitude" value="{{ old('lon') }}">
                                    <label for="lon">Longitude</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <a href="/clientes" class="btn btn-outline-secondary">Voltar</a>
                            <button class="btn btn-primary" type="submit">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layoutSys>

<script>
(function(){
    async function geocode(payload){
        const res = await fetch('/api/geocode', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        if(!res.ok){ throw new Error(data.error || 'Falha ao geocodificar'); }
        return data;
    }
    function fillFromGeocode(data){
        if(data.lat) document.getElementById('lat').value = data.lat;
        if(data.lon) document.getElementById('lon').value = data.lon;
        const addr = data.address || {};
        const address_line = document.getElementById('address_line');
        if(address_line && !address_line.value){
            const street = addr.road || addr.street || '';
            const house = addr.house_number || '';
            const composed = [street, house].filter(Boolean).join(', ');
            if(composed) address_line.value = composed;
        }
        const cityInput = document.getElementById('city');
        if(cityInput && !cityInput.value){
            cityInput.value = addr.city || addr.town || addr.village || '';
        }
        const stateInput = document.getElementById('state');
        if(stateInput && !stateInput.value){
            stateInput.value = addr.state_code || addr.state || '';
        }
        const zipInput = document.getElementById('zip_code');
        if(zipInput && !zipInput.value){
            zipInput.value = addr.postcode || '';
        }
        // habilita botão usar no cálculo se houver lat/lon
        const useBtn = document.getElementById('btnUseInRouting');
        const lat = document.getElementById('lat').value;
        const lon = document.getElementById('lon').value;
        useBtn.disabled = !(lat && lon);
    }
    function showAlert(type, message){
        const box = document.getElementById('geoAlert');
        box.className = 'alert alert-' + type;
        box.textContent = message;
    }
    document.getElementById('btnGeocodeCep').addEventListener('click', async function(){
        const cep = document.getElementById('zip_code').value;
        const alertBox = document.getElementById('geoAlert');
        alertBox.className = 'alert alert-info';
        alertBox.textContent = 'Consultando CEP...';
        try{
            const res = await fetch('/api/cep/' + encodeURIComponent(cep));
            const data = await res.json();
            if(!res.ok){ throw new Error(data.error || 'CEP inválido'); }
            document.getElementById('zip_code').value = data.cep || cep;
            if(data.logradouro){ document.getElementById('address_line').value = data.logradouro; }
            if(data.localidade){ document.getElementById('city').value = data.localidade; }
            if(data.uf){ document.getElementById('state').value = data.uf; }
            alertBox.className = 'alert alert-success';
            alertBox.textContent = 'Endereço carregado pelo CEP. Preencha o número e clique "Buscar pelo endereço" para coordenadas.';
        }catch(err){
            alertBox.className = 'alert alert-danger';
            alertBox.textContent = err.message || 'Falha ao consultar CEP';
        }
    });
    document.getElementById('btnGeocodeAddress').addEventListener('click', async function(){
        const payload = {
            address_line: document.getElementById('address_line').value,
            city: document.getElementById('city').value,
            state: document.getElementById('state').value,
            cep: document.getElementById('zip_code').value,
        };
        try{
            const data = await geocode(payload);
            fillFromGeocode(data);
            showAlert('success','Endereço localizado e coordenadas preenchidas.');
        }catch(err){ alert(err.message || 'Erro ao geocodificar endereço'); }
    });
    document.getElementById('btnUseInRouting').addEventListener('click', function(){
        const lat = document.getElementById('lat').value;
        const lon = document.getElementById('lon').value;
        if(lat && lon){
            window.open('/routing?coords=' + encodeURIComponent(lat + ',' + lon), '_blank');
        }
    });
    // Auto-geocode quando endereço estiver completo (debounce)
    const fields = ['address_line','address_number','city','state','zip_code'];
    let timer = null;
    function maybeAutoGeocode(){
        clearTimeout(timer);
        timer = setTimeout(async ()=>{
            const line = document.getElementById('address_line').value.trim();
            const num = document.getElementById('address_number').value.trim();
            const city = document.getElementById('city').value.trim();
            const state = document.getElementById('state').value.trim();
            const cep = document.getElementById('zip_code').value.trim();
            if((line && num && city && state) || cep){
                try{
                    const data = await geocode({ address_line: line + (num?(', '+num):''), city, state, cep });
                    fillFromGeocode(data);
                    showAlert('success','Coordenadas preenchidas automaticamente a partir do endereço.');
                }catch(e){ /* silencioso para evitar excesso de alertas */ }
            }
        }, 800);
    }
    fields.forEach(id => {
        const el = document.getElementById(id);
        if(el){ el.addEventListener('input', maybeAutoGeocode); }
    });
})();
</script>

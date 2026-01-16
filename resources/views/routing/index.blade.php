<x-layoutSys title="Cálculo de Rotas">
<div class="container py-4">
  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">Calcular rota (OSRM)</div>
        <div class="card-body">
          <p class="small text-muted">Informe coordenadas (lat,lon) por linha. Ordem será preservada.</p>
          <textarea id="coords" class="form-control" rows="8" placeholder="-23.533773,-46.625290\n-23.564224,-46.651417\n-23.550520,-46.633308">-23.533773,-46.625290
-23.564224,-46.651417
-23.550520,-46.633308</textarea>
          <div class="d-grid mt-3">
            <button id="btnRoute" class="btn btn-primary">Calcular rota</button>
          </div>
          <div class="mt-3">
            <div class="alert alert-info d-none" id="summary"></div>
            <div class="alert alert-danger d-none" id="errorBox"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Mapa</span>
          <div class="d-flex gap-2">
            <button id="btnLoadClients" class="btn btn-sm btn-outline-secondary">Carregar clientes</button>
            <button id="btnAddSelected" class="btn btn-sm btn-outline-primary" disabled>Adicionar selecionados</button>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="row g-0">
            <div class="col-md-4 border-end">
              <div class="p-2" style="height: 480px; overflow: auto;">
                <div id="clientsList" class="list-group list-group-flush small"></div>
              </div>
            </div>
            <div class="col-md-8">
              <div id="map" style="height: 480px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Leaflet CSS/JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tRzW4tU8G02lG4Z8J0A0=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20mrQX0pYOr8gcNyMYQ1syZ8dTQyVbEUpf3vY9Q0XnY=" crossorigin=""></script>
<script>
(function(){
  const map = L.map('map');
  const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);
  let routeLayer = null;
  let markers = [];
  let clients = [];
  let selectedIds = new Set();

  function parseCoords(text){
    const lines = text.split(/\n+/).map(s => s.trim()).filter(Boolean);
    const pts = [];
    for(const line of lines){
      const parts = line.split(',').map(s => s.trim());
      if(parts.length !== 2) continue;
      const lat = parseFloat(parts[0]);
      const lon = parseFloat(parts[1]);
      if(Number.isFinite(lat) && Number.isFinite(lon)){
        pts.push({lat, lon});
      }
    }
    return pts;
  }

  function fitBounds(points){
    const latlngs = points.map(p => [p.lat, p.lon]);
    const bounds = L.latLngBounds(latlngs);
    if(bounds.isValid()){
      map.fitBounds(bounds.pad(0.2));
    } else {
      map.setView([-23.55, -46.63], 12);
    }
  }

  function drawPoints(points){
    // clear old markers
    for(const m of markers){ map.removeLayer(m); }
    markers = [];
    for(const p of points){
      const m = L.marker([p.lat, p.lon]);
      m.addTo(map);
      markers.push(m);
    }
  }

  async function calcRoute(){
    const coordsText = document.getElementById('coords').value;
    const points = parseCoords(coordsText);

    const errorBox = document.getElementById('errorBox');
    const summary = document.getElementById('summary');
    errorBox.classList.add('d-none');
    summary.classList.add('d-none');

    if(points.length < 2){
      errorBox.textContent = 'Informe ao menos duas coordenadas válidas (lat,lon por linha).';
      errorBox.classList.remove('d-none');
      return;
    }

    drawPoints(points);
    fitBounds(points);

    try{
      const res = await fetch('/api/routing/route', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ coordinates: points })
      });
      const data = await res.json();
      if(!res.ok){
        throw new Error(data.error || 'Falha na API');
      }

      const route = data && data.routes && data.routes[0];
      if(!route || !route.geometry || !route.geometry.coordinates){
        throw new Error('Resposta do OSRM sem geometria.');
      }

      const coords = route.geometry.coordinates; // [lon, lat]
      const latlngs = coords.map(c => [c[1], c[0]]);

      if(routeLayer){ map.removeLayer(routeLayer); }
      routeLayer = L.polyline(latlngs, { color: 'blue', weight: 4 }).addTo(map);
      map.fitBounds(routeLayer.getBounds().pad(0.2));

      const km = (route.distance / 1000).toFixed(2);
      const min = Math.round(route.duration / 60);
      summary.textContent = `Distância: ${km} km • Tempo: ${min} min`;
      summary.classList.remove('d-none');
    } catch(err){
      errorBox.textContent = err.message || String(err);
      errorBox.classList.remove('d-none');
    }
  }

  document.getElementById('btnRoute').addEventListener('click', calcRoute);
  document.getElementById('btnLoadClients').addEventListener('click', loadClients);
  document.getElementById('btnAddSelected').addEventListener('click', addSelectedToTextarea);
  // inicializa visão padrão
  map.setView([-23.55, -46.63], 12);

  // Prefill via query param ?coords=lat,lon;lat,lon
  try{
    const params = new URLSearchParams(window.location.search);
    const coordsParam = params.get('coords');
    if(coordsParam){
      const pairs = coordsParam.split(';').map(s => s.trim()).filter(Boolean);
      const points = [];
      for(const pr of pairs){
        const parts = pr.split(',').map(s => s.trim());
        if(parts.length === 2){
          const lat = parseFloat(parts[0]);
          const lon = parseFloat(parts[1]);
          if(Number.isFinite(lat) && Number.isFinite(lon)){
            points.push({lat, lon});
          }
        }
      }
      if(points.length){
        const area = document.getElementById('coords');
        area.value = points.map(p => `${p.lat},${p.lon}`).join('\n');
        drawPoints(points);
        fitBounds(points);
      }
    }
  }catch(_e){}

  async function loadClients(){
    const list = document.getElementById('clientsList');
    const addBtn = document.getElementById('btnAddSelected');
    list.innerHTML = '<div class="p-2 text-muted">Carregando clientes...</div>';
    selectedIds = new Set();
    addBtn.disabled = true;
    try{
      const res = await fetch('/api/clients/coords');
      const data = await res.json();
      clients = Array.isArray(data) ? data : [];
      if(clients.length === 0){
        list.innerHTML = '<div class="p-2 text-muted">Nenhum cliente cadastrado.</div>';
        return;
      }
      list.innerHTML = '';
      for(const c of clients){
        const item = document.createElement('label');
        item.className = 'list-group-item d-flex align-items-center gap-2';
        const coordText = (c.lat && c.lon) ? `${c.lat}, ${c.lon}` : '<span class="badge bg-warning text-dark">sem coordenadas</span>';
        item.innerHTML = `
          <input type="checkbox" class="form-check-input" value="${c.id}">
          <div>
            <div class="fw-semibold">${c.full_name}</div>
            <div class="text-muted">${coordText}</div>
            <div class="text-muted">${(c.address_line||'')}${c.address_number?(', '+c.address_number):''}${(c.city||c.state)?(' • '+(c.city||'')+'-'+(c.state||'')):'')}</div>
          </div>
        `;
        const checkbox = item.querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', (e)=>{
          const id = Number(e.target.value);
          if(e.target.checked){
            selectedIds.add(id);
          } else {
            selectedIds.delete(id);
          }
          addBtn.disabled = selectedIds.size === 0;
        });
        list.appendChild(item);
      }
    }catch(err){
      list.innerHTML = '<div class="p-2 text-danger">Erro ao carregar clientes.</div>';
    }
  }

  async function addSelectedToTextarea(){
    const textArea = document.getElementById('coords');
    let selected = clients.filter(c => selectedIds.has(Number(c.id)));
    if(selected.length === 0) return;
    const missing = selected.filter(c => !(c.lat && c.lon)).map(c => c.id);
    if(missing.length){
      try{
        const res = await fetch('/api/clients/geocode-coords', {
          method: 'POST', headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ ids: missing })
        });
        const data = await res.json();
        if(Array.isArray(data)){
          const mapCoords = new Map(data.map(d => [Number(d.id), d]));
          selected = selected.map(c => {
            const upd = mapCoords.get(Number(c.id));
            if(upd){ c.lat = upd.lat; c.lon = upd.lon; }
            return c;
          });
        }
      }catch(e){ /* prossegue com o que tiver */ }
    }
    const withCoords = selected.filter(c => c.lat && c.lon);
    if(withCoords.length === 0){ return; }
    const lines = withCoords.map(c => `${c.lat},${c.lon}`);
    const current = textArea.value.trim();
    textArea.value = current ? (current + '\n' + lines.join('\n')) : lines.join('\n');
    const points = withCoords.map(c => ({lat: Number(c.lat), lon: Number(c.lon)}));
    drawPoints(points);
    fitBounds(points);
  }
})();
</script>
</x-layoutSys>
<script>
// Funções auxiliares para clientes (inseridas fora do IIFE principal para clareza)
</script>

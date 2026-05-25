(function(){
  const routes = ['inicio','estadisticas','empresas','bolsa'];
  function qs(sel) {return document.querySelector(sel)}
  function qsa(sel){return document.querySelectorAll(sel)}

  // SPA navigation
  document.addEventListener('click', e=>{
    const a = e.target.closest('[data-link]');
    if (a) {
      e.preventDefault();
      const href = a.getAttribute('href').replace('#','');
      navigate(href);
    }
  });

  function navigate(name){
    routes.forEach(r=>{
      const el = qs('#'+r);
      if (el) el.classList.toggle('active', r===name);
    });
    if (name === 'estadisticas') loadKPIs();
    if (name === 'empresas') loadEmpresas();
    if (name === 'bolsa') loadOfertas();
  }

  // default
  if (!location.hash) location.hash='#inicio';
  navigate(location.hash.replace('#',''));

  // KPIs + charts
  async function loadKPIs(){
    try{
      const res = await fetch('/portal_unam_web/api/kpis/resumen');
      const data = await res.json();
      qs('#kpi-egresados strong').textContent = data.egresados.toLocaleString();
      qs('#kpi-bach strong').textContent = data.bachilleres.toLocaleString();
      qs('#kpi-tit strong').textContent = data.titulados.toLocaleString();
      qs('#kpi-ofertas strong').textContent = data.ofertas.toLocaleString();

      const escRes = await fetch('/portal_unam_web/api/kpis/por-escuela');
      const escData = await escRes.json();
      const labels = escData.map(d=>d.escuela);
      const vals = escData.map(d=>+d.total_egresados);
      renderChart('chartEscuela', labels, vals, 'bar');

      const anioRes = await fetch('/portal_unam_web/api/kpis/por-anio');
      const anioData = await anioRes.json();
      renderChart('chartAnio', anioData.map(d=>d.anio_egreso), anioData.map(d=>+d.total), 'line');
    }catch(e){console.error(e)}
  }

  function renderChart(id, labels, data, type){
    const el = qs('#'+id);
    if (!el) return;
    if (el.chart) el.chart.destroy();
    el.chart = new Chart(el, {type, data:{labels, datasets:[{label:'Total',data}]}, options:{responsive:true}});
  }

  // Empresas
  async function loadEmpresas(){
    const res = await fetch('/portal_unam_web/empleadores');
    const html = await res.text();
    // crude: extract list items from existing view by parsing HTML
    const wrapper = document.createElement('div'); wrapper.innerHTML = html;
    const cards = wrapper.querySelectorAll('.card');
    const out = qs('#empresas-list'); out.innerHTML='';
    cards.forEach(c=>{ const node = document.createElement('div'); node.className='card'; node.innerHTML = c.innerHTML; out.appendChild(node); });

    // fill login modal options
    const select = qs('#empresaSelect'); select.innerHTML='';
    cards.forEach((c,i)=>{ const id = c.querySelector('a')?.getAttribute('href')?.match(/(\d+)/); const name = c.querySelector('.card-title')?.textContent || ('Empresa '+(i+1)); if (id) { const opt = document.createElement('option'); opt.value=id[1]; opt.textContent = name; select.appendChild(opt); } });
    // also populate register select if present
    const regSel = qs('#registerEmpresaSelect'); if (regSel) { regSel.innerHTML=''; cards.forEach((c,i)=>{ const id = c.querySelector('a')?.getAttribute('href')?.match(/(\d+)/); const name = c.querySelector('.card-title')?.textContent || ('Empresa '+(i+1)); if (id) { const opt = document.createElement('option'); opt.value=id[1]; opt.textContent = name; regSel.appendChild(opt); } }); }
  }

  // Ofertas
  async function loadOfertas(){
    const res = await fetch('/portal_unam_web/ofertas');
    const html = await res.text();
    const wrapper = document.createElement('div'); wrapper.innerHTML = html;
    const items = wrapper.querySelectorAll('.oferta-card');
    const out = qs('#ofertas-list'); out.innerHTML='';
    items.forEach(it=>{ const node = document.createElement('div'); node.className='card'; node.innerHTML = it.innerHTML; out.appendChild(node); });

    // empresa login controls (use server-side session)
    qs('#loginBtn').addEventListener('click', ()=> qs('#loginModal').style.display='flex');
    qs('#empresaClose').addEventListener('click', ()=> qs('#loginModal').style.display='none');
    // register modal
    const regBtn = qs('#registerBtn'); if (regBtn) regBtn.addEventListener('click', ()=> { qs('#registerModal').style.display='flex'; });
    const regClose = qs('#registerClose'); if (regClose) regClose.addEventListener('click', ()=> qs('#registerModal').style.display='none');
    const regSubmit = qs('#registerSubmit'); if (regSubmit) regSubmit.addEventListener('click', async ()=>{
      const sel = qs('#registerEmpresaSelect'); const pwd = qs('#registerPassword');
      if (!sel || !pwd) return alert('Formulario incompleto');
      const empleador_id = sel.value; const password = pwd.value;
      if (!empleador_id || !password) return alert('Selecciona empresa y contraseña');
      try {
        const r = await fetch('/portal_unam_web/api/auth/register', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({empleador_id, password})});
        if (r.ok) { alert('Registro exitoso. Ya puedes iniciar sesión.'); qs('#registerModal').style.display='none'; }
        else { const b = await r.json().catch(()=>({})); alert('Error registro: '+(b.error||r.statusText)); }
      } catch(err){ console.error(err); alert('Error de red'); }
    });
    // seed demo
    const seedBtn = qs('#seedBtn'); if (seedBtn) seedBtn.addEventListener('click', async ()=>{
      if (!confirm('Sembrar datos demo en la base de datos?')) return;
      try {
        const r = await fetch('/portal_unam_web/api/seed_demo', {method:'POST'});
        const body = await r.json();
        if (r.ok) { alert('Seed ejecutado: ' + JSON.stringify(body)); loadEmpresas(); loadKPIs(); loadOfertas(); }
        else { alert('Error seed: '+JSON.stringify(body)); }
      } catch(e){ console.error(e); alert('Error de red'); }
    });
    qs('#empresaLogin').addEventListener('click', async ()=>{
      const id = qs('#empresaSelect').value;
      const pwd = qs('#empresaPassword').value || '';
      if (!id) return alert('Selecciona empresa');
      try {
        const r = await fetch('/portal_unam_web/api/auth/login', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({empleador_id: id, password: pwd})});
        if (r.ok) {
          alert('Login exitoso');
          qs('#oferta-form').style.display='block';
          qs('#loginModal').style.display='none';
        } else {
          const body = await r.json().catch(()=>({}));
          alert('Error login: '+(body.error||r.statusText));
        }
      } catch(err){ console.error(err); alert('Error de red'); }
    });

    // create oferta
    const form = qs('#postOferta'); if (form) {
      form.addEventListener('submit', async e=>{
        e.preventDefault();
        const fd = new FormData(form);
        const payload = Object.fromEntries(fd.entries());
        // server uses PHP session to identify empleador, do not send empleador_id
        const r = await fetch('/portal_unam_web/api/ofertas', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload)});
        if (r.ok) { alert('Oferta publicada (demo).'); loadOfertas(); }
        else alert('Error');
      });
    }
  }

  // simple load home
  window.loadKPIs = loadKPIs;

})();
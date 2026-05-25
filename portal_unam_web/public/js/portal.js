// ══════════════════════════════════════════════════════════════
//  PORTAL DE EGRESADOS – UNAM MOQUEGUA · portal.js
// ══════════════════════════════════════════════════════════════

// ─── Sidebar toggle (móvil) ────────────────────────────────────
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
}

// ─── Paleta de colores ─────────────────────────────────────────
const PALETTE = {
  blue:  '#2456a4',
  sky:   '#3b82f6',
  gold:  '#e8a020',
  teal:  '#0d9488',
  rose:  '#e53e3e',
  navy:  '#0a1628',
  muted: '#94a3b8',
  light: '#e8eef6',
};

const ESCUELA_COLORS = [
  '#2456a4','#3b82f6','#0d9488','#e8a020','#e53e3e','#8b5cf6','#f59e0b','#10b981'
];

Chart.defaults.font.family = "'Sora', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#64748b';

// ─── Helpers ──────────────────────────────────────────────────
function shortenLabel(label, max = 22) {
  return label.length > max ? label.slice(0, max) + '…' : label;
}

function api(path) {
  return `${window.APP_BASE_URL}/${path.replace(/^\/+/, '')}`;
}

// ─── Dashboard: carga de gráficos ─────────────────────────────
async function loadDashboardCharts() {
  try {
    await Promise.all([
      loadChartEscuela(),
      loadChartAnio(),
      loadChartSede(),
      loadChartSexo(),
      loadChartTitulados(),
    ]);
  } catch (e) {
    console.warn('Error al cargar gráficos:', e);
  }
}

// Gráfico 1 – Egresados por Escuela (barras horizontales)
async function loadChartEscuela() {
  const el = document.getElementById('chartEscuela');
  if (!el) return;
  const res  = await fetch(api('api/kpis/por-escuela'));
  const data = await res.json();

  const labels   = data.map(d => shortenLabel(d.escuela));
  const egresados = data.map(d => +d.total_egresados);
  const bach      = data.map(d => +d.bachilleres);
  const tit       = data.map(d => +d.titulados);

  new Chart(el, {
    type: 'bar',
    data: {
      labels,
      datasets: [
        { label: 'Egresados', data: egresados, backgroundColor: PALETTE.blue + 'cc', borderRadius: 4 },
        { label: 'Bachilleres', data: bach, backgroundColor: PALETTE.gold + 'cc', borderRadius: 4 },
        { label: 'Titulados', data: tit, backgroundColor: PALETTE.teal + 'cc', borderRadius: 4 },
      ]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'top' } },
      scales: {
        x: { grid: { color: '#f1f5f9' }, beginAtZero: true },
        y: { grid: { display: false } }
      }
    }
  });
}

// Gráfico 2 – Evolución por año (líneas)
async function loadChartAnio() {
  const el = document.getElementById('chartAnio');
  if (!el) return;
  const res  = await fetch(api('api/kpis/por-anio'));
  const data = await res.json();

  new Chart(el, {
    type: 'line',
    data: {
      labels: data.map(d => d.anio_egreso),
      datasets: [
        {
          label: 'Egresados', data: data.map(d => +d.total),
          borderColor: PALETTE.blue, backgroundColor: PALETTE.blue + '18',
          tension: .4, fill: true, pointRadius: 4, pointHoverRadius: 6,
        },
        {
          label: 'Bachilleres', data: data.map(d => +d.bachilleres),
          borderColor: PALETTE.gold, backgroundColor: 'transparent',
          tension: .4, pointRadius: 3, pointHoverRadius: 5,
        },
        {
          label: 'Titulados', data: data.map(d => +d.titulados),
          borderColor: PALETTE.teal, backgroundColor: 'transparent',
          tension: .4, pointRadius: 3, pointHoverRadius: 5,
        },
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'top' } },
      scales: {
        x: { grid: { display: false } },
        y: { grid: { color: '#f1f5f9' }, beginAtZero: true }
      }
    }
  });
}

// Gráfico 3 – Por sede (dona)
async function loadChartSede() {
  const el = document.getElementById('chartSede');
  if (!el) return;
  const res  = await fetch(api('api/kpis/por-sede'));
  const data = await res.json();

  new Chart(el, {
    type: 'doughnut',
    data: {
      labels: data.map(d => d.sede),
      datasets: [{
        data: data.map(d => +d.total),
        backgroundColor: [PALETTE.blue, PALETTE.gold],
        borderWidth: 2, borderColor: '#fff',
        hoverOffset: 8,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' },
        tooltip: {
          callbacks: {
            label: ctx => ` ${ctx.label}: ${ctx.raw} egresados`
          }
        }
      },
      cutout: '65%',
    }
  });
}

// Gráfico 4 – Por sexo (dona)
async function loadChartSexo() {
  const el = document.getElementById('chartSexo');
  if (!el) return;
  const res  = await fetch(api('api/kpis/por-sexo'));
  const data = await res.json();

  const label = d => d.sexo === 'M' ? 'Masculino' : 'Femenino';
  new Chart(el, {
    type: 'doughnut',
    data: {
      labels: data.map(label),
      datasets: [{
        data: data.map(d => +d.total),
        backgroundColor: [PALETTE.sky, PALETTE.rose],
        borderWidth: 2, borderColor: '#fff',
        hoverOffset: 8,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } },
      cutout: '65%',
    }
  });
}

// Gráfico 5 – Titulados vs Bachilleres por escuela (barras apiladas)
async function loadChartTitulados() {
  const el = document.getElementById('chartTitulados');
  if (!el) return;
  const res  = await fetch(api('api/kpis/titulados-escuela'));
  const data = await res.json();

  new Chart(el, {
    type: 'bar',
    data: {
      labels: data.map(d => shortenLabel(d.escuela)),
      datasets: [
        { label: 'Titulados', data: data.map(d => +d.titulados), backgroundColor: PALETTE.teal + 'dd', borderRadius: 4, stack: 'a' },
        { label: 'Bachilleres', data: data.map(d => +d.bachilleres), backgroundColor: PALETTE.gold + 'dd', borderRadius: 4, stack: 'a' },
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'top' } },
      scales: {
        x: { stacked: true, grid: { display: false } },
        y: { stacked: true, grid: { color: '#f1f5f9' }, beginAtZero: true }
      }
    }
  });
}

// ─── Contador animado ─────────────────────────────────────────
function animateCounters() {
  document.querySelectorAll('.kpi-value[data-target]').forEach(el => {
    const target = +el.dataset.target;
    let current = 0;
    const step = Math.ceil(target / 60);
    const timer = setInterval(() => {
      current = Math.min(current + step, target);
      el.textContent = current.toLocaleString('es-PE');
      if (current >= target) clearInterval(timer);
    }, 20);
  });
}

// ─── Inicialización ───────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  animateCounters();
  if (document.getElementById('chartAnio')) {
    loadDashboardCharts();
  }
});

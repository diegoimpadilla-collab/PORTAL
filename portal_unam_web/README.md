# Portal de Egresados – UNAM Moquegua
## Guía de Instalación en XAMPP

---

## ESTRUCTURA DE ARCHIVOS GENERADOS

Todos los archivos van dentro del framework CodeIgniter 4 que descargaste:

```
C:\xampp\htdocs\portal_unam\PORTAL WEB\
│
├── .env                          ← Crear desde .env.example
├── app/
│   ├── Config/
│   │   ├── Database.php          ← Configuración BD
│   │   └── Routes.php            ← Rutas del sistema
│   │
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── EgresadosController.php
│   │   ├── EmpleadoresController.php
│   │   └── OfertasController.php
│   │
│   ├── Models/
│   │   ├── EgresadoModel.php
│   │   ├── EscuelaModel.php
│   │   ├── EmpleadorModel.php
│   │   └── OfertaModel.php
│   │
│   └── Views/
│       ├── layouts/
│       │   └── main.php          ← Layout HTML principal
│       ├── dashboard/
│       │   └── index.php         ← KPIs y gráficos
│       ├── egresados/
│       │   ├── index.php         ← Listado + filtros
│       │   └── detalle.php
│       ├── empleadores/
│       │   ├── index.php
│       │   └── detalle.php
│       ├── ofertas/
│       │   ├── index.php
│       │   └── detalle.php
│       └── errors/
│           └── not_found.php
│
└── public/
    ├── .htaccess                 ← Ya existe en CodeIgniter
    ├── index.php                 ← Ya existe en CodeIgniter
    ├── css/
    │   └── portal.css            ← Estilos del portal
    └── js/
        └── portal.js             ← Lógica + Chart.js
```

---

## PASOS DE INSTALACIÓN

### Paso 1 – Preparar carpeta base
1. Descomprime `codeigniter4-framework-v4.7.2` en:
   `C:\xampp\htdocs\portal_unam\PORTAL WEB\`

### Paso 2 – Crear la base de datos
1. Abre tu navegador → `http://localhost/phpmyadmin`
2. Clic en "Nueva" → escribe `portal_egresados_unam` → Crear
3. Selecciona la BD → pestaña "Importar"
4. Sube el archivo `portal_egresados_unam.sql`
5. Clic en "Continuar"

### Paso 3 – Pegar los archivos del portal
Pega cada archivo en su ruta exacta dentro de:
`C:\xampp\htdocs\portal_unam\PORTAL WEB\`

> ⚠️ Los archivos de Config/ **REEMPLAZAN** a los originales de CodeIgniter.

### Paso 4 – Configurar el archivo .env
1. Copia `.env.example` y renómbralo `.env` (sin extensión)
2. Verifica que la baseURL sea correcta:
   ```
   app.baseURL = 'http://localhost/portal_unam/PORTAL WEB/public/'
   ```
3. Si tu MySQL tiene contraseña, agrégala en:
   ```
   database.default.password = TU_CONTRASEÑA
   ```

### Paso 5 – Verificar mod_rewrite en XAMPP
1. Abre `C:\xampp\apache\conf\httpd.conf`
2. Busca esta línea y quita el `#` si lo tiene:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. Reinicia Apache desde el panel de XAMPP

### Paso 6 – Acceder al portal
Abre tu navegador en:
```
http://localhost/portal_unam/PORTAL WEB/public/
```

---

## MÓDULOS DEL PORTAL

| Módulo          | URL                        | Descripción                         |
|-----------------|----------------------------|-------------------------------------|
| Dashboard       | /                          | KPIs + 5 gráficos interactivos      |
| Egresados       | /egresados                 | Directorio con filtros y paginación |
| Empleadores     | /empleadores               | Cards de empresas aliadas           |
| Bolsa de Trabajo| /ofertas                   | Ofertas laborales activas           |

### API de KPIs (para los gráficos)
| Endpoint                     | Datos                              |
|------------------------------|------------------------------------|
| /api/kpis/resumen            | Totales: egresados/bach/tit/ofertas|
| /api/kpis/por-escuela        | Desglose por escuela               |
| /api/kpis/por-anio           | Evolución anual                    |
| /api/kpis/por-sede           | Moquegua vs Ilo                    |
| /api/kpis/por-sexo           | Masculino vs Femenino              |
| /api/kpis/titulados-escuela  | Titulados/bachilleres por escuela  |

---

## NOTAS TÉCNICAS

- **Framework**: CodeIgniter 4.7.2
- **BD**: MySQL / MariaDB (XAMPP)
- **Gráficos**: Chart.js 4.4 (CDN, sin instalación)
- **Fuentes**: Google Fonts (Sora + DM Mono)
- **PHP mínimo**: 7.4 (XAMPP incluye 8.x)

---

## PROBLEMAS COMUNES

**Error 404 en rutas**
→ Verifica que `mod_rewrite` esté activo y que `.htaccess` en `public/` exista.

**Error de conexión a BD**
→ Revisa usuario/contraseña en `app/Config/Database.php` y en `.env`.

**Página en blanco**
→ Cambia `CI_ENVIRONMENT = development` en `.env` para ver errores.

**CSS/JS no cargan**
→ Revisa que `app.baseURL` en `.env` tenga la URL correcta con `/public/`.

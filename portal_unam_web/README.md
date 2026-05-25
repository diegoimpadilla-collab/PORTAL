# Portal de Egresados – UNAM Moquegua

## Requisitos
- XAMPP (PHP 8.1+, MySQL, Apache)
- Composer instalado globalmente
- Cuenta en ngrok.com (gratis)

---

## INSTALACIÓN COMPLETA (pasos exactos)

### Paso 1 – Clonar el repositorio
```bash
cd C:\xampp\htdocs
git clone https://github.com/diegoimpadilla-collab/PORTAL.git
```
Quedará en: `C:\xampp\htdocs\PORTAL\portal_unam_web\`

### Paso 2 – Instalar dependencias con Composer
```bash
cd C:\xampp\htdocs\PORTAL\portal_unam_web
composer install
```
Esto descarga CodeIgniter 4 completo en la carpeta `vendor/`.

### Paso 3 – Crear base de datos
1. Abre `http://localhost/phpmyadmin`
2. Crea BD: `portal_egresados_unam`
3. Importa el archivo `portal_egresados_unam.sql`

### Paso 4 – Configurar .env
```bash
copy .env.example .env
```
Edita `.env`:
```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/PORTAL/portal_unam_web/public/'
database.default.password = 
```

### Paso 5 – Verificar en local
Abre: `http://localhost/PORTAL/portal_unam_web/public/`

---

## PUBLICAR CON NGROK

### Paso 1 – Instalar ngrok
Descarga desde https://ngrok.com/download e instálalo.

### Paso 2 – Autenticarse (solo primera vez)
```bash
ngrok config add-authtoken TU_TOKEN_AQUI
```

### Paso 3 – Exponer el puerto de Apache (80)
```bash
ngrok http 80
```

### Paso 4 – Actualizar baseURL en .env
Ngrok te da una URL como `https://abc123.ngrok-free.app`

Edita `.env`:
```
app.baseURL = 'https://abc123.ngrok-free.app/PORTAL/portal_unam_web/public/'
```

### Paso 5 – Reiniciar Apache desde XAMPP
Detén y vuelve a iniciar Apache en el Panel de XAMPP.

### Paso 6 – Compartir la URL ngrok
La URL pública será:
```
https://abc123.ngrok-free.app/PORTAL/portal_unam_web/public/
```

---

## SOLUCIÓN DE ERRORES COMUNES

### ❌ "vendor does not exist"
```bash
composer install
```

### ❌ Error 404 en rutas
- Verifica que `mod_rewrite` esté activo en Apache
- El `.htaccess` en `public/` debe existir

### ❌ "The action you requested is not allowed" (ngrok)
Edita `.env`:
```
app.baseURL = 'https://TU-URL.ngrok-free.app/PORTAL/portal_unam_web/public/'
```

### ❌ Error de base de datos
Verifica en `.env`:
```
database.default.hostname = localhost
database.default.username = root
database.default.password = 
database.default.database = portal_egresados_unam
```

### ❌ CSS/JS no cargan via ngrok
El `app.baseURL` debe terminar en `/public/` con la URL de ngrok correcta.

### ❌ Página en blanco
Cambia en `.env`:
```
CI_ENVIRONMENT = development
```
Esto mostrará los errores reales.

---

## ESTRUCTURA DEL PROYECTO

```
portal_unam_web/
├── .env                    ← Crea desde .env.example
├── .env.example            ← Plantilla de configuración
├── .htaccess               ← Redirige a public/
├── composer.json           ← Dependencias (CI4)
├── vendor/                 ← Generado por composer install
├── writable/               ← Cache, logs, sessions
├── app/
│   ├── Config/
│   │   ├── App.php         ← baseURL, configuración app
│   │   ├── Database.php    ← Credenciales BD
│   │   ├── Filters.php     ← Filtros (CSRF desactivado)
│   │   ├── Paths.php       ← Rutas del framework
│   │   └── Routes.php      ← Rutas URL
│   ├── Controllers/
│   ├── Models/
│   └── Views/
└── public/
    ├── .htaccess           ← Reescritura de URLs
    ├── index.php           ← Front controller CI4
    ├── css/
    ├── js/
    └── img/
```

---

## Módulos

| Módulo | URL | Descripción |
|--------|-----|-------------|
| Dashboard | `/` | KPIs + 5 gráficos |
| Egresados | `/egresados` | Directorio con filtros |
| Empleadores | `/empleadores` | Empresas aliadas |
| Bolsa de Trabajo | `/ofertas` | Ofertas laborales |

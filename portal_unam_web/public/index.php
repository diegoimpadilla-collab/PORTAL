<?php
// Minimal application entry point for Portal UNAM
// This file enables the existing views to work without the full CodeIgniter framework.

define('ROOT_PATH', dirname(__DIR__));

session_start();
db_connect();

$uri = normalize_uri($_SERVER['REQUEST_URI'] ?? '/');
$path = trim(strip_base_path($uri), '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$routeHandlers = [
    '#^$#' => 'dashboard_page',
    '#^dashboard/?$#' => 'dashboard_page',
    '#^public/?$#' => 'dashboard_page',
    '#^public/index.php$#' => 'dashboard_page',
    '#^egresados/?$#' => 'egresados_page',
    '#^egresados/buscar/?$#' => 'egresados_page',
    '#^egresados/(\d+)/?$#' => 'egresado_detail_page',
    '#^empleadores/?$#' => 'empleadores_page',
    '#^empleadores/(\d+)/?$#' => 'empleador_detail_page',
    '#^ofertas/?$#' => 'ofertas_page',
    '#^ofertas/(\d+)/?$#' => 'oferta_detail_page',
    '#^api/kpis/resumen/?$#' => 'api_resumen',
    '#^api/kpis/por-escuela/?$#' => 'api_por_escuela',
    '#^api/kpis/por-anio/?$#' => 'api_por_anio',
    '#^api/kpis/por-sede/?$#' => 'api_por_sede',
    '#^api/kpis/por-sexo/?$#' => 'api_por_sexo',
    '#^api/kpis/titulados-escuela/?$#' => 'api_por_escuela_titulados',
    '#^api/empleadores/?$#' => 'api_empleadores',
    '#^api/auth/register/?$#' => 'api_auth_register',
    '#^api/auth/login/?$#' => 'api_auth_login',
    '#^api/auth/logout/?$#' => 'api_auth_logout',
    '#^api/seed_demo/?$#' => 'api_seed_demo',
    '#^api/ofertas/?$#' => 'api_ofertas',
];

foreach ($routeHandlers as $pattern => $handler) {
    if (preg_match($pattern, $path, $matches)) {
        array_shift($matches);
        call_user_func_array($handler, $matches);
        exit;
    }
}

not_found_page();

function normalize_uri(string $uri): string
{
    $uri = parse_url($uri, PHP_URL_PATH) ?? '/';
    return '/' . ltrim(str_replace('\\', '/', $uri), '/');
}

function strip_base_path(string $uri): string
{
    $uri = '/' . ltrim($uri, '/');
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = rtrim($scriptDir, '/');
    $projectDir = '/' . trim(basename(ROOT_PATH));

    $candidates = array_filter([
        $scriptDir,
        $scriptDir . '/public',
        $projectDir,
        $projectDir . '/public'
    ]);

    foreach ($candidates as $cand) {
        if ($cand !== '' && strpos($uri, $cand) === 0) {
            $uri = substr($uri, strlen($cand));
            break;
        }
    }

    return '/' . ltrim($uri, '/');
}

function base_url(string $path = ''): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $root = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    // If script is inside a "public" folder, expose root without '/public'
    if (str_ends_with($root, '/public')) {
        $root = substr($root, 0, -7);
    }
    if ($root === '/') {
        $root = '';
    }
    if ($path === '/' || $path === '') {
        return $scheme . '://' . $host . $root . '/';
    }
    return $scheme . '://' . $host . $root . '/' . ltrim($path, '/');
}

function uri_string(): string
{
    $uri = normalize_uri($_SERVER['REQUEST_URI'] ?? '/');
    // Remove project folder and/or public prefix from uri string
    $projectDir = '/' . trim(basename(ROOT_PATH));
    $uri = preg_replace('#^' . preg_quote($projectDir, '#') . '(?:/public)?#', '', $uri);
    $uri = preg_replace('#^/public#', '', $uri);
    return trim($uri, '/');
}

function esc($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function view(string $view, array $data = []): string
{
    $viewFile = ROOT_PATH . '/app/Views/' . str_replace(['.', '\\'], '/', $view) . '.php';
    if (!is_file($viewFile)) {
        throw new RuntimeException("View not found: $viewFile");
    }
    extract($data, EXTR_SKIP);
    ob_start();
    include $viewFile;
    return ob_get_clean();
}

function render(string $view, array $data = []): void
{
    $content = view($view, $data);
    echo view('layouts/main', $data + ['content' => $content]);
}

function db_connect(): void
{
    static $connected = false;
    if ($connected) {
        return;
    }
    $config = db_config();
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database'], (int) $config['port']);
    $db->set_charset($config['charset']);
    $GLOBALS['DB_CONN'] = $db;
    $connected = true;
}

function db_config(): array
{
    $defaults = [
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'portal_egresados_unam',
        'port'     => 3306,
        'charset'  => 'utf8mb4',
    ];
    $env = load_env();
    if (!empty($env['database.default.hostname'])) {
        $defaults['hostname'] = $env['database.default.hostname'];
    }
    if (!empty($env['database.default.username'])) {
        $defaults['username'] = $env['database.default.username'];
    }
    if (isset($env['database.default.password'])) {
        $defaults['password'] = $env['database.default.password'];
    }
    if (!empty($env['database.default.database'])) {
        $defaults['database'] = $env['database.default.database'];
    }
    if (!empty($env['database.default.port'])) {
        $defaults['port'] = (int) $env['database.default.port'];
    }
    if (!empty($env['database.default.charset'])) {
        $defaults['charset'] = $env['database.default.charset'];
    }
    return $defaults;
}

function load_env(): array
{
    static $env = null;
    if ($env !== null) {
        return $env;
    }
    $env = [];
    $envFile = ROOT_PATH . '/.env';
    if (!is_file($envFile)) {
        return $env;
    }
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2) + ['', '']);
        $env[$key] = trim($value, "'\" ");
    }
    return $env;
}

function db_query(string $sql, array $params = []): mysqli_result
{
    $db = $GLOBALS['DB_CONN'];
    if (empty($params)) {
        $result = $db->query($sql);
        if ($result === false) {
            throw new RuntimeException($db->error);
        }
        return $result;
    }
    $stmt = $db->prepare($sql);
    if ($stmt === false) {
        throw new RuntimeException($db->error);
    }
    if ($params) {
        $types = '';
        $values = [];
        foreach ($params as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
            $values[] = $value;
        }
        $stmt->bind_param($types, ...$values);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result === false) {
        throw new RuntimeException($stmt->error);
    }
    return $result;
}

function db_fetch_all(string $sql, array $params = []): array
{
    $result = db_query($sql, $params);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function db_fetch_row(string $sql, array $params = []): ?array
{
    $result = db_query($sql, $params);
    $row = $result->fetch_assoc();
    return $row === null ? null : $row;
}

function db_fetch_value(string $sql, array $params = []): mixed
{
    $row = db_fetch_row($sql, $params);
    return $row ? array_shift($row) : null;
}

function dashboard_page(): void
{
    $data = [
        'title'            => 'Dashboard – Portal de Egresados UNAM',
        'total_egresados'  => (int) db_fetch_value('SELECT COUNT(*) FROM egresados'),
        'total_bachilleres'=> (int) db_fetch_value('SELECT COUNT(*) FROM egresados WHERE es_bachiller = 1'),
        'total_titulados'  => (int) db_fetch_value('SELECT COUNT(*) FROM egresados WHERE es_titulado = 1'),
        'total_ofertas'    => (int) db_fetch_value('SELECT COUNT(*) FROM ofertas_laborales WHERE activa = 1'),
        'ofertas_recientes'=> db_fetch_all(
            'SELECT o.*, emp.razon_social AS empresa, emp.sector, emp.ciudad AS ubicacion, ep.nombre AS escuela_nombre, ep.codigo AS escuela_codigo
             FROM ofertas_laborales o
             JOIN empleadores emp ON emp.id = o.empleador_id
             LEFT JOIN escuelas_profesionales ep ON ep.id = o.escuela_id
             WHERE o.activa = 1
             ORDER BY o.fecha_pub DESC
             LIMIT 4'
        ),
        'top_empleadores'  => db_fetch_all(
            'SELECT e.razon_social, e.sector, e.ciudad, SUM(ee.egresados_contratados) AS total
             FROM empleadores_escuelas ee
             JOIN empleadores e ON e.id = ee.empleador_id
             WHERE e.activo = 1
             GROUP BY ee.empleador_id
             ORDER BY total DESC
             LIMIT 5'
        ),
        'escuelas'         => db_fetch_all('SELECT * FROM escuelas_profesionales WHERE activo = 1 ORDER BY nombre'),
    ];
    render('dashboard/index', $data);
}

function api_resumen(): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'egresados'   => (int) db_fetch_value('SELECT COUNT(*) FROM egresados'),
        'bachilleres' => (int) db_fetch_value('SELECT COUNT(*) FROM egresados WHERE es_bachiller = 1'),
        'titulados'   => (int) db_fetch_value('SELECT COUNT(*) FROM egresados WHERE es_titulado = 1'),
        'ofertas'     => (int) db_fetch_value('SELECT COUNT(*) FROM ofertas_laborales WHERE activa = 1'),
    ], JSON_UNESCAPED_UNICODE);
}

function api_por_escuela(): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(db_fetch_all(
        'SELECT ep.nombre AS escuela, ep.codigo,
                COUNT(*) AS total_egresados,
                SUM(e.es_bachiller) AS bachilleres,
                SUM(e.es_titulado) AS titulados
         FROM egresados e
         JOIN escuelas_profesionales ep ON ep.id = e.escuela_id
         GROUP BY e.escuela_id
         ORDER BY total_egresados DESC'
    ), JSON_UNESCAPED_UNICODE);
}

function api_por_anio(): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(db_fetch_all(
        'SELECT anio_egreso, COUNT(*) AS total,
                SUM(es_bachiller) AS bachilleres,
                SUM(es_titulado) AS titulados
         FROM egresados
         WHERE anio_egreso IS NOT NULL
         GROUP BY anio_egreso
         ORDER BY anio_egreso ASC'
    ), JSON_UNESCAPED_UNICODE);
}

function api_por_sede(): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(db_fetch_all(
        'SELECT sede, COUNT(*) AS total,
                SUM(es_bachiller) AS bachilleres,
                SUM(es_titulado) AS titulados
         FROM egresados
         GROUP BY sede'
    ), JSON_UNESCAPED_UNICODE);
}

function api_por_sexo(): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(db_fetch_all(
        'SELECT sexo, COUNT(*) AS total
         FROM egresados
         GROUP BY sexo'
    ), JSON_UNESCAPED_UNICODE);
}

function api_por_escuela_titulados(): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(db_fetch_all(
        'SELECT ep.nombre AS escuela,
                SUM(e.es_titulado) AS titulados,
                SUM(e.es_bachiller) AS bachilleres
         FROM egresados e
         JOIN escuelas_profesionales ep ON ep.id = e.escuela_id
         GROUP BY e.escuela_id
         ORDER BY titulados DESC'
    ), JSON_UNESCAPED_UNICODE);
}

function ensure_usuarios_table()
{
    // Create simple empleadores_users table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS empleadores_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        empleador_id INT NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    try { db_query($sql); } catch (Throwable $e) { /* ignore */ }
}

function api_auth_register(): void
{
    header('Content-Type: application/json; charset=utf-8');
    $body = json_decode(file_get_contents('php://input'), true);
    $empleador_id = $body['empleador_id'] ?? null;
    $password = $body['password'] ?? null;
    if (!$empleador_id || !$password) {
        http_response_code(400);
        echo json_encode(['error'=>'missing_fields']);
        return;
    }
    ensure_usuarios_table();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    db_query('INSERT INTO empleadores_users (empleador_id, password_hash) VALUES (?, ?)', [$empleador_id, $hash]);
    echo json_encode(['status'=>'ok']);
}

function api_auth_login(): void
{
    header('Content-Type: application/json; charset=utf-8');
    $body = json_decode(file_get_contents('php://input'), true);
    $empleador_id = $body['empleador_id'] ?? null;
    $password = $body['password'] ?? null;
    if (!$empleador_id || !$password) {
        http_response_code(400);
        echo json_encode(['error'=>'missing_fields']);
        return;
    }
    ensure_usuarios_table();
    $row = db_fetch_row('SELECT * FROM empleadores_users WHERE empleador_id = ? ORDER BY id DESC LIMIT 1', [$empleador_id]);
    if (!$row || !password_verify($password, $row['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error'=>'invalid_credentials']);
        return;
    }
    // set session
    $_SESSION['empresa_id'] = (int) $empleador_id;
    echo json_encode(['status'=>'ok']);
}

function api_auth_logout(): void
{
    session_unset();
    session_destroy();
    echo json_encode(['status'=>'ok']);
}

function api_empleadores(): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(db_fetch_all('SELECT * FROM empleadores WHERE activo = 1 ORDER BY razon_social'), JSON_UNESCAPED_UNICODE);
}

function api_seed_demo(): void
{
    header('Content-Type: application/json; charset=utf-8');
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if ($method !== 'POST') {
        echo json_encode(['error' => 'use POST to seed demo data']);
        return;
    }
    $out = ['inserted' => []];
    try {
        // escuelas
        $exists = (int) db_fetch_value('SELECT COUNT(*) FROM escuelas_profesionales');
        if ($exists === 0) {
            db_query('INSERT INTO escuelas_profesionales (nombre, codigo, activo) VALUES (?, ?, 1)', ['Ingeniería de Sistemas', 'IS']);
            db_query('INSERT INTO escuelas_profesionales (nombre, codigo, activo) VALUES (?, ?, 1)', ['Ciencias Empresariales', 'CE']);
            $out['inserted'][] = 'escuelas';
        }
        // empleadores
        $empCount = (int) db_fetch_value('SELECT COUNT(*) FROM empleadores');
        if ($empCount === 0) {
            db_query('INSERT INTO empleadores (razon_social, sector, ciudad, activo) VALUES (?, ?, ?, 1)', ['ACME S.A.', 'Tecnología', 'Moquegua']);
            db_query('INSERT INTO empleadores (razon_social, sector, ciudad, activo) VALUES (?, ?, ?, 1)', ['Comercial del Sur', 'Comercio', 'Moquegua']);
            $out['inserted'][] = 'empleadores';
        }
        // egresados
        $egCount = (int) db_fetch_value('SELECT COUNT(*) FROM egresados');
        if ($egCount === 0) {
            $esc = (int) db_fetch_value('SELECT id FROM escuelas_profesionales LIMIT 1');
            db_query('INSERT INTO egresados (nombre_completo, es_bachiller, es_titulado, anio_egreso, escuela_id, sede, sexo) VALUES (?, ?, ?, ?, ?, ?, ?)', ['Juan Pérez', 1, 1, 2019, $esc, 'Moquegua', 'M']);
            db_query('INSERT INTO egresados (nombre_completo, es_bachiller, es_titulado, anio_egreso, escuela_id, sede, sexo) VALUES (?, ?, ?, ?, ?, ?, ?)', ['María Ruiz', 1, 0, 2020, $esc, 'Moquegua', 'F']);
            $out['inserted'][] = 'egresados';
        }
        // ofertas
        $ofCount = (int) db_fetch_value('SELECT COUNT(*) FROM ofertas_laborales');
        if ($ofCount === 0) {
            $emp = (int) db_fetch_value('SELECT id FROM empleadores LIMIT 1');
            db_query('INSERT INTO ofertas_laborales (empleador_id, titulo, ubicacion, salario_min, salario_max, modalidad, descripcion, vacantes, fecha_pub, activa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)', [$emp, 'Desarrollador Junior', 'Moquegua', 1200, 1800, 'Presencial', 'Vacante para desarrollador PHP', 2, date('Y-m-d')]);
            $out['inserted'][] = 'ofertas';
        }
        echo json_encode($out, JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function api_ofertas(): void
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    header('Content-Type: application/json; charset=utf-8');
    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
        // Require logged employer
        $empId = $_SESSION['empresa_id'] ?? null;
        if (!$empId) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized'], JSON_UNESCAPED_UNICODE);
            return;
        }
        // Insert into DB
        $titulo = $body['titulo'] ?? '';
        $ubicacion = $body['ubicacion'] ?? '';
        $salario_min = $body['salario_min'] ?? null;
        $salario_max = $body['salario_max'] ?? null;
        $modalidad = $body['modalidad'] ?? '';
        $descripcion = $body['descripcion'] ?? '';
        $vacantes = $body['vacantes'] ?? 1;
        $fecha_pub = date('Y-m-d');
        $sql = 'INSERT INTO ofertas_laborales (empleador_id, titulo, ubicacion, salario_min, salario_max, modalidad, descripcion, vacantes, fecha_pub, activa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)';
        db_query($sql, [$empId, $titulo, $ubicacion, $salario_min, $salario_max, $modalidad, $descripcion, $vacantes, $fecha_pub]);
        echo json_encode(['status' => 'ok'], JSON_UNESCAPED_UNICODE);
        return;
    }
    echo json_encode(db_fetch_all(
        'SELECT o.*, emp.razon_social AS empresa, emp.ciudad AS ubicacion
         FROM ofertas_laborales o
         JOIN empleadores emp ON emp.id = o.empleador_id
         WHERE o.activa = 1
         ORDER BY o.fecha_pub DESC'
    ), JSON_UNESCAPED_UNICODE);
}

function egresados_page(): void
{
    $filtros = [
        'busqueda'   => trim($_GET['q'] ?? ''),
        'escuela_id' => trim($_GET['escuela'] ?? ''),
        'sede'       => trim($_GET['sede'] ?? ''),
        'sexo'       => trim($_GET['sexo'] ?? ''),
        'es_bachiller'=> trim($_GET['bachiller'] ?? ''),
        'es_titulado' => trim($_GET['titulado'] ?? ''),
        'anio_egreso' => trim($_GET['anio'] ?? ''),
        'page'        => max(1, (int) ($_GET['page'] ?? 1)),
    ];

    $query = 'SELECT e.*, ep.nombre AS escuela_nombre, ep.codigo AS escuela_codigo, ep.facultad
              FROM egresados e
              JOIN escuelas_profesionales ep ON ep.id = e.escuela_id
              WHERE 1=1';
    $params = [];

    if ($filtros['busqueda'] !== '') {
        $query .= ' AND (e.nombre_completo LIKE ? OR e.dni LIKE ? OR e.codigo_estudiante LIKE ?)';
        $like = '%' . $filtros['busqueda'] . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }
    if ($filtros['escuela_id'] !== '') {
        $query .= ' AND e.escuela_id = ?';
        $params[] = $filtros['escuela_id'];
    }
    if ($filtros['sede'] !== '') {
        $query .= ' AND e.sede = ?';
        $params[] = $filtros['sede'];
    }
    if ($filtros['sexo'] !== '') {
        $query .= ' AND e.sexo = ?';
        $params[] = $filtros['sexo'];
    }
    if ($filtros['es_bachiller'] !== '') {
        $query .= ' AND e.es_bachiller = ?';
        $params[] = $filtros['es_bachiller'];
    }
    if ($filtros['es_titulado'] !== '') {
        $query .= ' AND e.es_titulado = ?';
        $params[] = $filtros['es_titulado'];
    }
    if ($filtros['anio_egreso'] !== '') {
        $query .= ' AND e.anio_egreso = ?';
        $params[] = $filtros['anio_egreso'];
    }

    $total = (int) db_fetch_value('SELECT COUNT(*) FROM (' . $query . ') AS sub', $params);
    $page = max(1, $filtros['page']);
    $perPage = 20;
    $offset = ($page - 1) * $perPage;
    $query .= ' ORDER BY e.nombre_completo ASC LIMIT ? OFFSET ?';
    $params[] = $perPage;
    $params[] = $offset;

    $egresados = db_fetch_all($query, $params);

    render('egresados/index', [
        'title' => 'Egresados – Portal UNAM',
        'filtros' => $filtros,
        'egresados' => $egresados,
        'total' => $total,
        'per_page' => $perPage,
        'page' => $page,
        'escuelas' => db_fetch_all('SELECT * FROM escuelas_profesionales WHERE activo = 1 ORDER BY nombre'),
        'anios' => range(2015, 2025),
    ]);
}

function egresado_detail_page(int $id): void
{
    $egresado = db_fetch_row(
        'SELECT e.*, ep.nombre AS escuela_nombre, ep.codigo AS escuela_codigo, ep.facultad
         FROM egresados e
         JOIN escuelas_profesionales ep ON ep.id = e.escuela_id
         WHERE e.id = ?'
    , [$id]);

    if (!$egresado) {
        not_found_page();
        return;
    }

    render('egresados/detalle', [
        'title' => $egresado['nombre_completo'] . ' – Portal UNAM',
        'egresado' => $egresado,
    ]);
}

function empleadores_page(): void
{
    render('empleadores/index', [
        'title' => 'Empleadores – Portal UNAM',
        'empleadores' => db_fetch_all('SELECT * FROM empleadores WHERE activo = 1 ORDER BY razon_social'),
        'total' => (int) db_fetch_value('SELECT COUNT(*) FROM empleadores WHERE activo = 1'),
    ]);
}

function empleador_detail_page(int $id): void
{
    $empleador = db_fetch_row('SELECT * FROM empleadores WHERE id = ?', [$id]);
    if (!$empleador) {
        not_found_page();
        return;
    }
    $empleador['escuelas'] = db_fetch_all(
        'SELECT ep.nombre, ep.codigo, ee.egresados_contratados, ee.anio
         FROM empleadores_escuelas ee
         JOIN escuelas_profesionales ep ON ep.id = ee.escuela_id
         WHERE ee.empleador_id = ?
         ORDER BY ee.egresados_contratados DESC'
    , [$id]);
    $empleador['ofertas'] = db_fetch_all(
        'SELECT * FROM ofertas_laborales WHERE empleador_id = ? AND activa = 1 ORDER BY fecha_pub DESC'
    , [$id]);

    render('empleadores/detalle', [
        'title' => $empleador['razon_social'] . ' – Portal UNAM',
        'empleador' => $empleador,
    ]);
}

function ofertas_page(): void
{
    $filtros = [
        'busqueda'   => trim($_GET['q'] ?? ''),
        'escuela_id' => trim($_GET['escuela'] ?? ''),
        'modalidad'  => trim($_GET['modalidad'] ?? ''),
        'page'       => max(1, (int) ($_GET['page'] ?? 1)),
    ];

    $query = 'SELECT o.*, emp.razon_social AS empresa, emp.sector, emp.ciudad AS ciudad_empresa,
                     ep.nombre AS escuela_nombre, ep.codigo AS escuela_codigo
              FROM ofertas_laborales o
              JOIN empleadores emp ON emp.id = o.empleador_id
              LEFT JOIN escuelas_profesionales ep ON ep.id = o.escuela_id
              WHERE o.activa = 1';
    $params = [];

    if ($filtros['escuela_id'] !== '') {
        $query .= ' AND o.escuela_id = ?';
        $params[] = $filtros['escuela_id'];
    }
    if ($filtros['modalidad'] !== '') {
        $query .= ' AND o.modalidad = ?';
        $params[] = $filtros['modalidad'];
    }
    if ($filtros['busqueda'] !== '') {
        $query .= ' AND (o.titulo LIKE ? OR emp.razon_social LIKE ? OR o.ubicacion LIKE ?)';
        $like = '%' . $filtros['busqueda'] . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $total = (int) db_fetch_value('SELECT COUNT(*) FROM (' . $query . ') AS sub', $params);
    $page = max(1, $filtros['page']);
    $perPage = 10;
    $offset = ($page - 1) * $perPage;
    $query .= ' ORDER BY o.fecha_pub DESC LIMIT ? OFFSET ?';
    $params[] = $perPage;
    $params[] = $offset;

    $ofertas = db_fetch_all($query, $params);

    render('ofertas/index', [
        'title' => 'Ofertas – Portal UNAM',
        'filtros' => $filtros,
        'ofertas' => $ofertas,
        'total' => $total,
        'per_page' => $perPage,
        'page' => $page,
        'escuelas' => db_fetch_all('SELECT * FROM escuelas_profesionales WHERE activo = 1 ORDER BY nombre'),
    ]);
}

function oferta_detail_page(int $id): void
{
    $oferta = db_fetch_row(
        'SELECT o.*, emp.razon_social AS empresa, emp.sector, emp.ciudad AS ciudad_empresa,
                ep.nombre AS escuela_nombre, ep.codigo AS escuela_codigo
         FROM ofertas_laborales o
         JOIN empleadores emp ON emp.id = o.empleador_id
         LEFT JOIN escuelas_profesionales ep ON ep.id = o.escuela_id
         WHERE o.id = ?'
    , [$id]);

    if (!$oferta) {
        not_found_page();
        return;
    }

    render('ofertas/detalle', [
        'title' => $oferta['titulo'] . ' – Portal UNAM',
        'oferta' => $oferta,
    ]);
}

function not_found_page(): void
{
    http_response_code(404);
    render('errors/not_found', [
        'title' => 'Página no encontrada',
    ]);
}

<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location:index.php");
}
$nombre = $_SESSION['nombre'];
$fotoPerfil = $_SESSION['foto_perfil'];
$rutaFotoPerfil = "fotos/" . $fotoPerfil;
// Verificar si la imagen existe, usar una por defecto si no
$rutaDefault = "recursos/img/default-avatar.png";
$rutaFotoPerfil = (!empty($fotoPerfil) && file_exists("fotos/" . $fotoPerfil))
    ? "fotos/" . $fotoPerfil
    : $rutaDefault;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/inicio/inicio.css">
    <link rel="stylesheet" href="css/inicio/calculadora.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=B612&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Inicio - ControlGastos</title>
</head>
<script src="js/inicio/minicalculadora.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<body>
    <!-- Barra de navegación lateral -->
    <nav class="sidebar">
        <div class="logo">
            <i class="fas fa-wallet"></i>
            <span class="logo-text">ControlGastos</span>
        </div>

        <div class="nav-links">
            <div class="nav-section">
                <div class="nav-title">Home</div>
                <a href="./inicio.php" class="nav-link active">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-title">Finanzas</div>
                <a href="./ingreso.php" class="nav-link">
                    <i class="fas fa-coins"></i>
                    <span>Ingresos</span>
                </a>
                <a href="./gasto.php" class="nav-link">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Gastos</span>
                </a>
                <a href="./balance.php" class="nav-link">
                    <i class="fas fa-chart-line"></i>
                    <span>Balance</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-title">Herramientas</div>
                <a href="calculadora.php" class="nav-link">
                    <i class="fas fa-calculator"></i>
                    <span>Calculadora</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-title">Otros</div>
                <a href="configuracion.php" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="main-content">
        <!-- Header -->
        <div class="header">
            <h1 class="page-title">Panel de Control</h1>
            <div class="user-info">
                <span class="user-name"><?php echo $nombre; ?></span>
                <div class="user-avatar">
                    <img src="<?php echo $rutaFotoPerfil; ?>" alt="Foto de perfil">
                </div>
                <a href="modelo/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <i class="fas fa-hand-wave"></i>
            <div class="welcome-text">¡Bienvenido de nuevo! <span><?php echo $nombre; ?></span></div>
        </div>

        <!-- Financial Summary Cards -->
        <div class="cards-container">
            <div class="card card-income">
                <div class="card-content">
                    <h3>Ingreso Total</h3>
                    <div class="amount">S/<?php include 'modelo/totalIngreso.php'; ?></div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>

            <div class="card card-expense">
                <div class="card-content">
                    <h3>Gasto Total</h3>
                    <div class="amount">S/<?php include 'modelo/total.php'; ?></div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-piggy-bank"></i>
                </div>
            </div>

            <div class="card card-budget">
                <div class="card-content">
                    <h3>Presupuesto Restante</h3>
                    <div class="amount">S/<?php
                                            $ingresos = file_get_contents('modelo/totalIngreso.php');
                                            $gastos = file_get_contents('modelo/total.php');
                                            echo number_format(floatval($ingresos) - floatval($gastos), 2);
                                            ?></div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        <!-- Sección de Calculadora (Integrada) -->
        <div class="calculadora-container">
            <h2>Mini Calculadora</h2>
            <input type="text" id="miniCalcDisplay" disabled>

            <div class="grid">
                <button class="btn-danger" onclick="miniClearCalc()">C</button>
                <button class="btn-warning" onclick="miniDeleteLast()">⌫</button>
                <button class="btn-secondary" onclick="miniAppend('/')">÷</button>
                <button class="btn-secondary" onclick="miniAppend('*')">×</button>

                <button class="btn-light" onclick="miniAppend('7')">7</button>
                <button class="btn-light" onclick="miniAppend('8')">8</button>
                <button class="btn-light" onclick="miniAppend('9')">9</button>
                <button class="btn-secondary" onclick="miniAppend('-')">−</button>

                <button class="btn-light" onclick="miniAppend('4')">4</button>
                <button class="btn-light" onclick="miniAppend('5')">5</button>
                <button class="btn-light" onclick="miniAppend('6')">6</button>
                <button class="btn-secondary" onclick="miniAppend('+')">+</button>

                <button class="btn-light" onclick="miniAppend('1')">1</button>
                <button class="btn-light" onclick="miniAppend('2')">2</button>
                <button class="btn-light" onclick="miniAppend('3')">3</button>
                <button class="btn-primary" style="grid-row: span 2;" onclick="miniCalculate()">=</button>

                <button class="btn-light" style="grid-column: span 2;" onclick="miniAppend('0')">0</button>
                <button class="btn-light" onclick="miniAppend('.')">.</button>
            </div>
        </div>

        <!-- Gráfico Circular -->
        <div class="chart-section">
            <div class="section-header">
                <h2>Resumen Financiero</h2>
            </div>
            <div class="chart-container">
                <canvas id="financialChart"></canvas>
            </div>
        </div>
    </main>

    <script src="js/inicio.js"></script>
</body>

</html>
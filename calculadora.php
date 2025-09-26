<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no hay usuario logueado redirige
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

// Valores seguros por defecto (evita "undefined variable")
$nombre = isset($_SESSION['nombre']) && $_SESSION['nombre'] !== '' ? $_SESSION['nombre'] : 'Usuario';
$fotoPerfil = isset($_SESSION['foto_perfil']) && $_SESSION['foto_perfil'] !== '' ? $_SESSION['foto_perfil'] : null;

$rutaDefault = 'recursos/img/default-avatar.png';
$rutaFotoPerfil = ($fotoPerfil && file_exists(__DIR__ . '/fotos/' . $fotoPerfil))
    ? 'fotos/' . $fotoPerfil
    : $rutaDefault;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/reset.css">
    <!-- font icons -->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-straight/css/uicons-bold-straight.css'>
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=B612:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <!-- style -->
    <link rel="stylesheet" href="css/calculadora/calculadora.css">
    <title>Calculadora Financiera</title>
</head>

<body>
    <div class="layout">
        <!-- Barra de navegación lateral -->
        <aside class="sidebar">
            <nav class="navcontainer">
                <div class="logo">
                    <figure class="logo__icon">
                        <i class="fi fi-tr-money logo__img"></i>
                    </figure>
                    <p class="logo__text">Control de Datos</p>
                </div>
                <span class="navcontainer__line"></span>
                <ul class="list">
                    <span class="list__title">Home</span>
                    <li class="list__item">
                        <a href="./inicio.php" class="list__link">
                            <i class="fi fi-sr-dashboard list__img"></i>
                            <p>Inicio</p>
                        </a>
                    </li>
                    <span class="list__title">Finanzas</span>
                    <li class="list__item">
                        <a href="./ingreso.php" class="list__link">
                            <i class="fi fi-sr-coins list__img"></i>
                            <p>ingresos</p>
                        </a>
                    </li>
                    <li class="list__item">
                        <a href="./gasto.php" class="list__link">
                            <i class="fi fi-sr-hand-holding-usd list__img"></i>
                            <p>Gastos</p>
                        </a>
                    </li>
                    <li class="list__item">
                        <a href="./balance.php" class="list__link">
                            <i class="fi fi-rr-chart-histogram list__img"></i>
                            <p>Balance</p>
                        </a>
                    </li>
                    <span class="list__title">Herramientas</span>
                    <li class="list__item">
                        <a href="./calculadora.php" class="list__link">
                            <i class="fi fi-sr-calculator list__img"></i>
                            <p>Calculadora</p>
                        </a>
                    </li>
                    <span class="list__title">Otros</span>
                    <li class="list__item">
                        <a href="configuracion.php" class="list__link">
                            <i class="fi fi-br-gears list__img"></i>
                            <p>Configuracion</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <div class="content">
            <!-- Barra superior -->
            <header class="head-container">
                <div class="user">
                    <p class="user__name"><?php echo $nombre; ?></p>
                    <div class="user__img">
                        <img src="<?php echo $rutaFotoPerfil; ?>" alt="Foto de perfil" class="image">
                    </div>
                    <a href="modelo/logout.php" class="user__link" title="Cerrar sesión">
                        <i class="fi fi-rr-sign-out-alt exit"></i>
                    </a>
                </div>
            </header>

            <!-- Calculadora -->
            <main class="calculator-container">
                <h2 class="page-title">Calculadora Financiera</h2>

                <!-- Tabs Navigation -->
                <div class="tabs-nav">
                    <button class="tab-button active" onclick="switchTab('basica')">
                        <i class="fi fi-sr-calculator"></i>
                        Básica
                    </button>
                    <button class="tab-button" onclick="switchTab('prestamo')">
                        <i class="fi fi-sr-money"></i>
                        Préstamo
                    </button>
                    <button class="tab-button" onclick="switchTab('inversion')">
                        <i class="fi fi-sr-chart-line-up"></i>
                        Inversión
                    </button>
                </div>

                <!-- Calculadora Básica -->
                <div class="tab-content active" id="basica">
                    <div class="calculator-basic">
                        <div class="calculator-display">
                            <input type="text" id="display" readonly value="0">
                        </div>
                        <div class="calculator-buttons">
                            <button class="btn btn-clear" onclick="clearDisplay()">C</button>
                            <button class="btn btn-clear" onclick="clearEntry()">CE</button>
                            <button class="btn btn-operation" onclick="deleteLast()">⌫</button>
                            <button class="btn btn-operation" onclick="appendOperation('/')">/</button>

                            <button class="btn btn-number" onclick="appendNumber('7')">7</button>
                            <button class="btn btn-number" onclick="appendNumber('8')">8</button>
                            <button class="btn btn-number" onclick="appendNumber('9')">9</button>
                            <button class="btn btn-operation" onclick="appendOperation('*')">×</button>

                            <button class="btn btn-number" onclick="appendNumber('4')">4</button>
                            <button class="btn btn-number" onclick="appendNumber('5')">5</button>
                            <button class="btn btn-number" onclick="appendNumber('6')">6</button>
                            <button class="btn btn-operation" onclick="appendOperation('-')">-</button>

                            <button class="btn btn-number" onclick="appendNumber('1')">1</button>
                            <button class="btn btn-number" onclick="appendNumber('2')">2</button>
                            <button class="btn btn-number" onclick="appendNumber('3')">3</button>
                            <button class="btn btn-operation" onclick="appendOperation('+')">+</button>

                            <button class="btn btn-number btn-zero" onclick="appendNumber('0')">0</button>
                            <button class="btn btn-number" onclick="appendNumber('.')">.</button>
                            <button class="btn btn-equals" onclick="calculate()">=</button>
                        </div>
                    </div>
                </div>

                <!-- Calculadora de Préstamo -->
                <div class="tab-content" id="prestamo">
                    <div class="loan-calculator">
                        <h3>Calculadora de Préstamo</h3>
                        <div class="form-group">
                            <label for="loanAmount">Monto del Préstamo (S/)</label>
                            <input type="number" id="loanAmount" placeholder="Ej: 100000">
                        </div>
                        <div class="form-group">
                            <label for="loanRate">Tasa de Interés Anual (%)</label>
                            <input type="number" id="loanRate" step="0.01" placeholder="Ej: 12.5">
                        </div>
                        <div class="form-group">
                            <label for="loanTerm">Plazo (años)</label>
                            <input type="number" id="loanTerm" placeholder="Ej: 5">
                        </div>
                        <button class="btn-calculate" onclick="calculateLoan()">Calcular Préstamo</button>
                        <div class="result-container" id="loanResult"></div>
                    </div>
                </div>

                <!-- Calculadora de Inversión -->
                <div class="tab-content" id="inversion">
                    <div class="investment-calculator">
                        <h3>Calculadora de Inversión</h3>
                        <div class="form-group">
                            <label for="initialAmount">Monto Inicial (S/)</label>
                            <input type="number" id="initialAmount" placeholder="Ej: 50000">
                        </div>
                        <div class="form-group">
                            <label for="monthlyContribution">Aporte Mensual (S/)</label>
                            <input type="number" id="monthlyContribution" placeholder="Ej: 1000">
                        </div>
                        <div class="form-group">
                            <label for="interestRate">Tasa de Interés Anual (%)</label>
                            <input type="number" id="interestRate" step="0.01" placeholder="Ej: 8.5">
                        </div>
                        <div class="form-group">
                            <label for="investmentTerm">Plazo (años)</label>
                            <input type="number" id="investmentTerm" placeholder="Ej: 10">
                        </div>
                        <button class="btn-calculate" onclick="calculateInvestment()">Calcular Inversión</button>
                        <div class="result-container" id="investmentResult"></div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="js/calculadora/calculadora.js"></script>
</body>
</html>

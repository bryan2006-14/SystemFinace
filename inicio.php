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
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Inicio - ControlGastos</title>
</head>

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
                    <div class="card-trend">
                        <span class="trend-up">
                            <i class="fas fa-arrow-up"></i>
                            +8.2%
                        </span>
                        <span class="trend-text">vs mes anterior</span>
                    </div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>

            <div class="card card-expense">
                <div class="card-content">
                    <h3>Gasto Total</h3>
                    <div class="amount">S/<?php include 'modelo/total.php'; ?></div>
                    <div class="card-trend">
                        <span class="trend-down">
                            <i class="fas fa-arrow-down"></i>
                            -3.5%
                        </span>
                        <span class="trend-text">vs mes anterior</span>
                    </div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-piggy-bank"></i>
                </div>
            </div>

            <div class="card card-budget">
                <div class="card-content">
                    <h3>Balance Actual</h3>
                    <div class="amount">S/<?php
                                            $ingresos = file_get_contents('modelo/totalIngreso.php');
                                            $gastos = file_get_contents('modelo/total.php');
                                            echo number_format(floatval($ingresos) - floatval($gastos), 2);
                                            ?></div>
                    <div class="card-trend">
                        <span class="trend-up">
                            <i class="fas fa-arrow-up"></i>
                            +12.7%
                        </span>
                        <span class="trend-text">vs mes anterior</span>
                    </div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>

            <div class="card card-savings">
                <div class="card-content">
                    <h3>Ahorros del Mes</h3>
                    <div class="amount">S/1,245.50</div>
                    <div class="card-trend">
                        <span class="trend-up">
                            <i class="fas fa-arrow-up"></i>
                            +15.3%
                        </span>
                        <span class="trend-text">vs mes anterior</span>
                    </div>
                </div>
                <div class="card-icon">
                    <i class="fas fa-piggy-bank"></i>
                </div>
            </div>
        </div>

        <!-- Gráfico principal -->
        <div class="chart-section">
            <div class="section-header">
                <h2>Resumen Financiero</h2>
                <div class="chart-controls">
                    <button class="chart-btn active" onclick="changeChartType('doughnut')">
                        <i class="fas fa-chart-pie"></i>
                    </button>
                    <button class="chart-btn" onclick="changeChartType('bar')">
                        <i class="fas fa-chart-bar"></i>
                    </button>
                    <button class="chart-btn" onclick="changeChartType('line')">
                        <i class="fas fa-chart-line"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="financialChart"></canvas>
                <!-- El gráfico circular se creará dinámicamente aquí -->
            </div>
        </div>

        <!-- Análisis de gastos -->
        <div class="expenses-section">
            <div class="section-header">
                <h3>Gastos por Categoría</h3>
            </div>
            <div class="expenses-grid">
                <div class="expense-item">
                    <div class="expense-icon food">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="expense-info">
                        <span class="expense-category">Alimentación</span>
                        <span class="expense-amount">S/425.80</span>
                        <div class="expense-bar">
                            <div class="expense-fill" style="width: 35%"></div>
                        </div>
                    </div>
                    <div class="expense-percentage">35%</div>
                </div>
                
                <div class="expense-item">
                    <div class="expense-icon transport">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="expense-info">
                        <span class="expense-category">Transporte</span>
                        <span class="expense-amount">S/180.50</span>
                        <div class="expense-bar">
                            <div class="expense-fill" style="width: 22%"></div>
                        </div>
                    </div>
                    <div class="expense-percentage">22%</div>
                </div>

                <div class="expense-item">
                    <div class="expense-icon entertainment">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="expense-info">
                        <span class="expense-category">Entretenimiento</span>
                        <span class="expense-amount">S/120.00</span>
                        <div class="expense-bar">
                            <div class="expense-fill" style="width: 15%"></div>
                        </div>
                    </div>
                    <div class="expense-percentage">15%</div>
                </div>

                <div class="expense-item">
                    <div class="expense-icon health">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="expense-info">
                        <span class="expense-category">Salud</span>
                        <span class="expense-amount">S/95.30</span>
                        <div class="expense-bar">
                            <div class="expense-fill" style="width: 12%"></div>
                        </div>
                    </div>
                    <div class="expense-percentage">12%</div>
                </div>
            </div>
        </div>

        <!-- Mini Calculadora (si existe en el diseño) -->
        <div class="mini-calculator" style="display: none;">
            <input type="text" id="miniCalcDisplay" readonly>
            <div class="calc-buttons">
                <button onclick="appendMiniValue('7')">7</button>
                <button onclick="appendMiniValue('8')">8</button>
                <button onclick="appendMiniValue('9')">9</button>
                <button onclick="appendMiniValue('+')">+</button>
                <button onclick="appendMiniValue('4')">4</button>
                <button onclick="appendMiniValue('5')">5</button>
                <button onclick="appendMiniValue('6')">6</button>
                <button onclick="appendMiniValue('-')">-</button>
                <button onclick="appendMiniValue('1')">1</button>
                <button onclick="appendMiniValue('2')">2</button>
                <button onclick="appendMiniValue('3')">3</button>
                <button onclick="appendMiniValue('*')">×</button>
                <button onclick="clearMiniDisplay()">C</button>
                <button onclick="appendMiniValue('0')">0</button>
                <button onclick="backspaceMini()">⌫</button>
                <button onclick="appendMiniValue('/')">÷</button>
                <button onclick="appendMiniValue('.')">.</button>
                <button onclick="calculateMiniResult()" style="grid-column: span 2;">=</button>
            </div>
        </div>
    </main>

    <!-- ChatBot Mejorado -->
    <div id="chatbot-container" class="chatbot-container">
        <div class="chatbot-header">
            <div class="bot-info">
                <div class="bot-avatar">
                    <i class="fas fa-robot"></i>
                    <div class="avatar-status"></div>
                </div>
                <div class="bot-details">
                    <span class="bot-name">Asistente Financiero</span>
                    <span class="bot-status">En línea</span>
                </div>
            </div>
            <div class="chatbot-controls">
                <button id="expand-chat" class="control-btn" title="Expandir">
                    <i class="fas fa-expand"></i>
                </button>
                <button id="minimize-chat" class="control-btn" title="Minimizar">
                    <i class="fas fa-minus"></i>
                </button>
                <button id="close-chat" class="control-btn" title="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="chatbot-body">
            <div class="chat-suggestions">
                <div class="suggestion" onclick="sendQuickMessage('¿Cómo puedo ahorrar más dinero?')">
                    <i class="fas fa-piggy-bank"></i>
                    <span>Consejos de ahorro</span>
                </div>
                <div class="suggestion" onclick="sendQuickMessage('Analiza mis gastos')">
                    <i class="fas fa-chart-bar"></i>
                    <span>Análisis de gastos</span>
                </div>
                <div class="suggestion" onclick="sendQuickMessage('¿Cómo hacer un presupuesto?')">
                    <i class="fas fa-calculator"></i>
                    <span>Crear presupuesto</span>
                </div>
            </div>

            <div id="chat-messages" class="chat-messages"></div>

            <div class="chat-input-area">
                <div class="input-container">
                    <input type="text" id="chat-input" placeholder="Escribe tu pregunta..." maxlength="500">
                    <button id="stop-btn" class="control-button stop-button" title="Detener respuesta" style="display: none;">
                        <i class="fas fa-stop"></i>
                    </button>
                    <button id="send-btn" class="send-button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat FAB -->
    <div id="chat-fab" class="chat-fab">
        <i class="fas fa-comments"></i>
        <span class="notification">1</span>
    </div>

    <!-- Incluir archivos separados -->
    <link rel="stylesheet" href="css/inicio/inicio-styles.css">
    <script src="js/inicio/inicio-script.js"></script>
</body>

</html>
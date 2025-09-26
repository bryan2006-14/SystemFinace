// js/inicio/inicio-script.js

// Variables globales
let chatVisible = false;
let isTyping = false;
let isExpanded = false;
let typingInterval = null;
let currentChart = null;
let myChart = null;

// Inicializar cuando carga la página
document.addEventListener('DOMContentLoaded', function() {
    initializeChart();
    initializeCircularChart();
    initializeChat();
    initializeMiniCalculator();
    showWelcomeMessage();
});

// Inicializar gráfico principal (doughnut/bar/line)
function initializeChart() {
    const ctx = document.getElementById('financialChart');
    if (ctx) {
        currentChart = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Ingresos', 'Gastos', 'Ahorros'],
                datasets: [{
                    data: [3500, 1200, 800],
                    backgroundColor: ['#10b981', '#ef4444', '#3b82f6'],
                    borderWidth: 0,
                    cutout: '65%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
}

// Inicializar gráfico circular
function initializeCircularChart() {
    // Crear canvas para el gráfico circular si no existe
    const chartContainer = document.querySelector('.chart-container');
    if (chartContainer && !document.getElementById('myChart')) {
        const canvas = document.createElement('canvas');
        canvas.id = 'myChart';
        canvas.style.maxHeight = '400px';
        chartContainer.appendChild(canvas);
    }

    // Cargar datos del gráfico circular
    fetch('modelo/circle.php')
        .then(response => response.json())
        .then(data => {
            const dataChart = {
                labels: ["Ingreso", "Gasto", "Presupuesto"],
                datasets: [{
                    data: [data.totalIngreso, data.totalGasto, data.presupuesto],
                    backgroundColor: ["#1DD667", "#F62828", "#4365DF"],
                    hoverBackgroundColor: ["#1DD667", "#F62828", "#4365DF"]
                }]
            };

            const optionsChart = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const dataset = context.dataset;
                                const total = dataset.data.reduce((acc, val) => acc + val, 0);
                                const currentValue = dataset.data[context.dataIndex];
                                const percentage = ((currentValue / total) * 100).toFixed(2);
                                return `${context.label}: ${percentage}%`;
                            }
                        }
                    }
                }
            };

            const ctx = document.getElementById('myChart');
            if (ctx) {
                // Destruir gráfico anterior si existe
                if (myChart) {
                    myChart.destroy();
                }
                
                myChart = new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: dataChart,
                    options: optionsChart
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar datos del gráfico circular:', error);
            // Datos de ejemplo en caso de error
            showFallbackChart();
        });
}

function showFallbackChart() {
    const ctx = document.getElementById('myChart');
    if (ctx) {
        myChart = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ["Ingreso", "Gasto", "Presupuesto"],
                datasets: [{
                    data: [5000, 3000, 2000],
                    backgroundColor: ["#1DD667", "#F62828", "#4365DF"]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}

// Inicializar mini calculadora
function initializeMiniCalculator() {
    const display = document.getElementById("miniCalcDisplay");
    if (display) {
        window.appendMiniValue = function(value) {
            display.value += value;
        };

        window.clearMiniDisplay = function() {
            display.value = "";
        };

        window.backspaceMini = function() {
            display.value = display.value.slice(0, -1);
        };

        window.calculateMiniResult = function() {
            try {
                const expression = display.value.replace(/,/g, '.');
                display.value = eval(expression) || "Error";
            } catch (e) {
                display.value = "Error";
            }
        };
    }
}

// Inicializar chat
function initializeChat() {
    const chatFab = document.getElementById('chat-fab');
    const minimizeBtn = document.getElementById('minimize-chat');
    const expandBtn = document.getElementById('expand-chat');
    const closeBtn = document.getElementById('close-chat');
    const sendBtn = document.getElementById('send-btn');
    const stopBtn = document.getElementById('stop-btn');
    const chatInput = document.getElementById('chat-input');

    if (chatFab) chatFab.addEventListener('click', toggleChat);
    if (minimizeBtn) minimizeBtn.addEventListener('click', minimizeChat);
    if (expandBtn) expandBtn.addEventListener('click', expandChat);
    if (closeBtn) closeBtn.addEventListener('click', closeChat);
    if (sendBtn) sendBtn.addEventListener('click', sendMessage);
    if (stopBtn) stopBtn.addEventListener('click', stopTyping);
    if (chatInput) {
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !isTyping) {
                sendMessage();
            }
        });
    }
}

function showWelcomeMessage() {
    setTimeout(() => {
        addBotMessage("¡Hola! 👋 Soy tu asistente financiero. Puedo ayudarte con análisis de gastos, consejos de ahorro y presupuestos. ¿En qué puedo ayudarte hoy?");
    }, 1000);
}

function toggleChat() {
    const container = document.getElementById('chatbot-container');
    const fab = document.getElementById('chat-fab');
    
    if (!container || !fab) return;
    
    chatVisible = !chatVisible;
    
    if (chatVisible) {
        container.style.display = 'flex';
        fab.style.display = 'none';
        setTimeout(() => {
            container.style.opacity = '1';
            container.style.transform = 'translateY(0)';
            document.getElementById('chat-input').focus();
        }, 10);
    } else {
        container.style.opacity = '0';
        container.style.transform = 'translateY(20px)';
        setTimeout(() => {
            container.style.display = 'none';
            fab.style.display = 'flex';
        }, 300);
    }
}

function minimizeChat() {
    const container = document.getElementById('chatbot-container');
    const fab = document.getElementById('chat-fab');
    
    if (!container || !fab) return;
    
    container.style.opacity = '0';
    container.style.transform = 'translateY(20px)';
    setTimeout(() => {
        container.style.display = 'none';
        fab.style.display = 'flex';
    }, 300);
    
    chatVisible = false;
}

function expandChat() {
    const container = document.getElementById('chatbot-container');
    if (!container) return;
    
    isExpanded = !isExpanded;
    
    if (isExpanded) {
        container.style.width = '80%';
        container.style.height = '80%';
        container.style.maxWidth = '1000px';
        container.style.maxHeight = '700px';
        document.getElementById('expand-chat').innerHTML = '<i class="fas fa-compress"></i>';
        document.getElementById('expand-chat').title = 'Contraer';
    } else {
        container.style.width = '350px';
        container.style.height = '500px';
        container.style.maxWidth = 'none';
        container.style.maxHeight = 'none';
        document.getElementById('expand-chat').innerHTML = '<i class="fas fa-expand"></i>';
        document.getElementById('expand-chat').title = 'Expandir';
    }
}

function closeChat() {
    minimizeChat();
}

function sendQuickMessage(message) {
    const chatInput = document.getElementById('chat-input');
    if (chatInput) {
        chatInput.value = message;
        sendMessage();
    }
}

async function sendMessage() {
    if (isTyping) return;
    
    const input = document.getElementById('chat-input');
    const message = input?.value.trim();
    
    if (!message || !input) return;
    
    // Ocultar sugerencias
    const suggestions = document.querySelector('.chat-suggestions');
    if (suggestions) suggestions.style.display = 'none';
    
    addUserMessage(message);
    input.value = '';
    
    // Deshabilitar entrada mientras el bot responde
    input.disabled = true;
    isTyping = true;
    
    // Mostrar botón de detener
    const stopBtn = document.getElementById('stop-btn');
    if (stopBtn) stopBtn.style.display = 'block';
    
    showTypingIndicator();
    
    try {
        // Usar la API real de Gemini en lugar de respuestas simuladas
        const response = await fetch('chatbot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message: message })
        });
        
        const data = await response.json();
        hideTypingIndicator();
        
        if (data.reply) {
            simulateTyping(data.reply);
        } else {
            simulateTyping("Lo siento, no pude procesar tu pregunta. Por favor intenta de nuevo.");
        }
        
    } catch (error) {
        hideTypingIndicator();
        console.error('Error al conectar con el chatbot:', error);
        // Fallback a respuestas locales si la API falla
        const fallbackResponse = getFallbackResponse(message);
        simulateTyping(fallbackResponse);
    }
}

function stopTyping() {
    if (typingInterval) {
        clearInterval(typingInterval);
        typingInterval = null;
    }
    
    hideTypingIndicator();
    
    const input = document.getElementById('chat-input');
    const stopBtn = document.getElementById('stop-btn');
    
    if (input) input.disabled = false;
    isTyping = false;
    
    if (stopBtn) stopBtn.style.display = 'none';
    
    const messagesContainer = document.getElementById('chat-messages');
    if (messagesContainer) {
        const interruptedElement = document.createElement('div');
        interruptedElement.className = 'message bot-message interrupted';
        interruptedElement.innerHTML = `
            <div class="message-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">
                <p>Respuesta interrumpida. ¿En qué más puedo ayudarte?</p>
                <span class="message-time">${getCurrentTime()}</span>
            </div>
        `;
        messagesContainer.appendChild(interruptedElement);
        scrollToBottom();
    }
}

function simulateTyping(message) {
    const messagesContainer = document.getElementById('chat-messages');
    if (!messagesContainer) return;
    
    const messageElement = document.createElement('div');
    messageElement.className = 'message bot-message';
    messageElement.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="message-content">
            <p></p>
            <span class="message-time">${getCurrentTime()}</span>
        </div>
    `;
    messagesContainer.appendChild(messageElement);
    
    const textElement = messageElement.querySelector('p');
    let index = 0;
    const typingSpeed = 30;
    
    if (typingInterval) {
        clearInterval(typingInterval);
    }
    
    typingInterval = setInterval(() => {
        if (index < message.length) {
            textElement.textContent += message[index];
            index++;
            scrollToBottom();
        } else {
            clearInterval(typingInterval);
            typingInterval = null;
            
            const input = document.getElementById('chat-input');
            const stopBtn = document.getElementById('stop-btn');
            
            if (input) input.disabled = false;
            isTyping = false;
            
            if (stopBtn) stopBtn.style.display = 'none';
        }
    }, typingSpeed);
}

function addUserMessage(message) {
    const messagesContainer = document.getElementById('chat-messages');
    const userAvatar = document.querySelector('.user-avatar img');
    
    if (!messagesContainer) return;
    
    const messageElement = document.createElement('div');
    messageElement.className = 'message user-message';
    messageElement.innerHTML = `
        <div class="message-content">
            <p>${message}</p>
            <span class="message-time">${getCurrentTime()}</span>
        </div>
        <div class="message-avatar">
            <img src="${userAvatar?.src || 'recursos/img/default-avatar.png'}" alt="Usuario">
        </div>
    `;
    messagesContainer.appendChild(messageElement);
    scrollToBottom();
}

function addBotMessage(message) {
    const messagesContainer = document.getElementById('chat-messages');
    if (!messagesContainer) return;
    
    const messageElement = document.createElement('div');
    messageElement.className = 'message bot-message';
    messageElement.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="message-content">
            <p>${message}</p>
            <span class="message-time">${getCurrentTime()}</span>
        </div>
    `;
    messagesContainer.appendChild(messageElement);
    scrollToBottom();
}

function showTypingIndicator() {
    const messagesContainer = document.getElementById('chat-messages');
    if (!messagesContainer) return;
    
    const typingElement = document.createElement('div');
    typingElement.className = 'typing-indicator';
    typingElement.id = 'typing-indicator';
    typingElement.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="typing-content">
            <div class="typing-dots">
                <span></span><span></span><span></span>
            </div>
            <span class="typing-text">Escribiendo...</span>
        </div>
    `;
    messagesContainer.appendChild(typingElement);
    scrollToBottom();
}

function hideTypingIndicator() {
    const indicator = document.getElementById('typing-indicator');
    if (indicator) {
        indicator.remove();
    }
}

function getFallbackResponse(message) {
    const lowerMessage = message.toLowerCase();
    
    if (lowerMessage.includes('ahorro') || lowerMessage.includes('ahorrar')) {
        return "Te recomiendo seguir la regla 50/30/20: 50% para gastos necesarios, 30% para gastos personales y 20% para ahorros. Automatiza tus ahorros para que sea más fácil y consistente.";
    } else if (lowerMessage.includes('gasto') || lowerMessage.includes('analiza')) {
        return "Tus principales categorías de gasto son: Alimentación (35%), Transporte (22%), Entretenimiento (15%) y Salud (12%). Te sugiero revisar tus gastos de entretenimiento.";
    } else if (lowerMessage.includes('presupuesto')) {
        return "Para crear un presupuesto: 1) Registra ingresos, 2) Clasifica gastos, 3) Establece límites realistas, 4) Haz seguimiento regular. Revisa semanalmente.";
    } else if (lowerMessage.includes('balance') || lowerMessage.includes('resumen')) {
        return "Tu balance se calcula: Ingresos - Gastos = Balance. Un balance positivo indica buena salud financiera.";
    } else if (lowerMessage.includes('inversión') || lowerMessage.includes('invertir')) {
        return "Para invertir: 1) Fondo de emergencia, 2) Objetivos claros, 3) Conocer tu riesgo, 4) Diversificar. Empieza con opciones conservadoras.";
    } else {
        return "Puedo ayudarte con análisis de gastos, consejos de ahorro, presupuestos y estrategias financieras. ¿En qué específicamente necesitas ayuda?";
    }
}

function getCurrentTime() {
    return new Date().toLocaleTimeString('es-ES', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });
}

function scrollToBottom() {
    const messagesContainer = document.getElementById('chat-messages');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

// Función para cambiar tipo de gráfico principal
function changeChartType(type) {
    if (currentChart) {
        currentChart.destroy();
        const ctx = document.getElementById('financialChart');
        
        if (ctx) {
            // Actualizar botones activos
            document.querySelectorAll('.chart-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            currentChart = new Chart(ctx.getContext('2d'), {
                type: type,
                data: {
                    labels: ['Ingresos', 'Gastos', 'Ahorros'],
                    datasets: [{
                        data: [3500, 1200, 800],
                        backgroundColor: ['#10b981', '#ef4444', '#3b82f6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: type === 'doughnut',
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
}

// Exportar funciones para uso global
window.changeChartType = changeChartType;
window.sendQuickMessage = sendQuickMessage;
window.appendMiniValue = window.appendMiniValue || function() {};
window.clearMiniDisplay = window.clearMiniDisplay || function() {};
window.backspaceMini = window.backspaceMini || function() {};
window.calculateMiniResult = window.calculateMiniResult || function() {};

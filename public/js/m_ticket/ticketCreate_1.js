// ticketCreate.js - основной файл

// Импорт модулей (если используете модули ES6)
import { NormsManager } from './modules/normsManager.js';
import { Calculator } from './modules/calculator.js';
import { Validator } from './modules/validator.js';
import { Navigation } from './modules/navigation.js';

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Инициализируем нормы
    NormsManager.init();

    // Загружаем нормы при загрузке
    NormsManager.loadBySelect();

    // Добавляем обработчики событий
    setupEventListeners();

    // Выполняем начальные расчеты
    Calculator.updateTicketKilometres();
    Calculator.updateCargoNo();
    Calculator.updateCompletedWork();
});

function setupEventListeners() {
    // Нормы
    const normSelect = document.getElementById('m_norm');
    if (normSelect) {
        normSelect.addEventListener('change', () => NormsManager.loadBySelect());
    }

    // Километры
    const kilometreFields = ['kilometres_city', 'kilometres_trail', 'kilometres_ground', 'kilometres_linear'];
    kilometreFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) field.addEventListener('input', Calculator.updateTicketKilometres);
    });

    // Cargo
    const cargoFields = ['cargo', 'kilometres_speedometer', 'pump'];
    cargoFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) field.addEventListener('input', Calculator.updateCargoNo);
    });

    // Выполненная работа
    const completedWork = document.getElementById('completed_work');
    if (completedWork) {
        completedWork.addEventListener('input', Calculator.updateCompletedWork);
    }

    // Грузы
    for (let i = 1; i <= 5; i++) {
        const cargoField = document.getElementById(`cargo_${i}`);
        if (cargoField) cargoField.addEventListener('input', Calculator.updateCargoNo);
    }
}

// Экспортируем функции для использования в HTML (onclick и т.д.)
window.openUrl = Navigation.openUrl.bind(Navigation);
window.openUrlEditTicket = Navigation.openUrlEditTicket.bind(Navigation);
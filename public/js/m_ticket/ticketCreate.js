// Функция для проверки совпадения полей
function checkKilometresMatch() {
    const ticketInput = document.getElementById('kilometres_ticket');
    const speedometerInput = document.getElementById('kilometres_speedometer');

    const ticket = parseFloat(ticketInput.value) || 0;
    const speedometer = parseFloat(speedometerInput.value) || 0;

    if (ticket !== speedometer) {
        // Добавляем классы для подсветки
        ticketInput.classList.add('is-warning', 'border-warning');
        speedometerInput.classList.add('is-warning', 'border-warning');

        // Создаем или обновляем подсказку
        showWarningMessage('Значения "км по путевке" и "Всего" должны совпадать!','kilometres_warning');
    } else {
        // Убираем подсветку
        ticketInput.classList.remove('is-warning', 'border-warning');
        speedometerInput.classList.remove('is-warning', 'border-warning');

        // Убираем подсказку
        hideWarningMessage('kilometres_warning');
    }
}
function checkTicketWritesOffMatch() {
    const normalInput = document.getElementById('normal_fuel');
    const spentInput = document.getElementById('spent_fuel');

    const normal = parseFloat(normalInput.value) || 0;
    const spent = parseFloat(spentInput.value) || 0;
    const adjusted = Math.abs(normal-spent)
    if (normal !== spent) {
        // Добавляем классы для подсветки
        normalInput.classList.add('is-warning', 'border-warning');
        spentInput.classList.add('is-warning', 'border-warning');

        // Создаем или обновляем подсказку
        showWarningMessage(`Значения "Израсходовано горючего" и "Положено по норме горючего" должны совпадать! Коррект.: ${adjusted}`,'ticket_writes');
    } else {
        // Убираем подсветку
        normalInput.classList.remove('is-warning', 'border-warning');
        spentInput.classList.remove('is-warning', 'border-warning');

        // Убираем подсказку
        hideWarningMessage('ticket_writes');
    }
}

/**
 * Функция для расчета нормы по формуле:
 * ОКРУГЛ(километры * норма / 100, 0)
 */
function calculateNormalValue(kilometres, normal) {
    const km = parseFloat(kilometres) || 0;
    const norm = parseFloat(normal) || 0;
    const result = Math.round((km * norm) / 100);
    return result;
}
function updateCalcNormalsCargo() {

    const cargoInput = document.getElementById('cargo');
    const [cargo1, cargo2, cargo3, cargo4, cargo5] = [1, 2, 3, 4, 5].map(i =>
        parseFloat(document.getElementById(`cargo_${i}`)?.value) || 0
    );

    const completedWorkInput = document.getElementById('completed_work');
    const [weight1, weight2, weight3, weight4, weight5] = [1, 2, 3, 4, 5].map(i =>
        parseFloat(document.getElementById(`weight_${i}`)?.value) || 0
    );

    // Получаем значения норм из span элементов
    const normalCargo = document.getElementById('normal_cargo')?.textContent || 0;

    const sumCargoWeight = (cargo1 * weight1) +
        (cargo2 * weight2) +
        (cargo3 * weight3) +
        (cargo4 * weight4) +
        (cargo5 * weight5);

    // Расчет по формуле: ОКРУГЛ((сумма) / 100 * норма, 0)
    const calcNormalCargo = Math.round((sumCargoWeight / 100) * normalCargo);

    // Обновляем поля calc_normal_*
    const calcNormalCargoField = document.getElementById('calc_normal_cargo');

    if (calcNormalCargoField) calcNormalCargoField.value = calcNormalCargo;
    updateFuel()
}
function updateCalcNormalsPump() {

    const pumpInput = document.getElementById('pump');
    const pump = parseFloat(pumpInput?.value) || 0;
    // Получаем значения норм из span элементов
    const normalPump = document.getElementById('normal_pump')?.textContent || 0;

    const calcNormalPump = Math.round(pump * normalPump);

    // Обновляем поля calc_normal_*
    const calcNormalPumpField = document.getElementById('calc_normal_pump');

    if (calcNormalPumpField) calcNormalPumpField.value = calcNormalPump;
    updateFuel()
}

/**
 * Обновление всех расчетных полей норм
 */
function updateCalcNormals() {
    // Получаем значения километров из полей путевки
    const kilometresCity = document.getElementById('kilometres_city')?.value || 0;
    const kilometresTrail = document.getElementById('kilometres_trail')?.value || 0;
    const kilometresGround = document.getElementById('kilometres_ground')?.value || 0;
    const kilometresLinear = document.getElementById('kilometres_linear')?.value || 0;

    // Получаем значения норм из span элементов
    const normalCity = document.getElementById('normal_city')?.textContent || 0;
    const normalTrail = document.getElementById('normal_trail')?.textContent || 0;
    const normalGround = document.getElementById('normal_ground')?.textContent || 0;
    const normalLinear = document.getElementById('normal_linear')?.textContent || 0;

    // Рассчитываем значения для полей calc_normal_*
    const calcNormalCity = calculateNormalValue(kilometresCity, normalCity);
    const calcNormalTrail = calculateNormalValue(kilometresTrail, normalTrail);
    const calcNormalGround = calculateNormalValue(kilometresGround, normalGround);
    const calcNormalLinear = calculateNormalValue(kilometresLinear, normalLinear);

    // Обновляем поля calc_normal_*
    const calcNormalCityField = document.getElementById('calc_normal_city');
    const calcNormalTrailField = document.getElementById('calc_normal_trail');
    const calcNormalGroundField = document.getElementById('calc_normal_ground');
    const calcNormalLinearField = document.getElementById('calc_normal_linear');

    if (calcNormalCityField) calcNormalCityField.value = calcNormalCity;
    if (calcNormalTrailField) calcNormalTrailField.value = calcNormalTrail;
    if (calcNormalGroundField) calcNormalGroundField.value = calcNormalGround;
    if (calcNormalLinearField) calcNormalLinearField.value = calcNormalLinear;

    updateFuel();
}
function updateFuel(){
    const calcNormalCityField = document.getElementById('calc_normal_city');
    const calcNormalTrailField = document.getElementById('calc_normal_trail');
    const calcNormalGroundField = document.getElementById('calc_normal_ground');
    const calcNormalLinearField = document.getElementById('calc_normal_linear');
    const calcNormalCargoField = document.getElementById('calc_normal_cargo');
    const calcNormalPumpField = document.getElementById('calc_normal_pump');
    const normalFuelField = document.getElementById('normal_fuel');

    const calcNormalCity = parseFloat(calcNormalCityField?.value) || 0;
    const calcNormalTrail = parseFloat(calcNormalTrailField?.value) || 0;
    const calcNormalGround = parseFloat(calcNormalGroundField?.value) || 0;
    const calcNormalLinear = parseFloat(calcNormalLinearField?.value) || 0;
    const calcNormalCargo = parseFloat(calcNormalCargoField?.value) || 0;
    const calcNormalPump = parseFloat(calcNormalPumpField?.value) || 0;

    const total = calcNormalCity + calcNormalTrail + calcNormalGround + calcNormalLinear + calcNormalCargo + calcNormalPump;

    if (normalFuelField) normalFuelField.value = total;

    checkTicketWritesOffMatch()
}
function updateTicketKilometres() {
    const cityInput = document.getElementById('kilometres_city');
    const trailInput = document.getElementById('kilometres_trail');
    const groundInput = document.getElementById('kilometres_ground');
    const linearInput = document.getElementById('kilometres_linear');
    const ticketInput = document.getElementById('kilometres_ticket');

    const city = parseFloat(cityInput?.value) || 0;
    const trail = parseFloat(trailInput?.value) || 0;
    const linear = parseFloat(linearInput?.value) || 0;
    const ground = parseFloat(groundInput?.value) || 0;

    const total = city + trail + ground + linear;
    if (ticketInput) ticketInput.value = total;

    // Обновляем расчетные поля норм
    updateCalcNormals();

    // Проверяем совпадение после обновления
    checkKilometresMatch();
}
function updateCargoNo() {
    const cargoInput = document.getElementById('cargo');
    const cargoNoInput = document.getElementById('cargo-no');
    const kilometres_speedometerInput = document.getElementById('kilometres_speedometer');

    const cargo = parseFloat(cargoInput.value) || 0;
    const kilometres_speedometer = parseFloat(kilometres_speedometerInput.value) || 0;

    const cargoNo = kilometres_speedometer - cargo;
    cargoNoInput.value = cargoNo;

    // Обновляем расчетные поля норм (cargo)
    updateCalcNormals();
    // Проверяем совпадение после обновления
    checkKilometresMatch();
}

function updateCargo(){
    const cargoInput = document.getElementById('cargo');
    const cargo1Input = document.getElementById('cargo_1');
    const cargo2Input = document.getElementById('cargo_2');
    const cargo3Input = document.getElementById('cargo_3');
    const cargo4Input = document.getElementById('cargo_4');
    const cargo5Input = document.getElementById('cargo_5');

    const cargo1 = parseFloat(cargo1Input?.value) || 0;
    const cargo2 = parseFloat(cargo2Input?.value) || 0;
    const cargo3 = parseFloat(cargo3Input?.value) || 0;
    const cargo4 = parseFloat(cargo4Input?.value) || 0;
    const cargo5 = parseFloat(cargo5Input?.value) || 0;

    const total = cargo1 + cargo2 + cargo3 + cargo4 + cargo5;
    if (cargoInput) cargoInput.value = total;

    updateCargoNo();
    updateCalcNormalsCargo();
    updateCompletedWork()
}
function updateWeight(){
    const completedWorkInput = document.getElementById('completed_work');
    const weight1Input = document.getElementById('weight_1');
    const weight2Input = document.getElementById('weight_2');
    const weight3Input = document.getElementById('weight_3');
    const weight4Input = document.getElementById('weight_4');
    const weight5Input = document.getElementById('weight_5');

    const weight1 = parseFloat(weight1Input?.value) || 0;
    const weight2 = parseFloat(weight2Input?.value) || 0;
    const weight3 = parseFloat(weight3Input?.value) || 0;
    const weight4 = parseFloat(weight4Input?.value) || 0;
    const weight5 = parseFloat(weight5Input?.value) || 0;

    const total = weight1 + weight2 + weight3 + weight4 + weight5;
    if (completedWorkInput) completedWorkInput.value = total.toFixed(2);
    updateCalcNormalsCargo()
    updateCompletedWork()
}

function updateCompletedWork(){
    const completedWorkInput = document.getElementById('completed_work');
    const cargoInput = document.getElementById('cargo');
    const completedWorkKmInput = document.getElementById('completed_work_km');

    const completedWork = parseFloat(completedWorkInput.value) || 0;
    const cargo = parseFloat(cargoInput.value) || 0;

    if(completedWorkKmInput)completedWorkKmInput.value = Math.round(completedWork * cargo);
}
function updateSpent(){
    const weighticketWritet = parseFloat(document.getElementById('ticket_write_off')?.value) || 0;
    const takenTransferred = parseFloat(document.getElementById('taken_transferred_f')?.value) || 0;
    document.getElementById('spent_fuel').value = weighticketWritet + takenTransferred
    checkTicketWritesOffMatch()
}
function updateSpentButter(){
    const takenTransferred = parseFloat(document.getElementById('taken_transferred_b')?.value) || 0;
    document.getElementById('spent_butter').value =  takenTransferred
}
function loadNorms(){
    const normId = document.getElementById('m_norm').value;
    if (!normId) {
        return;
    }
    const normsData = initMilitaryTicketForm()
    const selectedNorm = normsData.find(norm => norm.id == normId);

    if (selectedNorm) {
        document.getElementById('normal_city').textContent = selectedNorm.city || '0';
        document.getElementById('normal_trail').textContent = selectedNorm.trail || '0';
        document.getElementById('normal_ground').textContent = selectedNorm.ground || '0';
        document.getElementById('normal_linear').textContent = selectedNorm.linear || '0';
        document.getElementById('normal_cargo').textContent = selectedNorm.cargo || '0';
        document.getElementById('normal_pump').textContent = selectedNorm.pump|| '0';
    }
    // Обновляем расчетные поля норм
    updateCalcNormals();
}
function initMilitaryTicketForm() {
    let normsData = [];
    const normsElement = document.getElementById('military-norms-data');
    if (normsElement && normsElement.dataset.norms) {
        try {
            normsData = JSON.parse(normsElement.dataset.norms);
        } catch (e) {
            console.error('Ошибка парсинга данных норм:', e);
        }
    }
    return normsData;
}
function updateTicketKilometresWork(){
    const kilometresStartInput = document.getElementById('kilometres_speedometer_start');
    const kilometresEndInput = document.getElementById('kilometres_speedometer_end');
    const kilometresInput = document.getElementById('kilometres_speedometer');

    const kmStart = parseFloat(kilometresStartInput?.value) || 0;
    const kmEnd = parseFloat(kilometresEndInput?.value) || 0;

    const total = kmEnd - kmStart;
    if (kilometresInput) kilometresInput.value = total;

    checkKilometresMatch();
}
async function updateTakenFuel(){
    const takenFuelInput = document.getElementById('taken_fuel');
    const takenLoadInput = document.getElementById('taken_load_f');
    const takenLoadOtherInput = document.getElementById('taken_load_other_f');
    const takenTransferredInput = document.getElementById('taken_transferred_f');
    const takenOtherInput = document.getElementById('taken_other_f');

    const takenLoad = parseFloat(takenLoadInput?.value) || 0;
    const takenLoadOther = parseFloat(takenLoadOtherInput?.value) || 0;
    const takenTransferred = parseFloat(takenTransferredInput?.value) || 0;
    const takenOther = parseFloat(takenOtherInput?.value) || 0;

    const manualTotal = takenLoad + takenLoadOther + takenTransferred + takenOther;

    const tempId = document.getElementById('temp_id')?.value;
    let fuelTotal = 0;
    let fuelOtherTotal = 0;
    let fuelPlacesTotal = 0;

    if (tempId) {
        try {
            const [localRes, otherRes, placesRes] = await Promise.all([
                fetch(`/military-ticket/temp-get-fuel/${tempId}`),
                fetch(`/military-ticket/temp-get-fuel-other/${tempId}`),
                fetch(`/military-ticket/temp-get-fuel-places/${tempId}`)
            ]);
            const localData = await localRes.json();
            const otherData = await otherRes.json();
            const placesData = await placesRes.json();
            fuelTotal = localData.total || 0;
            fuelOtherTotal = otherData.total || 0;
            fuelPlacesTotal = placesData.total || 0;
        } catch (e) {
            console.error('Error loading fuel totals:', e);
        }
    }

    const total = manualTotal + fuelTotal + fuelOtherTotal + fuelPlacesTotal;
    if (takenFuelInput) takenFuelInput.value = total;
}
function updateTakenButter(){
    const takenButterInput = document.getElementById('taken_butter');
    const takenLoadInput = document.getElementById('taken_load_b');
    const takenLoadOtherInput = document.getElementById('taken_load_other_b');
    const takenTransferredInput = document.getElementById('taken_transferred_b');
    const takenOtherInput = document.getElementById('taken_other_b');

    const takenLoad = parseFloat(takenLoadInput?.value) || 0;
    const takenLoadOther = parseFloat(takenLoadOtherInput?.value) || 0;
    const takenTransferred = parseFloat(takenTransferredInput?.value) || 0;
    const takenOther = parseFloat(takenOtherInput?.value) || 0;

    const total = takenLoad + takenLoadOther + takenTransferred + takenOther;
    if (takenButterInput) takenButterInput.value = total;
}

///////////////////////////////////////////////////////////////////////

function openUrl(idModelMachine, monthParam, yearParam) {
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');
    const monthElement = document.getElementById('currentMonthYear');

    if (!prevBtn || !nextBtn || !monthElement) {
        console.error('Не найдены элементы календаря');
        return;
    }

    let currentDate = new Date();
    currentDate.setFullYear(parseInt(yearParam));

    if (monthParam && monthParam !== '') {
        let monthNumber = parseInt(monthParam);
        if (!isNaN(monthNumber) && monthNumber >= 1 && monthNumber <= 12) {
            currentDate.setMonth(monthNumber - 1);
        }
    }


    function navigateToMonth(offset) {
        currentDate.setMonth(currentDate.getMonth() + offset);
        let monthForUrl = currentDate.getMonth() + 1;
        let yearForUrl = currentDate.getFullYear();

        window.location.href = `/military-ticket/${idModelMachine}/${monthForUrl}/${yearForUrl}`;
    }

    prevBtn.addEventListener('click', function (e) {
        e.preventDefault();
        navigateToMonth(-1);
    });

    nextBtn.addEventListener('click', function (e) {
        e.preventDefault();
        navigateToMonth(1);
    });

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        const monthNames = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

        monthElement.textContent = `${monthNames[month]} ${year}`;
    }

    renderCalendar();
}
function openUrlEditTicket(idModelMachine, monthParam, yearParam,id) {
    window.location.href = `/military-ticket/edit/${idModelMachine}/${monthParam}/${yearParam}/${id}`;
}

///////////////////////////////////////////////////////////////////////

// Функция для показа предупреждения
function showWarningMessage(text,field) {
    let warningDiv = document.getElementById(field);

    if (!warningDiv) {
        warningDiv = document.createElement('div');
        warningDiv.id = field;
        warningDiv.className = 'alert alert-warning alert-dismissible fade show mt-2';
        warningDiv.setAttribute('role', 'alert');

        const fieldset = document.querySelector(`#alert_check_${field}_Match`).closest('fieldset');
        fieldset.appendChild(warningDiv);
    }

    warningDiv.innerHTML = `
        <i class="bi bi-exclamation-triangle-fill"></i> 
        ${text}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
}
// Функция для скрытия предупреждения
function hideWarningMessage(field) {
    const warningDiv = document.getElementById(field);
    if (warningDiv) {
        warningDiv.remove();
    }
}

///////////////////////////////////////////////////////////////////////


// Функция добавления заправки
async function addFuelRecord() {
    const tempId = document.getElementById('temp_id').value;
    const date = document.getElementById('fuel_date').value;
    const mt_local_id = document.getElementById('fuel_type').value;
    const value = document.getElementById('fuel_value').value;

    // Валидация
    if (!date) {
        showAlert('danger', 'Укажите дату заправки');
        return;
    }
    if (!mt_local_id) {
        showAlert('danger', 'Выберите источник заправки');
        return;
    }
    if (!value || value <= 0) {
        showAlert('danger', 'Укажите корректное количество топлива');
        return;
    }
    try {
        const response = await fetch('/military-ticket/temp-add-fuel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                temp_id: tempId,
                date: date,
                mt_local_id: mt_local_id,
                value: parseFloat(value)
            })
        });

        const result = await response.json();

        if (result.success) {
            // Очищаем форму
            document.getElementById('fuel_date').value = '';
            document.getElementById('fuel_type').value = '';
            document.getElementById('fuel_value').value = '';

            // Обновляем список и общую сумму
            await loadFuelRecords();
            await loadFuelOtherRecords();

            // Закрываем модальное окно
            const modal = bootstrap.Modal.getInstance(document.getElementById('fuelModal'));
            modal.hide();
            showAlert('success', 'Заправка успешно добавлена');

        } else {
            showAlert('danger', result.error || 'Ошибка при добавлении заправки');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('danger', 'Ошибка при добавлении заправки');
    }
}

// Функция загрузки списка заправок
async function loadFuelRecords() {
    const tempId = document.getElementById('temp_id').value;

    if (!tempId) return;

    try {
        const response = await fetch(`/military-ticket/temp-get-fuel/${tempId}`);
        const data = await response.json();

        // Обновляем список
        const fuelList = document.getElementById('fuelList');

        if (data.records.length === 0) {
            fuelList.innerHTML = '<li class="list-group-item text-muted text-center py-3">Нет добавленных заправок</li>';
        } else {
            fuelList.innerHTML = '';
            data.records.forEach(record => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `
                    <div>
                        <strong>${record.date}</strong><br>
                        <small class="text-muted">${getFuelTypeName(record.mt_local_id)}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary rounded-pill me-2">${record.value} л</span>
                        <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteConfirmModal"
                                data-fuel-id="${record.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                fuelList.appendChild(li);
            });
        }

        await updateTakenFuel();

    } catch (error) {
        console.error('Error:', error);
    }
}

// Функция удаления заправки
async function removeFuelRecord(fuelId) {
    const tempId = document.getElementById('temp_id').value;

    try {
        const response = await fetch('/military-ticket/temp-remove-fuel', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                temp_id: tempId,
                fuel_id: fuelId
            })
        });

        const result = await response.json();

        if (result.success) {
            await loadFuelRecords();
            await loadFuelOtherRecords();
            showAlert('success', 'Заправка удалена');
        } else {
            showAlert('danger', result.error || 'Ошибка при удалении');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('danger', 'Ошибка при удалении заправки');
    }
}

// Функция получения названия типа заправки
function getFuelTypeName(id) {
    const select = document.getElementById('fuel_type');
    const option = select.querySelector(`option[value="${id}"]`);
    return option ? option.textContent : 'Неизвестный источник';
}

// Функция показа уведомлений
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

///////////////////////////////////////////////////////////////////////
// Функции для заправок "Другие источники"

async function addFuelOtherRecord() {
    const tempId = document.getElementById('temp_id').value;
    const date = document.getElementById('fuel_other_date').value;
    const mt_other_id = document.getElementById('fuel_other_type').value;
    const value = document.getElementById('fuel_other_value').value;

    if (!date) {
        showAlert('danger', 'Укажите дату заправки');
        return;
    }
    if (!mt_other_id) {
        showAlert('danger', 'Выберите источник заправки');
        return;
    }
    if (!value || value <= 0) {
        showAlert('danger', 'Укажите корректное количество топлива');
        return;
    }

    try {
        const response = await fetch('/military-ticket/temp-add-fuel-other', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                temp_id: tempId,
                date: date,
                mt_other_id: mt_other_id,
                value: parseFloat(value)
            })
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('fuel_other_date').value = '';
            document.getElementById('fuel_other_type').value = '';
            document.getElementById('fuel_other_value').value = '';

            await loadFuelRecords();
            await loadFuelOtherRecords();

            const modal = bootstrap.Modal.getInstance(document.getElementById('fuelOtherModal'));
            modal.hide();
            showAlert('success', 'Заправка успешно добавлена');
        } else {
            showAlert('danger', result.error || 'Ошибка при добавлении заправки');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('danger', 'Ошибка при добавлении заправки');
    }
}

async function loadFuelOtherRecords() {
    const tempId = document.getElementById('temp_id').value;
    if (!tempId) return;

    try {
        const response = await fetch(`/military-ticket/temp-get-fuel-other/${tempId}`);
        const data = await response.json();

        const fuelOtherList = document.getElementById('fuelOtherList');
        if (!fuelOtherList) return;

        if (data.records.length === 0) {
            fuelOtherList.innerHTML = '<li class="list-group-item text-muted text-center py-3">Нет добавленных заправок</li>';
        } else {
            fuelOtherList.innerHTML = '';
            data.records.forEach(record => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `
                    <div>
                        <strong>${record.date}</strong><br>
                        <small class="text-muted">${getFuelOtherTypeName(record.mt_other_id)}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary rounded-pill me-2">${record.value} л</span>
                        <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteConfirmModalOther"
                                data-fuel-other-id="${record.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                fuelOtherList.appendChild(li);
            });
        }

        await updateTakenFuel();

    } catch (error) {
        console.error('Error:', error);
    }
}

async function removeFuelOtherRecord(fuelId) {
    const tempId = document.getElementById('temp_id').value;

    try {
        const response = await fetch('/military-ticket/temp-remove-fuel-other', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                temp_id: tempId,
                fuel_id: fuelId
            })
        });

        const result = await response.json();

        if (result.success) {
            await loadFuelRecords();
            await loadFuelOtherRecords();
            showAlert('success', 'Заправка удалена');
        } else {
            showAlert('danger', result.error || 'Ошибка при удалении');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('danger', 'Ошибка при удалении заправки');
    }
}

function getFuelOtherTypeName(id) {
    const select = document.getElementById('fuel_other_type');
    if (!select) return 'Неизвестный источник';
    const option = select.querySelector(`option[value="${id}"]`);
    return option ? option.textContent : 'Неизвестный источник';
}

///////////////////////////////////////////////////////////////////////
// Функции для заправок "Места"

async function addFuelPlacesRecord() {
    const tempId = document.getElementById('temp_id').value;
    const date = document.getElementById('fuel_places_date').value;
    const mt_places_id = document.getElementById('fuel_places_type').value;
    const value = document.getElementById('fuel_places_value').value;

    if (!date) {
        showAlert('danger', 'Укажите дату заправки');
        return;
    }
    if (!mt_places_id) {
        showAlert('danger', 'Выберите место заправки');
        return;
    }
    if (!value || value <= 0) {
        showAlert('danger', 'Укажите корректное количество топлива');
        return;
    }

    try {
        const response = await fetch('/military-ticket/temp-add-fuel-places', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                temp_id: tempId,
                date: date,
                mt_places_id: mt_places_id,
                value: parseFloat(value)
            })
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('fuel_places_date').value = '';
            document.getElementById('fuel_places_type').value = '';
            document.getElementById('fuel_places_value').value = '';

            await loadFuelRecords();
            await loadFuelOtherRecords();
            await loadFuelPlacesRecords();

            const modal = bootstrap.Modal.getInstance(document.getElementById('fuelPlacesModal'));
            modal.hide();
            showAlert('success', 'Заправка успешно добавлена');
        } else {
            showAlert('danger', result.error || 'Ошибка при добавлении заправки');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('danger', 'Ошибка при добавлении заправки');
    }
}

async function loadFuelPlacesRecords() {
    const tempId = document.getElementById('temp_id').value;
    if (!tempId) return;

    try {
        const response = await fetch(`/military-ticket/temp-get-fuel-places/${tempId}`);
        const data = await response.json();

        const fuelPlacesList = document.getElementById('fuelPlacesList');
        if (!fuelPlacesList) return;

        if (data.records.length === 0) {
            fuelPlacesList.innerHTML = '<li class="list-group-item text-muted text-center py-3">Нет добавленных заправок</li>';
        } else {
            fuelPlacesList.innerHTML = '';
            data.records.forEach(record => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `
                    <div>
                        <strong>${record.date}</strong><br>
                        <small class="text-muted">${getFuelPlacesTypeName(record.mt_places_id)}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary rounded-pill me-2">${record.value} л</span>
                        <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteConfirmModalPlaces"
                                data-fuel-places-id="${record.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                fuelPlacesList.appendChild(li);
            });
        }

        await updateTakenFuel();

    } catch (error) {
        console.error('Error:', error);
    }
}

async function removeFuelPlacesRecord(fuelId) {
    const tempId = document.getElementById('temp_id').value;

    try {
        const response = await fetch('/military-ticket/temp-remove-fuel-places', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                temp_id: tempId,
                fuel_id: fuelId
            })
        });

        const result = await response.json();

        if (result.success) {
            await loadFuelRecords();
            await loadFuelOtherRecords();
            await loadFuelPlacesRecords();
            showAlert('success', 'Заправка удалена');
        } else {
            showAlert('danger', result.error || 'Ошибка при удалении');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('danger', 'Ошибка при удалении заправки');
    }
}

function getFuelPlacesTypeName(id) {
    const select = document.getElementById('fuel_places_type');
    if (!select) return 'Неизвестное место';
    const option = select.querySelector(`option[value="${id}"]`);
    return option ? option.textContent : 'Неизвестное место';
}
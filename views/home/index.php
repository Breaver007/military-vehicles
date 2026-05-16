<?php $title = $title ?? 'Главная'; ?>
<div class="container-fluid py-5 my-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="bi bi-book"></i> Инструкция по использованию системы</h2>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Данная система предназначена для учёта расхода топлива военной техники через эксплуатационные карточки.
            </div>

            <!-- Содержание -->
            <div class="mb-4">
                <h4 class="mb-3"><i class="bi bi-list-ol"></i> Содержание</h4>
                <ol class="list-group list-group-numbered">
                    <li class="list-group-item"><a href="#section-1">Подготовка: создание справочников</a></li>
                    <li class="list-group-item"><a href="#section-2">Добавление техники</a></li>
                    <li class="list-group-item"><a href="#section-3">Создание норм расхода</a></li>
                    <li class="list-group-item"><a href="#section-4">Настройка источников заправок</a></li>
                    <li class="list-group-item"><a href="#section-5">Создание эксплуатационной карточки</a></li>
                    <li class="list-group-item"><a href="#section-6">Редактирование карточки</a></li>
                    <li class="list-group-item"><a href="#section-7">Экспорт и печать</a></li>
                </ol>
            </div>

            <!-- Раздел 1: Подготовка -->
            <div id="section-1" class="mb-5">
                <h4 class="text-primary"><i class="bi bi-1-circle"></i> 1. Подготовка: создание справочников</h4>
                <p>Перед началом работы необходимо настроить справочные данные через меню в правом верхнем углу:</p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-secondary bg-gradient">
                                <h5 class="mb-0"><i class="bi bi-fuel-pump"></i> Виды топлива</h5>
                            </div>
                            <div class="card-body">
                                <p>Создайте виды топлива, которые будут использоваться в системе.</p>
                                <p class="text-muted small">Меню: <code>Виды топлива</code> → кнопка "Добавить"</p>
                                <ul>
                                    <li>Название (например: "Дизельное топливо")</li>
                                    <li>Единица измерения</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-secondary bg-gradient">
                                <h5 class="mb-0"><i class="bi bi-building"></i> Склад в/ч</h5>
                            </div>
                            <div class="card-body">
                                <p>Укажите склады части, откуда может выдаваться топливо.</p>
                                <p class="text-muted small">Меню: <code>Склад в/ч</code> → кнопка "Добавить"</p>
                                <ul>
                                    <li>Название склада</li>
                                    <li>Наименование документа</li>
                                    <li>Номер документа</li>
                                    <li>Дата</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-secondary bg-gradient">
                                <h5 class="mb-0"><i class="bi bi-truck"></i> Заправки других в/ч</h5>
                            </div>
                            <div class="card-body">
                                <p>Укажите внешние источники заправки (другие воинские части, АЗС).</p>
                                <p class="text-muted small">Меню: <code>Заправки других в/ч</code> → кнопка "Добавить"</p>
                                <ul>
                                    <li>Название (например: "2-й военный автопарк")</li>
                                    <li>Наименование документа</li>
                                    <li>Номер документа</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-secondary bg-gradient">
                                <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Прочие заправки</h5>
                            </div>
                            <div class="card-body">
                                <p>Дополнительные места заправки (используются в табе "Места").</p>
                                <p class="text-muted small">Меню: <code>Прочие заправки</code> → кнопка "Добавить"</p>
                                <ul>
                                    <li>Название</li>
                                    <li>Реквизиты документов</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Раздел 2: Добавление техники -->
            <div id="section-2" class="mb-5">
                <h4 class="text-primary"><i class="bi bi-2-circle"></i> 2. Добавление техники</h4>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Важно:</strong> Перед созданием эксплуатационных карточек добавьте технику в систему!
                </div>
                <ol>
                    <li>Перейдите в меню <strong>Техника</strong> (через кнопку меню в шапке)</li>
                    <li>Нажмите кнопку <strong>"Добавить технику"</strong></li>
                    <li>Заполните данные:
                        <ul>
                            <li><strong>Название</strong> — наименование техники (например: "Урал-4320")</li>
                            <li><strong>Регистрационный номер</strong> — госномер</li>
                            <li><strong>Подразделение</strong> — воинская часть</li>
                            <li><strong>Вид топлива</strong> — выберите из справочника</li>
                            <li><strong>Норма расхода</strong> — базовая норма (л/100км)</li>
                        </ul>
                    </li>
                    <li>Нажмите <strong>"Сохранить"</strong></li>
                </ol>
            </div>

            <!-- Раздел 3: Создание норм -->
            <div id="section-3" class="mb-5">
                <h4 class="text-primary"><i class="bi bi-3-circle"></i> 3. Создание норм расхода</h4>
                <p>Нормы расхода используются для автоматического расчёта положенного топлива. Система позволяет создать несколько норм для разных условий эксплуатации.</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-secondary bg-gradient">
                                <h5 class="mb-0">Параметры нормы</h5>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <li><strong>Название нормы</strong> — для удобства выбора</li>
                                    <li><strong>Город</strong> — расход в городе (л/100км)</li>
                                    <li><strong>Трасса</strong> — расход на шоссе</li>
                                    <li><strong>Грунт</strong> — расход на грунтовых дорогах</li>
                                    <li><strong>Линейная</strong> — линейная норма</li>
                                    <li><strong>Груз</strong> — норма на тонну груза</li>
                                    <li><strong>Насос</strong> — расход на работу насоса (л/моточас)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-muted">Создание норм: перейдите в раздел "Техника", выберите технику, затем вкладка "Нормы".</p>
            </div>

            <!-- Раздел 4: Настройка источников -->
            <div id="section-4" class="mb-5">
                <h4 class="text-primary"><i class="bi bi-4-circle"></i> 4. Настройка источников заправок</h4>
                <p>В системе реализовано <strong>3 типа источников</strong> заправок:</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Тип</th>
                                <th>Источник</th>
                                <th>Где отображается</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-primary">Склад в/ч</span></td>
                                <td>Справочник "Склад в/ч"</td>
                                <td>Таб "Склад в/ч"</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Другие источники</span></td>
                                <td>Справочник "Заправки других в/ч"</td>
                                <td>Таб "Другие источники"</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning">Места</span></td>
                                <td>Справочник "Прочие заправки"</td>
                                <td>Таб "Места"</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info">
                    <i class="bi bi-lightbulb"></i> <strong>Рекомендация:</strong> Заполните все справочники заранее, чтобы они были доступны при создании карточек.
                </div>
            </div>

            <!-- Раздел 5: Создание карточки -->
            <div id="section-5" class="mb-5">
                <h4 class="text-primary"><i class="bi bi-5-circle"></i> 5. Создание эксплуатационной карточки</h4>

                <h5 class="mt-3">5.1 Переход к созданию</h5>
                <ol>
                    <li>Нажмите <strong>"Эксплуатационная карточка"</strong> в главном меню</li>
                    <li>Выберите технику из списка</li>
                    <li>Выберите месяц и год</li>
                    <li>Нажмите кнопку <strong>"Создать"</strong></li>
                </ol>

                <h5 class="mt-3">5.2 Заполнение полей</h5>
                <div class="accordion" id="accordionCreate">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Путевой лист
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <ul>
                                    <li><strong>Дата</strong> — дата путевого листа</li>
                                    <li><strong>Номер</strong> — номер путевого листа</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Предварительные данные
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ul>
                                    <li><strong>Спидометр на начало дня</strong> — показания на начало смены</li>
                                    <li><strong>Спидометр на конец дня</strong> — показания на конец смены</li>
                                    <li><strong>Кол-во дней</strong> — количество отработанных дней</li>
                                    <li><strong>Выбор нормы</strong> — норма расхода для расчётов</li>
                                </ul>
                                <p class="text-muted small">После заполнения спидометров поле "Всего" рассчитывается автоматически.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Данные из путевки (пробег)
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ul>
                                    <li><strong>Город</strong> — пробег по городу</li>
                                    <li><strong>Трасса</strong> — пробег по шоссе</li>
                                    <li><strong>Грунт</strong> — пробег по грунтовым дорогам</li>
                                    <li><strong>Линейная</strong> — линейный пробег</li>
                                    <li><strong>К списанию</strong> — топливо к списанию</li>
                                </ul>
                                <p class="text-muted small">Поле "км по путевке" рассчитывается автоматически как сумма всех видов пробега.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                Расчёт по нормам
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p>Поля рассчитываются автоматически на основе:</p>
                                <ul>
                                    <li>Выбранной нормы</li>
                                    <li>Данных пробега</li>
                                    <li>Данных о грузе и весе</li>
                                    <li>Моточасов работы насоса</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                Данные по грузу и весу
                            </button>
                        </h2>
                        <div id="collapse5" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <ul>
                                    <li><strong>Груз 1-5</strong> — количество рейсов с грузом</li>
                                    <li><strong>Вес 1-5</strong> — вес груза за каждый рейс (тонн)</li>
                                </ul>
                                <p class="text-muted small">Поле "Тонн" (выполненная работа) рассчитывается автоматически как сумма весов.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                                Материальные средства — Заправки
                            </button>
                        </h2>
                        <div id="collapse6" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p>Заправки добавляются через <strong>3 табы</strong>:</p>
                                <ol>
                                    <li><strong>Склад в/ч</strong> — нажмите кнопку "+", выберите дату, склад и количество</li>
                                    <li><strong>Другие источники</strong> — аналогично для внешних заправок</li>
                                    <li><strong>Места</strong> — для прочих мест заправки</li>
                                </ol>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle"></i> Поле "Получено горючего" рассчитывается автоматически как сумма всех заправок +手动ные поля (заправка, заправка др., передано, получено др.)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="mt-3">5.3 Сохранение</h5>
                <p>После заполнения всех данных нажмите <strong>"Создать"</strong>. Карточка будет сохранена в базу данных.</p>
            </div>

            <!-- Раздел 6: Редактирование -->
            <div id="section-6" class="mb-5">
                <h4 class="text-primary"><i class="bi bi-6-circle"></i> 6. Редактирование карточки</h4>
                <ol>
                    <li>Перейдите в раздел <strong>"Эксплуатационная карточка"</strong></li>
                    <li>Выберите технику, месяц и год</li>
                    <li>В списке карточек нажмите кнопку <strong>"Редактировать"</strong> (карандаш)</li>
                    <li>Измените необходимые данные</li>
                    <li>Для удаления заправки нажмите кнопку с корзиной в списке заправок</li>
                    <li>Для добавления новой заправки используйте кнопку "+" в соответствующем табе</li>
                    <li>Нажмите <strong>"Обновить"</strong></li>
                </ol>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> При редактировании все заправки удаляются и добавляются заново согласно списку в табах.
                </div>
            </div>

            <!-- Раздел 7: Экспорт -->
            <div id="section-7" class="mb-5">
                <h4 class="text-primary"><i class="bi bi-7-circle"></i> 7. Экспорт и печать</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-file-earmark-excel"></i> Экспорт в Excel</h5>
                            </div>
                            <div class="card-body">
                                <ol>
                                    <li>Выберите технику, месяц, год</li>
                                    <li>Нажмите кнопку <strong>"Экспорт"</strong></li>
                                    <li>Скачается файл Excel с формой карточки</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="bi bi-printer"></i> Печать</h5>
                            </div>
                            <div class="card-body">
                                <ol>
                                    <li>Выберите технику, месяц, год</li>
                                    <li>Нажмите кнопку <strong>"Печать"</strong></li>
                                    <li>Выберите период дат</li>
                                    <li>Нажмите "Сформировать"</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Клавиши -->
            <div class="mb-5">
                <h4 class="text-primary"><i class="bi bi-keyboard"></i> Быстрые клавиши и подсказки</h4>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>Элемент</th>
                                <th>Описание</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-success">авто</span></td>
                                <td>Поле рассчитывается автоматически</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info"><i class="bi bi-calculator-fill"></i> расчет</span></td>
                                <td>Поле участвует в расчетах</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning">участвует в расчете</span></td>
                                <td>Изменение этого поля влияет на итоговые расчеты</td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-plus-lg text-primary"></i></td>
                                <td>Добавить запись (заправку)</td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-pencil text-warning"></i></td>
                                <td>Редактировать запись</td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-trash text-danger"></i></td>
                                <td>Удалить запись</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Техподдержка -->
            <div class="alert alert-secondary">
                <h5><i class="bi bi-question-circle"></i> Техническая поддержка</h5>
                <p>При возникновении вопросов или проблем обращайтесь к администратору системы.</p>
            </div>
        </div>
    </div>
</div>
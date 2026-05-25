# AGENTS.md — Military Vehicles

## Project overview

Plain PHP application (no framework) with a custom MVC. Tracks military vehicle fuel consumption via "operational cards" (эксплуатационные карточки).

## Entrypoint & routing

- `index.php` — single entrypoint (Apache rewrites via `.htaccess`)
- `config/routes.php` — all routes defined here; session also started here
- Custom `App\Router\Router` supports `get()`, `post()`, `put()`, `delete()` — all return `Route` objects for chaining `.name('...')`
- Named routes are registered in a second pass in `index.php` lines 14-18. If you add a new named route, verify both passes still work.

## Architecture

| Layer | Location | Notes |
|---|---|---|
| Controllers | `App/Controllers/` | Extend `App\Controllers\Controller` |
| Models | `App/Models/OperationalCard/` | Extend `App\Models\Model` |
| Views | `views/` | PHP templates, wrapped by `views/layout.php` |
| DB layer | `App/Database/Database.php` | Singleton PDO, hardcoded config |

## Database

- MySQL, database `military-vehicles`, host `127.0.1.14`, user `root`, no password
- Config is hardcoded in `App/Database/Database.php:8-14`
- PDO with `ERRMODE_EXCEPTION`, `FETCH_ASSOC`, real prepared statements

## Key dependencies (composer.json)

- `phpoffice/phpspreadsheet` — Excel export (used in MilitaryTicketController)
- `symfony/var-dumper` — `dd()` helper for debugging

## Controller conventions

- Extend `Controller` to get `$this->view('template', $data)`, `$this->redirect($url)`, `$this->json($data, $code)`
- View path maps to `views/{template}.php` (e.g. `home/index` → `views/home/index.php`)
- Use `$_SESSION['success']`, `$_SESSION['error']`, `$_SESSION['errors']` for flash messages
- CSS/JS served from `/public/`

## Model conventions

- `Model` base class provides `all()`, `find(id)`, `create(data)`, `update(id, data)`, `delete(id)`, `where(col, op, val)`, `paginate()`, `query()`
- `query()` returns a `QueryBuilder` for chaining: `->where(...)->orderBy(...)->get()` or `->first()`
- Table name set via `protected string $table = '...'` in each model subclass
- All models are under `App\Models\OperationalCard\` (do NOT put new models at `App\Models\` root)

## Route parameter order

Route URL parameters follow the route pattern order, not controller method signature names. Typically: `{idModelMachine}/{month}/{year}/{id}` — the controller receives them in that positional order.

## Dev environment

- Open Server Panel (OSP) with Apache + PHP 8.5 (`.osp/project.ini`)
- No test runner, no linter, no typechecker, no build step configured
- Just `composer install` to set up autoload and dependencies

## Language

- UI text, comments, and session messages are in Russian
- DB column names are in Russian/transliterated English mix

---

## Учёт масел (military_butter)

### Таблицы

| Таблица | Назначение |
|---|---|
| `military_butter` | Справочник видов масел: `id, name, date_edit, is_active` |
| `military_ticket_butter` | Записи масел в путевых листах: `id, date, mt_butter_id, machine_id, value, ticket_id` |

### Маршруты (config/routes.php)

**CRUD справочника:**
| Метод | URL | Описание |
|---|---|---|
| GET | `/military-butter` | Список видов масел |
| GET | `/military-butter/create` | Форма добавления |
| POST | `/military-butter/store` | Сохранение нового вида |
| GET | `/military-butter/{id}` | Просмотр |
| GET | `/military-butter/edit/{id}` | Форма редактирования |
| POST | `/military-butter/update/{id}` | Обновление |
| GET | `/military-butter/delete/{id}` | Удаление |

**Временные записи масел в билете (API через сессию):**
| Метод | URL | Описание |
|---|---|---|
| POST | `/military-ticket/temp-add-butter` | Добавить масло во временную сессию |
| GET | `/military-ticket/temp-get-butter/{tempId}` | Получить временные записи |
| DELETE | `/military-ticket/temp-remove-butter` | Удалить временную запись |

### Механизм работы масел в путевых листах

- **Временные записи** хранятся в `$_SESSION['temp_butters']` (массив объектов с `temp_id, mt_butter_id, name, value`)
- `temp_id` генерируется через `uniqid('butter_')`, для редактирования — `edit_{id}_{uniqid}`
- **Вкладка "Масла"** — 4-я таба в `create.php` и `edit.php`, подключена модалка `MilitaryButterStock`
- **Авторасчёт `taken_butter`** — сумма по всем записям масла (ручные поля + temp-записи) вычисляется через JS `updateTakenButter()` в `ticketCreate.js`
- **При сохранении (`store`/`update`):**
  1. Все записи из `$_POST['butter']` сохраняются в `military_ticket_butter`
  2. Старые записи удаляются через `query()->where('ticket_id', '=', $id)->delete()`
  3. `temp_butters` очищается после сохранения
- **JS-функции** (`ticketCreate.js`):
  - `addButterRecord(formData)` — POST на temp-add-butter, затем `loadButterRecords`
  - `loadButterRecords(tempId)` — GET temp-get-butter, рендерит таблицу
  - `removeButterRecord(tempId, row)` — DELETE temp-remove-butter, удаляет строку
  - `getButterTypeName(id)` — возвращает название вида масла по ID из выпадающего списка
  - `updateTakenButter()` — пересчитывает `taken_butter` как сумму ручных полей `butter_value[]` плюс temp-сумма из атрибута `data-temp-total`

---

## Учёт антифризов (military_antifreeze)

### Таблицы

| Таблица | Назначение |
|---|---|
| `military_antifreeze` | Справочник видов антифризов: `id, name, date_edit, is_active` |
| `military_ticket_antifreeze` | Записи расхода антифриза: `id, date, mt_antifreeze_id, machine_id, value, ticket_id` |

**Важно:** `military_ticket_antifreeze` — это отдельные записи, **не привязанные к путевым листам**. Поле `ticket_id` существует, но не используется. Поле `machine_id` добавлено ALTER-запросом.

### Маршруты (config/routes.php)

**CRUD справочника:**
| Метод | URL | Описание |
|---|---|---|
| GET | `/military-antifreeze` | Список видов антифризов |
| GET | `/military-antifreeze/create` | Форма добавления |
| POST | `/military-antifreeze/store` | Сохранение нового вида |
| GET | `/military-antifreeze/{id}` | Просмотр |
| GET | `/military-antifreeze/edit/{id}` | Форма редактирования |
| POST | `/military-antifreeze/update/{id}` | Обновление |
| GET | `/military-antifreeze/delete/{id}` | Удаление |

**Записи антифриза (standalone):**
| Метод | URL | Описание |
|---|---|---|
| GET | `/military-antifreeze/records` | Список записей |
| GET | `/military-antifreeze/records/create` | Форма добавления |
| POST | `/military-antifreeze/records/store` | Сохранение записи |
| GET | `/military-antifreeze/records/edit/{id}` | Форма редактирования |
| POST | `/military-antifreeze/records/update/{id}` | Обновление |
| GET | `/military-antifreeze/records/delete/{id}` | Удаление |

**Важно:** Маршруты `/military-antifreeze/records/*` объявлены **до** `/military-antifreeze/{id}`, чтобы `{id}` не перехватывал "records".

### Файлы

| Файл | Назначение |
|---|---|
| `App/Controllers/MilitaryButterController.php` | CRUD для видов масел |
| `App/Controllers/MilitaryAntifreezeController.php` | CRUD для антифризов + управление записями (records) |
| `App/Controllers/MilitaryTicketController.php` | Temp-методы для масел, расчёт `taken_butter` |
| `App/Models/OperationalCard/MilitaryButter.php` | Модель `military_butter` |
| `App/Models/OperationalCard/MilitaryTicketButter.php` | Модель `military_ticket_butter` |
| `App/Models/OperationalCard/MilitaryAntifreeze.php` | Модель `military_antifreeze` |
| `App/Models/OperationalCard/MilitaryTicketAntifreeze.php` | Модель `military_ticket_antifreeze` |
| `App/Models/QueryBuilder.php` | Добавлен метод `delete()` |
| `views/military_butter/index.php` | Список видов масел |
| `views/military_butter/create.php` | Форма добавления масла |
| `views/military_butter/show.php` | Просмотр масла |
| `views/military_butter/edit.php` | Форма редактирования масла |
| `views/military_antifreeze/index.php` | Список видов антифризов |
| `views/military_antifreeze/create.php` | Форма добавления антифриза |
| `views/military_antifreeze/show.php` | Просмотр антифриза |
| `views/military_antifreeze/edit.php` | Форма редактирования антифриза |
| `views/military_antifreeze/record_index.php` | Список записей антифриза |
| `views/military_antifreeze/record_create.php` | Форма добавления записи антифриза |
| `views/military_antifreeze/record_edit.php` | Форма редактирования записи антифриза |
| `views/military_ticket/modals/MilitaryButterStock.php` | Модалка добавления масла в билет |
| `views/military_ticket/create.php` | Вкладка "Масла" + модалка + JS |
| `views/military_ticket/edit.php` | Вкладка "Масла" + модалка + JS |
| `public/js/m_ticket/ticketCreate.js` | JS-функции для масел |
| `views/layout.php` | Ссылки в дропдауне |

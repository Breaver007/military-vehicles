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

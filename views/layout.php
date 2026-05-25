<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? $siteName ?></title>
    <link rel="stylesheet" href="/public/css/bootstrap.css">
    <link rel="stylesheet" href="/public/icons/bootstrap-icons-1.13.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body class="bg-body-secondary">
<header>
    <nav class="navbar fixed-top bg-primary">
        <div class="container-fluid">
            <div class="row g-0 align-items-center w-100">
                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 col-xxl-2">
                    <a class="navbar-brand text-light" href="/">
                        <i class="bi bi-bootstrap"></i>
                        Bootstrap
                    </a>
                </div>
                <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 col-xxl-10 d-flex align-items-center justify-content-between">
                    <div>
                        <a href="/military-ticket"
                           class="link-light link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover">Потевые листы</a>
                        <a href="/military-ticket/print-select/"
                           class="link-light link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover">Эксплуатационная
                            карточка</a>
                        <a href="/military-report/print-select"
                           class="link-light link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover">
                            Донесение
                        </a>
                    </div>
                  <div class="btn-group dropstart">
                      <i class="btn btn-sm btn-light bi bi-list"
                         type="button"
                         data-bs-toggle="dropdown"
                         aria-expanded="false">
                      </i>
<ul class="dropdown-menu text-dark">
                           <li>
                               <a href="/military-report/print-select"
                                  class="dropdown-item">Донесение о ГСМ</a>
                           </li>
                           <li><hr class="dropdown-divider"></li>
                           <li>
                               <a href="/military-fuel"
                                  class="dropdown-item">Виды топлива</a>
                           </li>
                          <li>
                              <a href="/military-ticket-from-local-stock"
                                 class="dropdown-item">Склад в/ч</a>
                          </li>
                          <li>
                              <a href="/military-fuel-other-places"
                                 class="dropdown-item">Заправки других в/ч</a>
                          </li>
                           <li>
                               <a href="/military-butter"
                                  class="dropdown-item">Виды масел</a>
                           </li>
                           <li>
                               <a href="/military-antifreeze"
                                  class="dropdown-item">Антифризы</a>
                           </li>
                           <li>
                               <a href="/military-antifreeze/records"
                                  class="dropdown-item">Записи антифриза</a>
                           </li>
                           <li>
                               <a href="/military-ticket-from-local-other"
                                  class="dropdown-item">Прочие заправки</a>
                           </li>
                          <li>
                              <a href="/military-machine"
                                 class="dropdown-item">Техника
                              </a>
                          </li>
                      </ul>
                  </div>

<!--                    -->
<!--                    -->
                </div>
            </div>
        </div>
    </nav>
</header>
<main>
    <?= $content ?>
</main>
<footer class="fixed-bottom bg-primary">
    <p class="text-light text-center my-2">&copy; <?= $currentYear ?> <?= $siteName ?>. Все права защищены.</p>
    <div class="position-fixed end-0 bottom-0 dropdown mx-2 my-1">
        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-palette"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <button class="dropdown-item theme-option" data-theme="light">
                    <i class="bi bi-sun me-2"></i>Светлая
                </button>
            </li>
            <li>
                <button class="dropdown-item theme-option" data-theme="dark">
                    <i class="bi bi-moon me-2"></i>Темная
                </button>
            </li>
            <li>
                <button class="dropdown-item theme-option" data-theme="auto">
                    <i class="bi bi-circle-half me-2"></i>Авто
                </button>
            </li>
        </ul>
    </div>
</footer>
<script  src="/public/js/m_ticket/ticketCreate.js"></script>
<script src="/public/js/bootstrap.bundle.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeOptions = document.querySelectorAll('.theme-option');
        const savedTheme = localStorage.getItem('bsTheme') || 'light';

        // Применяем сохраненную тему
        document.documentElement.setAttribute('data-bs-theme', savedTheme);

        themeOptions.forEach(option => {
            option.addEventListener('click', function () {
                const theme = this.dataset.theme;
                localStorage.setItem('bsTheme', theme);
                document.documentElement.setAttribute('data-bs-theme', theme);
            });
        });

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        console.log(tooltipList)
    });
</script>
</body>
</html>
<?php

namespace App\Controllers;

class ProjectUpdateController extends Controller
{
    public function index(): void
    {
        $this->view('project_update/index');
    }

    public function run(): void
    {
        $gitPath = 'C:\Program Files\Git\bin';
        putenv('PATH=' . getenv('PATH') . PATH_SEPARATOR . $gitPath);
        putenv('PATH=' . getenv('PATH') . PATH_SEPARATOR . 'C:\Program Files\Git\cmd');

        // Проверяем, что Git теперь работает
        exec('git --version 2>&1', $testOutput, $testCode);
        if ($testCode !== 0) {
            $_SESSION['update_output'] = '❌ Git не найден! Установите Git: https://git-scm.com/download/win';
            $this->redirect('/project-update');
            return;
        }

        $rootDir = realpath(__DIR__ . '/../..');
        $gitRemoteUrl = 'https://github.com/Breaver007/military-vehicles.git';
        $allOutput = '';

        // Функция для конвертации вывода
        $convertOutput = function($output) {
            // Пробуем разные кодировки
            $encodings = ['CP866', 'Windows-1251', 'KOI8-R'];
            foreach ($encodings as $encoding) {
                $converted = @iconv($encoding, 'UTF-8//IGNORE', $output);
                if ($converted !== false && $converted !== '') {
                    return $converted;
                }
            }
            // Если ничего не помогло - возвращаем как есть
            return $output;
        };

        // Команды
        $commands = [];

        if (is_dir($rootDir . '/.git')) {
            $commands[] = "cd " . escapeshellarg($rootDir) . " && git pull " . escapeshellarg($gitRemoteUrl);
        }

        if (is_file($rootDir . '/composer.json')) {
            $commands[] = "cd " . escapeshellarg($rootDir) . " && composer install";
        }

        foreach ($commands as $cmd) {
            $cmdOutput = [];
            $exitCode = 0;

            // Добавляем переключение кодировки для Windows
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Для Windows - меняем кодировку консоли
                exec('chcp 65001 > NUL');
            }

            exec($cmd . ' 2>&1', $cmdOutput, $exitCode);

            // Конвертируем каждую строку вывода
            $convertedOutput = [];
            foreach ($cmdOutput as $line) {
                $convertedOutput[] = $convertOutput($line);
            }

            $allOutput .= '$ ' . $cmd . PHP_EOL;
            $allOutput .= implode(PHP_EOL, $convertedOutput) . PHP_EOL;
            $allOutput .= 'Exit code: ' . $exitCode . PHP_EOL . PHP_EOL;
        }

        $_SESSION['update_output'] = $allOutput;
        $this->redirect('/project-update');
    }
}

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
        $output = [];
        $commands = [];

        $rootDir = realpath(__DIR__ . '/../..');

        if (is_dir($rootDir . '/.git')) {
            $commands[] = "cd " . escapeshellarg($rootDir) . " && git pull 2>&1";
        }

        if (is_file($rootDir . '/composer.json')) {
            $commands[] = "cd " . escapeshellarg($rootDir) . " && composer install 2>&1";
        }

        $allOutput = '';
        foreach ($commands as $cmd) {
            $cmdOutput = [];
            exec($cmd, $cmdOutput, $exitCode);
            $allOutput .= '$ ' . $cmd . PHP_EOL;
            $allOutput .= implode(PHP_EOL, $cmdOutput) . PHP_EOL;
            $allOutput .= 'Exit code: ' . $exitCode . PHP_EOL . PHP_EOL;
        }

        $_SESSION['update_output'] = $allOutput;
        $this->redirect('/project-update');
    }
}

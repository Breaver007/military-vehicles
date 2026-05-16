<?php
session_start();
// config/routes.php

use App\Controllers\HomeController;
use App\Controllers\MilitaryFuelController;
use App\Controllers\MilitaryFuelOtherPlacesController;
use App\Controllers\MilitaryModelMachineController;
use App\Controllers\MilitaryReportController;
use App\Controllers\MilitaryTicketController;
use App\Controllers\MilitaryTicketFromLocalOtherController;
use App\Controllers\MilitaryTicketFromLocalStockController;

/** @var App\Routeer\Router $router */

// Главная страница
$router->get('/', [HomeController::class, 'index'])->name('home');

//Донесение
$router->get('/military-report/print-select', [MilitaryReportController::class, 'printSelect'])->name('military-report.printSelect');
$router->post('/military-report/print', [MilitaryReportController::class, 'printForm'])->name('military-report.print');
$router->post('/military-report/print', [MilitaryReportController::class, 'printForm'])->name('military-report.print');

// CRUD для эксплуатационных карточек
$router->get('/military-ticket', [MilitaryTicketController::class, 'index'])->name('military-ticket.index');
$router->get('/military-ticket/create/{id}/{month}/{year}', [MilitaryTicketController::class, 'create'])->name('military-ticket.create');
$router->post('/military-ticket/store', [MilitaryTicketController::class, 'store'])->name('military-ticket.store');
$router->get('/military-ticket/{id}', [MilitaryTicketController::class, 'show'])->name('military-ticket.show');
$router->get('/military-ticket/{id}/{month}/{year}', [MilitaryTicketController::class, 'showMonth'])->name('military-ticket.showMonth');
$router->get('/military-ticket/edit/{idModelMachine}/{month}/{year}/{id}', [MilitaryTicketController::class, 'edit'])->name('military-ticket.edit');
$router->post('/military-ticket/update/{idModelMachine}/{month}/{year}/{id}', [MilitaryTicketController::class, 'update'])->name('military-ticket.update');
$router->get('/military-ticket/delete/{idModelMachine}/{month}/{year}/{id}', [MilitaryTicketController::class, 'delete'])->name('military-ticket.delete');
$router->get('/military-ticket/export/{idModelMachine}/{month}/{year}', [MilitaryTicketController::class, 'exportToExcel'])->name('military-ticket.exportToExcel');
$router->get('/military-ticket/print-select/{idModelMachine}', [MilitaryTicketController::class, 'printSelect'])->name('military-ticket.print-select');
$router->post('/military-ticket/print', [MilitaryTicketController::class, 'printForm'])->name('military-ticket.print');
//$router->get('/military-ticket/print/{idModelMachine}/{month}/{year}', [MilitaryTicketController::class, 'printForm'])->name('military-ticket.print');

// CRUD для видов топлива
$router->get('/military-fuel', [MilitaryFuelController::class, 'index'])->name('military-fuel.index');
$router->get('/military-fuel/create', [MilitaryFuelController::class, 'create'])->name('military-fuel.create');
$router->post('/military-fuel/store', [MilitaryFuelController::class, 'store'])->name('military-fuel.store');
$router->get('/military-fuel/{id}', [MilitaryFuelController::class, 'show'])->name('military-fuel.show');
$router->get('/military-fuel/edit/{id}', [MilitaryFuelController::class, 'edit'])->name('military-fuel.edit');
$router->post('/military-fuel/update/{id}', [MilitaryFuelController::class, 'update'])->name('military-fuel.update');
$router->get('/military-fuel/delete/{id}', [MilitaryFuelController::class, 'delete'])->name('military-fuel.delete');

// CRUD для других заправок
$router->get('/military-fuel-other-places', [MilitaryFuelOtherPlacesController::class, 'index'])->name('military-fuel-other-places.index');
$router->get('/military-fuel-other-places/create', [MilitaryFuelOtherPlacesController::class, 'create'])->name('military-fuel-other-places.create');
$router->post('/military-fuel-other-places/store', [MilitaryFuelOtherPlacesController::class, 'store'])->name('military-fuel-other-places.store');
$router->get('/military-fuel-other-places/{id}', [MilitaryFuelOtherPlacesController::class, 'show'])->name('military-fuel-other-places.show');
$router->get('/military-fuel-other-places/edit/{id}', [MilitaryFuelOtherPlacesController::class, 'edit'])->name('military-fuel-other-places.edit');
$router->post('/military-fuel-other-places/update/{id}', [MilitaryFuelOtherPlacesController::class, 'update'])->name('military-fuel-other-places.update');
$router->get('/military-fuel-other-places/delete/{id}', [MilitaryFuelOtherPlacesController::class, 'delete'])->name('military-fuel-other-places.delete');

// CRUD для местных заправок
$router->get('/military-ticket-from-local-stock', [MilitaryTicketFromLocalStockController::class, 'index'])->name('military-ticket-from-local-stock.index');
$router->get('/military-ticket-from-local-stock/create', [MilitaryTicketFromLocalStockController::class, 'create'])->name('military-ticket-from-local-stock.create');
$router->post('/military-ticket-from-local-stock/store', [MilitaryTicketFromLocalStockController::class, 'store'])->name('military-ticket-from-local-stock.store');
$router->get('/military-ticket-from-local-stock/{id}', [MilitaryTicketFromLocalStockController::class, 'show'])->name('military-ticket-from-local-stock.show');
$router->get('/military-ticket-from-local-stock/edit/{id}', [MilitaryTicketFromLocalStockController::class, 'edit'])->name('military-ticket-from-local-stock.edit');
$router->post('/military-ticket-from-local-stock/update/{id}', [MilitaryTicketFromLocalStockController::class, 'update'])->name('military-ticket-from-local-stock.update');
$router->get('/military-ticket-from-local-stock/delete/{id}', [MilitaryTicketFromLocalStockController::class, 'delete'])->name('military-ticket-from-local-stock.delete');


// CRUD для местных заправок
$router->get('/military-ticket-from-local-other', [MilitaryTicketFromLocalOtherController::class, 'index'])->name('military-ticket-from-local-other.index');
$router->get('/military-ticket-from-local-other/create', [MilitaryTicketFromLocalOtherController::class, 'create'])->name('military-ticket-from-local-other.create');
$router->post('/military-ticket-from-local-other/store', [MilitaryTicketFromLocalOtherController::class, 'store'])->name('military-ticket-from-local-other.store');
$router->get('/military-ticket-from-local-other/{id}', [MilitaryTicketFromLocalOtherController::class, 'show'])->name('military-ticket-from-local-other.show');
$router->get('/military-ticket-from-local-other/edit/{id}', [MilitaryTicketFromLocalOtherController::class, 'edit'])->name('military-ticket-from-local-other.edit');
$router->post('/military-ticket-from-local-other/update/{id}', [MilitaryTicketFromLocalOtherController::class, 'update'])->name('military-ticket-from-local-other.update');
$router->get('/military-ticket-from-local-other/delete/{id}', [MilitaryTicketFromLocalOtherController::class, 'delete'])->name('military-ticket-from-local-other.delete');

// Временные маршруты для заправок (ДО существующих маршрутов)
$router->post('/military-ticket/temp-add-fuel', [MilitaryTicketController::class, 'tempAddFuel']);
$router->get('/military-ticket/temp-get-fuel/{tempId}', [MilitaryTicketController::class, 'tempGetFuel']);
$router->delete('/military-ticket/temp-remove-fuel', [MilitaryTicketController::class, 'tempRemoveFuel']);
$router->post('/military-ticket/temp-add-fuel-other', [MilitaryTicketController::class, 'tempAddFuelOther']);
$router->get('/military-ticket/temp-get-fuel-other/{tempId}', [MilitaryTicketController::class, 'tempGetFuelOther']);
$router->delete('/military-ticket/temp-remove-fuel-other', [MilitaryTicketController::class, 'tempRemoveFuelOther']);
$router->post('/military-ticket/temp-add-fuel-places', [MilitaryTicketController::class, 'tempAddFuelPlaces']);
$router->get('/military-ticket/temp-get-fuel-places/{tempId}', [MilitaryTicketController::class, 'tempGetFuelPlaces']);
$router->delete('/military-ticket/temp-remove-fuel-places', [MilitaryTicketController::class, 'tempRemoveFuelPlaces']);

// CRUD для техники
$router->get('/military-machine', [MilitaryModelMachineController::class, 'index'])->name('military-machine.index');
$router->get('/military-machine/create', [MilitaryModelMachineController::class, 'create'])->name('military-machine.create');
$router->post('/military-machine/store', [MilitaryModelMachineController::class, 'store'])->name('military-machine.store');
$router->get('/military-machine/{id}', [MilitaryModelMachineController::class, 'show'])->name('military-machine.show');
$router->get('/military-machine/edit/{id}', [MilitaryModelMachineController::class, 'edit'])->name('military-machine.edit');
$router->post('/military-machine/update/{id}', [MilitaryModelMachineController::class, 'update'])->name('military-machine.update');
$router->get('/military-machine/delete/{id}', [MilitaryModelMachineController::class, 'delete'])->name('military-machine.delete');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpertSignalController;

// Simple test for ExpertSignalController without view
Route::get('/debug-controller-test', function () {
    try {
        $controller = new ExpertSignalController();
        $request = new \Illuminate\Http\Request();
        
        // Test if controller can run without issues
        $result = $controller->index($request);
        
        return "Controller executed successfully. Data keys: " . implode(', ', array_keys($result->getData()));
    } catch (Exception $e) {
        return "Controller error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine();
    }
});

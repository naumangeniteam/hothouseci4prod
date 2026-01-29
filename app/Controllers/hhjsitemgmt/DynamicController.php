<?php

namespace App\Controllers\hhjsitemgmt;

use App\Controllers\BaseController;
use Config\Services;
use CodeIgniter\Exceptions\PageNotFoundException;

class DynamicController extends BaseController
{
    public function index(...$segments)
    {
        // Extract controller, method, and params
        $controller = $segments[0] ?? null;
        $method     = $segments[1] ?? 'index';
        $params     = array_slice($segments, 2);

        if (!$controller) {
            throw PageNotFoundException::forPageNotFound();
        }

        // ✅ Build full class name, with ucfirst for safety
        $controllerClass = "App\\Controllers\\hhjsitemgmt\\" . ucfirst($controller);

        if (!class_exists($controllerClass)) {
            log_message('error', "Dynamic route: Controller '$controllerClass' not found.");
            throw PageNotFoundException::forPageNotFound();
        }

        // ✅ Create instance & call initController
        $instance = new $controllerClass();
        if (method_exists($instance, 'initController')) {
            $instance->initController(
                Services::request(),
                Services::response(),
                Services::logger()
            );
        }

        // ✅ Verify method exists
        if (!method_exists($instance, $method)) {
            log_message('error', "Dynamic route: Method '$method' not found in '$controllerClass'.");
            throw PageNotFoundException::forPageNotFound();
        }

        // ✅ Log for debugging
        log_message('debug', "Dynamic route: Calling {$controllerClass}::{$method} with params: " . json_encode($params));

        // ✅ Call method with params
        return call_user_func_array([$instance, $method], $params);
    }
}

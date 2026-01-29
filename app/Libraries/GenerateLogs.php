<?php

namespace App\Libraries;

use CodeIgniter\Router\RouteCollection;
use Config\Services;

class GenerateLogs
{
    private string $file;
    private string $prefix;
    private $router;

    public function __construct(array $parameter = [])
    {
        // Save logs to writable/logs (CI4 recommended)
        $logFolder = WRITEPATH . 'logs/';

        if (!is_dir($logFolder)) {
            mkdir($logFolder, 0777, true);
        }

        $type = $parameter['type'] ?? 'log_';
        $this->file   = $logFolder . $type . date("d_m_Y") . '.txt';
        $this->prefix = date("D M d Y h.i A") . " >> ";

        // Get router service
        $this->router = Services::router();
    }

    /**
     * Write a log entry
     */
    public function putLog(string $type = '', string $text = ''): bool
    {
        $class  = $this->router->controllerName();
        $method = $this->router->methodName();

        $logType = $type . ' - ' . $class . ' - ' . $method . ' >> ';

        // Write to file
        $logText = $this->prefix . $logType . $text . PHP_EOL . PHP_EOL;
        file_put_contents($this->file, $logText, FILE_APPEND);

        return true;
    }

    /**
     * Same as putLog but allows separate file handling if needed
     */
    public function putLogFiles(string $type = '', string $text = ''): bool
    {
        return $this->putLog($type, $text);
    }

    /**
     * Read log file
     */
    public function getLog(): string|false
    {
        return @file_get_contents($this->file);
    }

    /**
     * Convert seconds to human-readable format
     */
    public function secondtotimedate(int $ss): string
    {
        $s = $ss % 60;
        $m = floor(($ss % 3600) / 60);
        $h = floor(($ss % 86400) / 3600);
        $d = floor(($ss % 2592000) / 86400);
        $M = floor($ss / 2592000);

        if ($d > 0) {
            return "$d days, $h hours, $m minutes";
        }
        return "Bidding expired";
    }
}

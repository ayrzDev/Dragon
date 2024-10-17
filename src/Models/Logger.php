<?php

namespace App\Models;

use DateTime;

class Logger
{
    private $logFile;

    public function __construct($file = "system.log")
    {
        $this->logFile = $file;
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([])); // Başlangıçta boş bir JSON dizi yaz
        }
    }
    private function log($message, $level = 'INFO')
    {
        $date = new DateTime();
        $logEntry = [
            'timestamp' => $date->format('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message
        ];

        // Mevcut logları oku
        $logData = json_decode(file_get_contents($this->logFile), true);
        $logData[] = $logEntry; // Yeni logu ekle

        // Log dosyasını güncelle
        file_put_contents($this->logFile, json_encode($logData));
    }

    public function info($message)
    {
        $this->log($message, 'INFO');
    }

    public function warning($message)
    {
        $this->log($message, 'WARNING');
    }

    public function error($message)
    {
        $this->log($message, 'ERROR');
    }
}

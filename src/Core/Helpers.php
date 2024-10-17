<?php

namespace App\Core;

use App\Config\App;

class Helpers
{
    public static function view($page, $data = [], $url = false)
    {
        $root = $_SERVER["DOCUMENT_ROOT"];
        $themePath = ($root) . '/public/themes/' . App::template . '/';
        $pagePath =  $themePath . $page . '.php';
        if ($url) {
            $pagePath = $themePath . $page . '/index.php';
        }

        if (file_exists($pagePath)) {
            extract($data);
            require_once $pagePath;
        } else {
            if (is_dir($themePath)) {
                $errorsThemePath = $themePath . 'errors/';
                $errorPagePath = $errorsThemePath . $page . '.php';

                if (file_exists($errorPagePath)) {
                    extract($data);
                    require_once $errorPagePath;
                } else {
                    require_once($_SERVER["DOCUMENT_ROOT"] . '/public/errors/404.php');
                }
            } else {
                require_once(__DIR__ . '/../../public/errors/404.php');
            }
        }
    }

    public static function urlParser($url)
    {
        $decodedUrl = urldecode($url);
        $text = strtolower(htmlspecialchars($decodedUrl));
        $array = explode(" ", $text);
        $result = implode("-", $array);

        return $result;
    }

    public static function controllers()
    {
        $url = $_SERVER["DOCUMENT_ROOT"] . "/app/controllers/*.php";
        $files = glob($url);

        foreach ($files as $file) {
            echo $file . "<br />";
        }
    }

    public static function delete_image($filename, $path)
    {
        $uploadsDir = $_SERVER["DOCUMENT_ROOT"] . $path;
        $filePath = $uploadsDir . $filename;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                return ['success' => true, 'message' => 'File successfully deleted.'];
            } else {
                return ['success' => false, 'message' => 'Failed to delete file.'];
            }
        } else {
            return ['success' => false, 'message' => 'File does not exist.'];
        }
    }

    public static function image_upload($file)
    {
        if (isset($file['tmp_name']) && $file['tmp_name'] != '') {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $mimeType = mime_content_type($file['tmp_name']);

            if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
                return ['success' => false, 'message' => 'Invalid file type.'];
            }

            $uploadsDir = $_SERVER["DOCUMENT_ROOT"] . '/uploads/';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0775, true);
            }

            $name = uniqid() . '.' . $extension;
            $location = $uploadsDir . $name;

            if (move_uploaded_file($file['tmp_name'], $location)) {
                return ['success' => true, 'filename' => $name];
            } else {
                return ['success' => false, 'message' => 'File upload failed.'];
            }
        } else {
            return ['success' => false, 'message' => 'No file selected.'];
        }
    }

    public static function encryptText($text, $key)
    {
        $ivSize = openssl_cipher_iv_length('AES-256-CBC');
        $iv = openssl_random_pseudo_bytes($ivSize);
        $encrypted = openssl_encrypt($text, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        $encrypted = base64_encode($iv . $encrypted);
        return $encrypted;
    }

    public static function decryptText($encryptedText, $key)
    {
        $encryptedText = base64_decode($encryptedText);
        $ivSize = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($encryptedText, 0, $ivSize);
        $encrypted = substr($encryptedText, $ivSize);
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }
}

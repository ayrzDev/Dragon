<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/src/Config/config.php';
require_once AUTOLOAD_PATH;

use App\Models\UserModel;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode($_POST['data'], true);
    $userModel = new UserModel();
    // if (Security::validateCsrfToken($requestData["token"])) {
    $response = $userModel->login($requestData);
    header('Content-Type: application/json');
    echo json_encode($response);
    // }
}

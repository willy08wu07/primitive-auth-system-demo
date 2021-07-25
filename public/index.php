<?php

include_once '../DatabaseConfig.php';
include_once '../StandardTemplateView.php';
include_once '../LoginFormView.php';
include_once '../DashboardView.php';
include_once '../AuthService.php';

// 初始化
session_save_path('../storage/sessions');
session_start();
$config = new DatabaseConfig();
$connection = $config->newPdo();
$authService = new AuthService();

// 檢查使用者是否要求登入
if ($authService->isLoggingIn()) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $authService->logInOrRegister($connection, $username, $password);
    return;
}

// 檢查使用者是否要求登出
if ($authService->isLoggingOut()) {
    $authService->logOut();
    return;
}

// 檢查是否已登入
if ($authService->isLoggedIn()) {
    $authService->outputDashboard();
    return;
}

// 不符合任何上述條件，那就是首次進入網站
$authService->outputHomepage();

<?php
function redirect(string $action = 'dashboard'): void {
    header('Location: index.php?action=' . urlencode($action));
    exit;
}

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function old(string $key, string $default = ''): string {
    return e($_POST[$key] ?? $default);
}

function clientIp(): string {
    $keys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR',
    ];

    foreach ($keys as $key) {
        if (empty($_SERVER[$key])) {
            continue;
        }
        $value = trim((string)$_SERVER[$key]);
        if ($key === 'HTTP_X_FORWARDED_FOR') {
            $parts = explode(',', $value);
            $value = trim($parts[0]);
        }
        if ($value !== '') {
            return $value;
        }
    }

    return '0.0.0.0';
}

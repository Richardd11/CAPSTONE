<?php

namespace App\Config;

class App
{
    const APP_NAME = 'Examination System';
    const APP_VERSION = '2.0.0';
    const APP_ENV = 'development';
    const BASE_URL = 'http://localhost';
    const BASE_PATH = '/';
    const SESSION_LIFETIME = 3600;
    const SESSION_NAME = 'exam_system_session';
    const BCRYPT_COST = 12;
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOGIN_LOCKOUT_TIME = 900;
    const DEFAULT_PAGE_SIZE = 20;
    const MAX_PAGE_SIZE = 100;
    const MAX_FILE_SIZE = 5242880;
    const ALLOWED_FILE_TYPES = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
    const DEFAULT_EXAM_DURATION = 60;
    const MAX_EXAM_DURATION = 300;

    public static function get($key, $default = null)
    {
        return defined("self::$key") ? constant("self::$key") : $default;
    }

    public static function isDebug()
    {
        return self::APP_ENV === 'development';
    }

    public static function url($path = '')
    {
        return rtrim(self::BASE_URL . self::BASE_PATH, '/') . '/' . ltrim($path, '/');
    }
}


<?php
define('APP_NAME', 'Siddha Child Care');
define('APP_ROOT', dirname(__DIR__));
define('DB_PATH', APP_ROOT . '/data/siddha_child_care.sqlite');
define('OPENAI_API_KEY', getenv('OPENAI_API_KEY') ?: '');
define('OPENAI_MODEL', getenv('OPENAI_MODEL') ?: 'gpt-5-mini');
define('OPENAI_BASE_URL', rtrim(getenv('OPENAI_BASE_URL') ?: 'https://api.openai.com/v1', '/'));
date_default_timezone_set('Asia/Kolkata');
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

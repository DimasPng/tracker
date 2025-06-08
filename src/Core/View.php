<?php

namespace App\Core;

use App\Enum\UserRole;

class View
{
    private static string $viewPath = __DIR__ . '/../../resources/views';

    public static function render(string $view, array $data = [], ?string $layout = 'app'): string
    {
        extract($data);

        ob_start();
        $viewFile = self::$viewPath . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }

        include $viewFile;
        $content = ob_get_clean();

        if ($layout === null) {
            return $content;
        }

        $layoutFile = self::$viewPath . '/layouts/' . $layout . '.php';

        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout file not found: {$layoutFile}");
        }

        ob_start();
        include $layoutFile;

        return ob_get_clean();
    }

    public static function component(string $component, array $data = []): string
    {
        extract($data);

        ob_start();
        $componentFile = self::$viewPath . '/components/' . $component . '.php';

        if (!file_exists($componentFile)) {
            throw new \Exception("Component file not found: {$componentFile}");
        }

        include $componentFile;

        return ob_get_clean();
    }

    public static function auth(): ?array
    {
        return auth()->user();
    }

    public static function hasRole(UserRole $role): bool
    {
        return auth()->hasRole($role);
    }

    public static function e(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public static function csrfToken(): string
    {
        return app('csrf')->getToken();
    }

    public static function csrfField(): string
    {
        $token = self::csrfToken();
        $name = app('csrf')->getTokenName();

        return '<input type="hidden" name="' . self::e($name) . '" value="' . self::e($token) . '">';
    }
}

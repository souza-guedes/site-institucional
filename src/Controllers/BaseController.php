<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Response;

/**
 * Controlador Base fornecendo injeção de dados canônicos e renderização de views modulares.
 */
abstract class BaseController
{
    protected string $viewsPath;
    protected array $firmProfile;

    public function __construct()
    {
        $this->viewsPath = dirname(__DIR__) . '/Views';
        $firmProfilePath = dirname(__DIR__, 2) . '/data/firm-profile.json';
        if (file_exists($firmProfilePath)) {
            $decoded = json_decode((string) file_get_contents($firmProfilePath), true);
            $this->firmProfile = is_array($decoded) ? $decoded : [];
        } else {
            $this->firmProfile = [];
        }
    }

    protected function render(string $viewName, array $data = [], int $status = 200): Response
    {
        $viewFile = $this->viewsPath . '/' . ltrim($viewName, '/') . '.php';
        if (!file_exists($viewFile)) {
            return new Response("View '{$viewName}' não encontrada.", 500);
        }

        $firm = $this->firmProfile;
        extract($data);

        ob_start();
        include $viewFile;
        $content = (string) ob_get_clean();

        return new Response($content, $status);
    }
}

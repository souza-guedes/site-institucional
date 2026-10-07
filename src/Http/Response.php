<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Abstração imutável de resposta HTTP.
 */
final readonly class Response
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        public string $content,
        public int $statusCode = 200,
        public array $headers = ['Content-Type' => 'text/html; charset=UTF-8']
    ) {}

    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->statusCode);
            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}");
            }
        }

        echo $this->content;
    }
}

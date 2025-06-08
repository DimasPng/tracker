<?php

namespace App\Core\Error;

use App\Contract\ResponseFactoryInterface;
use App\Core\DI\Container;
use App\Core\Exception\HttpException;
use App\Core\Response;
use Throwable;

class ErrorHandler
{
    public function __construct(private readonly bool $debug = false, private readonly ?Container $container = null)
    {
    }

    public function register(): void
    {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleException(Throwable $exception): void
    {
        try {
            $this->logError($exception);
            $this->renderErrorResponse($exception);
        } catch (Throwable $e) {
            $this->renderFallbackError($e);
        }
    }

    public function handleError(int $level, string $message, string $file = '', int $line = 0): bool
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }

        return false;
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $exception = new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            );

            $this->handleException($exception);
        }
    }

    private function logError(Throwable $exception): void
    {
        $message = sprintf(
            "[%s] %s: %s in %s:%d\nStack trace:\n%s",
            date('Y-m-d H:i:s'),
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        error_log($message);
    }

    private function renderErrorResponse(Throwable $exception): void
    {
        $statusCode = $this->getHttpStatusCode($exception);

        if ($this->isAjaxRequest()) {
            $this->renderJsonError($exception, $statusCode);
        } else {
            $this->renderHtmlError($exception, $statusCode);
        }
    }

    private function getHttpStatusCode(Throwable $exception): int
    {
        return match (true) {
            $exception instanceof \InvalidArgumentException => 400,
            $exception instanceof HttpException => $exception->getStatusCode(),
            default => 500
        };
    }

    private function renderJsonError(Throwable $exception, int $statusCode): void
    {
        $response = [
            'error' => true,
            'message' => $this->debug ? $exception->getMessage() : 'Internal Server Error',
            'status' => $statusCode,
        ];

        if ($this->debug) {
            $response['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }

    private function renderHtmlError(Throwable $exception, int $statusCode): void
    {
        if ($this->container) {
            try {
                $response = $this->createStyledErrorResponse($exception, $statusCode);
                $response->send();

                return;
            } catch (Throwable $e) {
            }
        }

        http_response_code($statusCode);
        $this->renderSimpleErrorPage($exception, $statusCode);
    }

    private function createStyledErrorResponse(Throwable $exception, int $statusCode): Response
    {
        $title = $this->getErrorTitle($statusCode);
        $message = $this->debug ? $exception->getMessage() : 'Something went wrong. Please try again later.';

        $content = $this->renderErrorTemplate($title, $message, $statusCode, $exception);

        if ($this->container === null) {
            throw new \RuntimeException('ResponseFactory is not available');
        }

        return $this->container->get(ResponseFactoryInterface::class)->html($content, $statusCode);
    }

    private function renderErrorTemplate(string $title, string $message, int $statusCode, Throwable $exception): string
    {
        $debugInfo = '';
        if ($this->debug) {
            $debugInfo = "
                <div style='margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #dc3545;'>
                    <h3 style='color: #dc3545; margin-top: 0;'>🐛 Debug Information</h3>
                    <p><strong>Exception:</strong> " . htmlspecialchars(get_class($exception)) . '</p>
                    <p><strong>File:</strong> ' . htmlspecialchars($exception->getFile()) . ':' . $exception->getLine() . "</p>
                    <details style='margin-top: 15px;'>
                        <summary style='cursor: pointer; font-weight: bold;'>Stack Trace</summary>
                        <pre style='background: #fff; padding: 15px; border-radius: 4px; overflow-x: auto; margin-top: 10px;'>" . htmlspecialchars($exception->getTraceAsString()) . '</pre>
                    </details>
                </div>';
        }

        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$title} - Buy A Cow</title>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
                .error-container { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); max-width: 600px; width: 90%; text-align: center; }
                .error-icon { font-size: 4rem; margin-bottom: 20px; }
                h1 { color: #333; font-size: 2rem; margin-bottom: 10px; }
                .error-code { color: #666; font-size: 1.2rem; margin-bottom: 20px; }
                p { color: #666; line-height: 1.6; margin-bottom: 30px; }
                .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 8px; transition: all 0.3s; }
                .btn:hover { background: #5a6fd8; transform: translateY(-2px); }
                .debug { text-align: left; }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <div class='error-icon'>" . $this->getErrorEmoji($statusCode) . "</div>
                <h1>{$title}</h1>
                <div class='error-code'>Error {$statusCode}</div>
                <p>" . htmlspecialchars($message) . "</p>
                <a href='/' class='btn'>🏠 Go Home</a>
                <div class='debug'>{$debugInfo}</div>
            </div>
        </body>
        </html>";
    }

    private function renderSimpleErrorPage(Throwable $exception, int $statusCode): void
    {
        $title = $this->getErrorTitle($statusCode);
        $message = $this->debug ? $exception->getMessage() : 'Internal Server Error';

        echo "<!DOCTYPE html><html><head><title>{$title}</title></head><body>";
        echo "<h1>{$title}</h1>";
        echo '<p>' . htmlspecialchars($message) . '</p>';
        if ($this->debug) {
            echo '<pre>' . htmlspecialchars($exception->getTraceAsString()) . '</pre>';
        }
        echo '</body></html>';
        exit;
    }

    private function renderFallbackError(Throwable $exception): void
    {
        http_response_code(500);
        echo 'Critical Error: ' . $exception->getMessage();
        exit;
    }

    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function getErrorTitle(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Page Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
            default => 'Error'
        };
    }

    private function getErrorEmoji(int $statusCode): string
    {
        return match ($statusCode) {
            400 => '⚠️',
            401 => '🔒',
            403 => '🚫',
            404 => '🔍',
            405 => '❌',
            500 => '💥',
            default => '😵'
        };
    }
}

<?php

use React\EventLoop\Loop;

require __DIR__.'/vendor/autoload.php';

$socket_port = 8080;
$socket_host = '0.0.0.0';
$host = sprintf('%s:%s', $socket_host, $socket_port);

function renderView(string $view, array $data = []): string
{
    $result = function ($file, array $data = []) {
        ob_start();
        extract($data, EXTR_SKIP);
        try {
            include $file;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
        return ob_get_clean();
    };

    return $result($view, $data);
}

$handler = function (Psr\Http\Message\ServerRequestInterface $request) {
    $body = $request->getParsedBody() ?? [];

    return new React\Http\Message\Response(
        200,
        [
            'Content-Type' => 'text/html',
        ],
        renderView(__DIR__ . '/view/index.php', ['body' => $body])
    );
};

$server = new React\Http\Server(
    new React\Http\Middleware\StreamingRequestMiddleware(),
    new React\Http\Middleware\LimitConcurrentRequestsMiddleware(1),
    new React\Http\Middleware\RequestBodyBufferMiddleware(16 * 1024 * 1024), // 16 MiB
    new React\Http\Middleware\RequestBodyParserMiddleware(),
    $handler
);

$socket = new React\Socket\Server($host);
$server->listen($socket);

Loop::addSignal(SIGINT, $func = function ($signal) use (&$func, $socket) {
    echo sprintf('Signal: %s' . PHP_EOL, $signal);
    Loop::removeSignal(SIGINT, $func);
    $socket->close();
});

echo 'Listening for SIGINT. Use "kill -SIGINT ' . getmypid() . '" or CTRL+C' . PHP_EOL;

echo "Server running at ${host}".PHP_EOL;
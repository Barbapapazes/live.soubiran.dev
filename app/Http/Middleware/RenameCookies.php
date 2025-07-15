<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

use function array_key_exists;
use function tap;

class RenameCookies
{
    /**
     * @var array<string, string>
     */
    protected array $map = [
        'XSRF-TOKEN' => 'LIVE-XSRF-TOKEN',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        return tap($next($request), $this->renameResponseCookies(...));
    }

    private function renameResponseCookies(Response $response): void
    {
        $cookies = $response->headers->getCookies();

        foreach ($cookies as $cookie) {
            if (array_key_exists($name = $cookie->getName(), $this->map)) {
                $response->headers->removeCookie($name, $cookie->getPath(), $cookie->getDomain());
                $response->headers->setCookie($this->makeCookie($this->map[$name], $cookie));

                break;
            }
        }
    }

    private function makeCookie(string $name, Cookie $cookie): Cookie
    {
        return new Cookie(
            $name,
            $cookie->getValue(),
            $cookie->getExpiresTime(),
            $cookie->getPath(),
            $cookie->getDomain(),
            $cookie->isSecure(),
            $cookie->isHttpOnly(),
            $cookie->isRaw(),
            $cookie->getSameSite(),
            $cookie->isPartitioned(),
        );
    }
}

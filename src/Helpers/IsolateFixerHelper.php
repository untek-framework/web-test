<?php

namespace Untek\Framework\WebTest\Helpers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Untek\Component\Http\Helpers\SymfonyHttpResponseHelper;

class IsolateFixerHelper
{

    /**
     * Фикс недостающего session_id.
     *
     * Такая проблема проявляется только под CLI.
     *
     * @param Request $request
     */
    public static function fixBeforeHandle(Request $request): void
    {
        $clientSessId = $request->cookies->get('PHPSESSID');
        if ($clientSessId) {
            session_id($clientSessId);
        }
        SymfonyHttpResponseHelper::forgeServerVar($request);
    }

    /**
     * Фиск: добавление ID сесси в куки ответа.
     *
     * Такая проблема проявляется только под CLI.
     *
     * @param Request $request
     * @param Response $response
     */
    public static function fixAfterHandle(Request $request, Response $response): void
    {
        $clientSessId = $request->cookies->get('PHPSESSID');
        if (session_id() != $clientSessId) {
            $response->headers->set('Set-Cookie', "PHPSESSID=" . session_id() . "; path=/");
        }
    }
}

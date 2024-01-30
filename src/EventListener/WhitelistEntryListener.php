<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Repository\WhitelistEntryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WhitelistEntryListener implements EventSubscriberInterface
{
    private $whitelistRepository;

    public function __construct(WhitelistEntryRepository $whitelistRepository)
    {
        $this->whitelistRepository = $whitelistRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // Get the current request
        $request = $event->getRequest();

        // Check if the request is allowed based on whitelist entries
        if (!$this->isRequestAllowed($request)) {
            // If not allowed, create a forbidden response
            $response = new Response(sprintf('Access Denied for ip: %s', $request->getClientIp()), Response::HTTP_FORBIDDEN);

            $data = ['message' => sprintf('Access Denied for ip: %s', $request->getClientIp())];
            $response = new JsonResponse($data, JsonResponse::HTTP_FORBIDDEN);
            // Set the response to stop further processing
            $event->setResponse($response);
        }
    }

    /**
     * isRequestAllowed
     *
     * @param  mixed $request
     * @return bool
     */
    private function isRequestAllowed(Request $request): bool
    {
        $ipAddress = $request->getClientIp();

        return $this->whitelistRepository->isRequestAllowed($ipAddress);
    }
}

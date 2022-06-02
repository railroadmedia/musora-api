<?php

namespace Railroad\MusoraApi\Middleware;

use Closure;
use Illuminate\Http\Request;
use Railroad\MusoraApi\Contracts\UserProviderInterface;

class AddMemberData
{
    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    /**
     * AddMemberData constructor.
     *
     * @param UserProviderInterface $userProvider
     */
    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        //add current user's membership data(isEdge, edgeExpirationDate, ...) on the response
        if ($this->userProvider->getCurrentUser()) {
            $content = json_decode($response->content(), true);
            $response->setContent(
                json_encode(array_merge($content, $this->userProvider->getCurrentUserMembershipData($request->get('brand'))))
            );
        }
        return $response;
    }

    public function terminate($request, $response)
    {
        //sync user firebase token if exists on the request
        if ($request->has('firebase_token') && $request->has('platform')) {
            $this->userProvider->setCurrentUserFirebaseTokens(
                ($request->get('platform') == 'ios') ? $request->get('firebase_token') : null,
                ($request->get('platform') == 'android') ? $request->get('firebase_token') : null
            );
        }
    }
}

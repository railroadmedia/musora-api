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
                json_encode(array_merge($content, $this->userProvider->getCurrentUserMembershipData()))
            );
        }

        return $response;
    }
}
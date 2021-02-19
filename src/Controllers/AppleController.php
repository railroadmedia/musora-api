<?php

namespace Railroad\MusoraApi\Controllers;


use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Railroad\Ecommerce\Contracts\UserProviderInterface;
use Railroad\Ecommerce\Entities\AppleReceipt;
use Railroad\Ecommerce\Events\AppSignupFinishedEvent;
use Railroad\Ecommerce\Repositories\AppleReceiptRepository;
use Railroad\Ecommerce\Services\AppleStoreKitService;

class AppleController extends Controller
{
    /**
     * @var AppleReceiptRepository
     */
    private $appleReceiptRepository;

    /**
     * @var AppleStoreKitService
     */
    private $appleStoreKitService;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * AppleController constructor.
     *
     * @param AppleReceiptRepository $appleReceiptRepository
     * @param AppleStoreKitService $appleStoreKitService
     * @param UserProviderInterface $userProvider
     */
    public function __construct(
        AppleReceiptRepository $appleReceiptRepository,
        AppleStoreKitService $appleStoreKitService,
        UserProviderInterface $userProvider
    ) {
        $this->appleReceiptRepository = $appleReceiptRepository;
        $this->appleStoreKitService = $appleStoreKitService;
        $this->userProvider = $userProvider;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Railroad\Ecommerce\Exceptions\ReceiptValidationException
     * @throws \Throwable
     */
    public function restorePurchase(Request $request)
    {
        $receipt = $request->get('receipt', []);

        if (empty($receipt)) {
            return response()->json(
                [
                    'message' => 'No receipt on the request',
                ],
                500
            );
        }

        $results = $this->appleStoreKitService->restoreAndSyncPurchasedItems($receipt);

        if (!$results) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'No valid purchased items in Apple response',
                ],
                200
            );
        }

        if ($results['shouldCreateAccount'] == true) {

            auth()->logout();

            return response()->json(
                [
                    'shouldCreateAccount' => true,
                ]
            );

        } elseif ($results['shouldLogin'] == true) {

            auth()->logout();

            return response()->json(
                [
                    'shouldLogin' => true,
                    'email' => $results['receiptUser']->getEmail(),
                ]
            );
        }

        $user = $results['receiptUser'] ?? auth()->user();
        $userAuthToken = $this->userProvider->getUserAuthToken($user);

        return response()->json(
            [
                'success' => true,
                'token' => $userAuthToken,
                'isEdge' => UserAccessService::isEdge($user->getId()),
                'isEdgeExpired' => UserAccessService::isEdgeExpired($user->getId()),
                'edgeExpirationDate' => UserAccessService::getEdgeExpirationDate($user->getId()),
                'isPackOwner' => UserAccessService::isPackOwner($user->getId()),
                'tokenType' => 'bearer',
                'expiresIn' => auth('api')
                        ->factory()
                        ->getTTL() * 60,
                'userId' => $user->getId(),
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function signup(Request $request)
    {
        $action = $this->appleStoreKitService->checkSignup($request->get('receipt'));

        switch ($action) {

            case AppleStoreKitService::SHOULD_RENEW:
                return response()->json(
                    [
                        'shouldRenew' => true,
                        'message' => 'You can not create multiple Drumeo accounts under the same apple account. You already have an expired/cancelled membership. Please renew your membership.',
                    ]
                );
            case AppleStoreKitService::SHOULD_LOGIN:
                return response()->json(
                    [
                        'shouldLogin' => true,
                        'message' => 'You have an active Drumeo account. Please login into your account. If you want to modify your payment plan please cancel your active subscription from device settings before.',
                    ]
                );
            default:
                return response()->json(
                    [
                        'shouldSignup' => true,
                    ]
                );
        }

    }
}
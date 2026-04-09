<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\AppUser;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(Request $request): View
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired portal link.');
        }

        $app = App::findOrFail($request->query('app_id'));
        $appUser = AppUser::firstOrCreate(
            ['app_id' => $app->id, 'external_user_id' => $request->query('external_user_id')],
            ['credit_balance' => 0],
        );

        $packagesParam = $request->query('packages');
        $packages = $packagesParam ? json_decode($packagesParam, true) : $app->getDefaultCreditPackages();

        $logs = $appUser->usageLogs()
            ->with('appModel')
            ->latest()
            ->take(20)
            ->get();

        $lastTopUp = $appUser->creditTransactions()
            ->where('type', 'purchase')
            ->latest()
            ->first();

        return view('portal.index', [
            'app' => $app,
            'appUser' => $appUser,
            'packages' => $packages,
            'logs' => $logs,
            'lastTopUp' => $lastTopUp,
            'theme' => $request->query('theme', 'dark'),
            'returnUrl' => $request->query('return_url'),
            'signatureParams' => $request->query(),
        ]);
    }

    public function checkout(Request $request, StripeService $stripeService)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired portal link.');
        }

        $app = App::findOrFail($request->query('app_id'));
        $appUser = AppUser::where('app_id', $app->id)
            ->where('external_user_id', $request->query('external_user_id'))
            ->firstOrFail();

        $packagesParam = $request->query('packages');
        $packages = $packagesParam ? json_decode($packagesParam, true) : $app->getDefaultCreditPackages();

        $packageIndex = $request->input('package_index', 0);
        if (! isset($packages[$packageIndex])) {
            abort(400, 'Invalid package.');
        }

        [$credits, $priceInCents] = $packages[$packageIndex];

        $url = $stripeService->createCheckoutSessionForPortal(
            $app,
            $appUser,
            $credits,
            $priceInCents,
            $request->fullUrl(),
        );

        return redirect($url);
    }
}

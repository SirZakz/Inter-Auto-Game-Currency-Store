<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
use App\Services\RconService;
use App\Models\WebsiteShopArticles;

class PaymentController extends Controller
{
    private $stripe;

    public function __construct() {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function __invoke(Request $request) {
        return view('shop.payment');
    }
    
    private function calculateOrderAmount(array $items): int {
        $total = 0;

        foreach($items as $item) {
            $total += intval($item['cost']) * 100;
        }

        return $total;
    }

    
    
    public function paymentSuccess(Request $request) {
        $user = Auth::user();
        $rcon = app(RconService::class);
        $items = session()->get('items');
    
        // Add logging to check if RCON is connected
        if (!$rcon->isConnected) {
            \Log::error('RCON connection failed on paymentSuccess for user ID: ' . $user->id);
            return response()->json(['error' => 'RCON connection failed'], 500);
        }
    
        // If user is online, disconnect them
        if ($user->online && $rcon->isConnected) {
            \Log::info('Disconnecting user ' . $user->id . ' from the server for payment processing.');
            $rcon->disconnectUser($user);
            sleep(1);
        }
    
        // Process each item and update user points
        foreach ($items as $item) {
            $article = WebsiteShopArticles::where('product_id', $item['id'])->first();
    
            if ($article) {
                // Add casting to int to ensure valid data is passed to RCON
                $rcon->giveCredits($user, (int) $article->credits);
                $rcon->giveDuckets($user, (int) $article->duckets);
                $rcon->giveDiamonds($user, (int) $article->diamonds);
    
                \Log::info('Updated user ' . $user->id . ' with credits: ' . $article->credits . ', duckets: ' . $article->duckets . ', diamonds: ' . $article->diamonds);
            } else {
                \Log::error('Article not found for item ID: ' . $item['id']);
            }
        }
    
        session()->forget('items');
    
        return view('shop.payment-success');
    }
    

    public function proceedPayment(Request $request) {
        $data = $request->validate([
            'items' => ['required'],
        ]);

        $items = $data['items'];
        session()->put('items', $items);

        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $this->calculateOrderAmount($items),
            'currency' => 'myr',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }
}


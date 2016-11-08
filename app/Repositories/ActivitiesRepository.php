<?php namespace App\Repositories;

use App\Activities;
use App\Interfaces\ActivitiesInterface as ActivitiesContract;
use App\User;
use Illuminate\Contracts\Events\Dispatcher;

class ActivitiesRepository implements ActivitiesContract {

    /**
     * @var Dispatcher
     */
    private $event;

    /**
     * @param Dispatcher $event
     */
    public function __construct(Dispatcher $event)
    {
        $this->event = $event;
    }

    public function createActivity($options)
    {
        $activities = Activities::create($options);

        return ($activities) ? true : false;
    }

    public function addDefaultSignupWalletCredit($user_id)
    {
        $user = User::find($user_id);
        $new_wallet_points = (int) $user->wallet_points + config('wallet.default_signup_credit');
        $user->wallet_points = $new_wallet_points;
        $user->save();

        return $user;
    }

    public function removeWalletCreditAfterPosting($user_id)
    {
        $user = User::find($user_id);
        $new_wallet_points = (int) $user->wallet_points - config('wallet.ad_posting_charge');
        $user->wallet_points = $new_wallet_points;
        $user->save();

        return $user;
    }

    public function addRefferalSignupCredit($referral_code)
    {
        $referral = User::whereEmail($referral_code->send_to)->first();
        $user = User::whereEmail($referral_code->send_from)->first();

        // adding credit to referral account & history of credit
        $referral->wallet_points = $referral->wallet_points + config('wallet.referral_signup_credit');
        $referral->save();

        WalletHistory::create([
            'user_id'     => $referral->id,
            'description' => $user->name . ' joined by ' . $referral->name . ' referral code.',
            'type'        => 'credit',
            'amount'      => $referral->wallet_points + config('wallet.referral_signup_credit')
        ]);
        // adding credit to referral account & history of credit

        // adding credit to user account & history of credit
        $user->wallet_points = $user->wallet_points + config('wallet.referral_signup_credit');
        $user->save();

        WalletHistory::create([
            'user_id'     => $user->id,
            'description' => 'joined using ' . $referral->name . ' referral code.',
            'type'        => 'credit',
            'amount'      => $user->wallet_points + config('wallet.referral_signup_credit')
        ]);
        // adding credit to user account & history of credit

        // set code status to used
        $referral_code->used = 1;
        $referral_code->save();
    }
}
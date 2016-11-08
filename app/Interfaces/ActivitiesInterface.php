<?php namespace App\Interfaces;

interface ActivitiesInterface {

    public function createActivity($options);

    public function addDefaultSignupWalletCredit($user_id);

    public function removeWalletCreditAfterPosting($user_id);

    public function addRefferalSignupCredit($referral_code);
}
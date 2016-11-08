<?php namespace App\Interfaces;

interface AccountInterface {

    public function addUserDetails($request);

    public function processLogin($user);
}
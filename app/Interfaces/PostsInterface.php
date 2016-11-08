<?php namespace App\Interfaces;

interface PostsInterface
{
    public function postManager($data, $user);

    public function updateWallet($activity, $username);
}
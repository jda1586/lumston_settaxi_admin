<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class AdminAccount extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'admin_account';

    public static function login($username, $password)
    {
        $account = static::where('username', $username)->first();

        if ($account != null && $account->password == md5($password))
        {
            return $account;
        }
    }

    public static function getCurrent()
    {
        return static::find(Session::get('admin_account_id'));
    }

}

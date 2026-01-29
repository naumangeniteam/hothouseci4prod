<?php

namespace App\Controllers;

class Home_its extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
}

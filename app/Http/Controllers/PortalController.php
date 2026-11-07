<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function __invoke(): View
    {
        return view('auth.login', ['titulo' => 'Portal']);
    }

}

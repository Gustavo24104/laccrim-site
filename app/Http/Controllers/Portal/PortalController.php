<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;


class PortalController extends Controller
{
    public function __invoke(): View
    {
        return view('paginas.portal.auth.login', ['titulo' => 'Portal']);
    }
}

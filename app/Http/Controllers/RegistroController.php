<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
class RegistroController extends Controller
{
    public function __invoke(): View
    {
        return view('auth.register', ['titulo' => 'Registro']);
    }
}

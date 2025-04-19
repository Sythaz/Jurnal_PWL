<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // $breadcrumb = (object) [
        //     'title' => 'Satu hari, satu cerita, satu jejak kehidupan.',
        //     'list' => ['Home', 'Welcome']
        // ];

        $activeMenu = 'dashboard';
        return view('welcome', [
            'activeMenu' => $activeMenu,
            // 'breadcrumb' => $breadcrumb,
        ]);
    }
}

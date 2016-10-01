<?php
namespace App\Http\Controllers;

class DefaultController extends Controller
{
    /**
     * @Get("/", as="_index")
     * @Middleware("auth")
     *
     * @return View
     */
    public function index()
    {
        return view('app');
    }
}

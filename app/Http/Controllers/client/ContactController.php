<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    private $PAGE_TITLE = "Liên hệ";

    public function index()
    {
        return view('client.contact.index')->with([
            'title' => $this->PAGE_TITLE
        ]);
    }
}

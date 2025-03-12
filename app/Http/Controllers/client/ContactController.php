<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

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

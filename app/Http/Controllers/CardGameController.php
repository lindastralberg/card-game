<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CardGameController extends Controller
{
    public function getNewDeck()
    {
        return Http::get('https://deckofcardsapi.com/api/deck/new/');
    }
}

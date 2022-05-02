<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CardGameController extends Controller
{
    public function getNewDeck()
    {
        return Http::get('https://deckofcardsapi.com/api/deck/new/shuffle/?deck_count=1');
    }

    public function drawCard($deck_id)
    {
        return Http::get('https://deckofcardsapi.com/api/deck/' . $deck_id . '/draw/?count=1');
    }
}

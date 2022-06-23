<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\Timer;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function liste()
    {
        $tickets = Ticket::all();

        //dd($tickets);

        return view('tickets', [
            'tickets' => $tickets,
        ]);
    }
}

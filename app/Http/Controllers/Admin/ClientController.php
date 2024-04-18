<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Get all client
     * @return Client
     */
    public function getAllClients()
    {
        return Client::all();
    }
}

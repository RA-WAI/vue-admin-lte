<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Get all client
     * @param mixed $search
     * @return Paginator
     */
    public function getClientOptions(Request $request): Paginator
    {
        return Client::orderByDesc("id")->paginate(20)
            ->through(fn ($client) => [
                'id' => $client->id,
                'full_name' => $client->full_name,
            ]);
    }
}

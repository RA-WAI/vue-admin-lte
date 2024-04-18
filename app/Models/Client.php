<?php

namespace App\Models;

use App\Models\Attributes\ClientAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory, ClientAttribute;
}

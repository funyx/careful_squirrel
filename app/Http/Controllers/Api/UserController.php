<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $query = User::select('name', 'id');
        $searchName = trim($request->query('query', ''));
        if($searchName && $searchName !== '') {
            $query = $query->where(DB::raw('lower(name)'), 'like', '%' . strtolower($searchName) . '%');
        }
        return $query->take(10)->get();
    }
}

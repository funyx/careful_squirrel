<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationData;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index( Request $request ): LengthAwarePaginator
    {
        $user = $request->user();

        return Message::with('sender', 'recipient')->where('sender_id', $user->getAuthIdentifier())->orWhere('recipient_id', $user->getAuthIdentifier())->orWhereNull('recipient_id')->orderByDesc('created_at')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store( Request $request )
    {
        $input = $request->all();
        $input['sender_id'] = $request->user()->getAuthIdentifier();

        $validator = Validator::make($input, [
            'sender_id'    => [
                'required',
                'exists:users,id',
            ],
            'recipient_id' => [
                'nullable',
                'exists:users,id',
            ],
            'content'      => [
                'required',
                'filled',
            ],
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 400);
        }

        return Message::with('sender', 'recipient')->create($input);
    }
}

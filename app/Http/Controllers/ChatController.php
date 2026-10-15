<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ChatRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $chats = Chat::paginate();

        return view('chat.index', compact('chats'))
            ->with('i', ($request->input('page', 1) - 1) * $chats->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $chat = new Chat();

        return view('chat.create', compact('chat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChatRequest $request): RedirectResponse
    {
        Chat::create($request->validated());

        return Redirect::route('chats.index')
            ->with('success', 'Chat created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $chat = Chat::find($id);

        return view('chat.show', compact('chat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $chat = Chat::find($id);

        return view('chat.edit', compact('chat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChatRequest $request, Chat $chat): RedirectResponse
    {
        $chat->update($request->validated());

        return Redirect::route('chats.index')
            ->with('success', 'Chat updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Chat::find($id)->delete();

        return Redirect::route('chats.index')
            ->with('success', 'Chat deleted successfully');
    }
}

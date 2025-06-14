<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;

class TicketController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }
    // List all tickets for the current user
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(10);
        return view('user.tickets.index', compact('tickets'));
    }

    // Show form to create a new ticket
    public function create()
    {
        return view('user.tickets.create');
    }

    // Store a new ticket
    public function store(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id'     => auth()->id(),
            'subject'     => $request->subject,
            'description' => $request->description,
            'status'      => 'open',
            'ticket_id'   => Ticket::generateTicketID(),
        ]);

        return redirect()->route('user.tickets.index')->with('success', 'Ticket created successfully.');
    }

    // Show ticket thread with replies
    public function show($ticketId)
    {
        $ticket = Ticket::where('user_id', auth()->id())
                        ->where('ticket_id', $ticketId)
                        ->firstOrFail();
        $replies = $ticket->replies()->orderBy('created_at')->get();
        return view('user.tickets.show', compact('ticket', 'replies'));
    }

    // Append a reply to a ticket
    public function reply(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        $attachments = [];
        
       // Handle attachments
        if($request->hasFile('attachments')) {
            foreach($request->file('attachments') as $file) {
            // Upload image
            $attachments[] = Helpers::upload('tickets/', config('fileformats.image'), $file);  
            }  
        }

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'user',
            'message' => $request->message,
            'attachments' => !empty($attachments) ? json_encode($attachments) : null
        ]);

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }
}

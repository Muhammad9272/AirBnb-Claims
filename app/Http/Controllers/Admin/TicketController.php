<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\CentralLogics\Helpers;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        // Optionally, add permission middleware (e.g., 'permissions:support')
    }

    public function datatables()
    {   
        $datas = Ticket::with('user')->orderBy('id', 'desc')->get();
        
        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('ticket_id', function(Ticket $data) {
                return '<span class="fw-semibold">'.$data->ticket_id.'</span>';
            })
            ->addColumn('subject', function(Ticket $data) {
                return '<div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="fs-14 mb-1">'.Str::limit($data->subject, 50).'</h5>
                                <div class="text-muted">'.Str::limit($data->description, 60).'</div>
                            </div>
                        </div>';
            })
            ->addColumn('user', function(Ticket $data) {
                return '<div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm bg-light rounded p-1">
                                    <img src="'.Helpers::image($data->user->photo, 'user/avatar/','user.png').'" alt="" class="img-fluid d-block rounded-circle">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-14 mb-1">'.$data->user->name.'</h5>
                                <div class="text-muted">'.$data->user->email.'</div>
                            </div>
                        </div>';
            })

            ->addColumn('updated_at', function(Ticket $data) {
                return '<div class="text-muted">
                            '.$data->updated_at->format('M d, Y H:i').'
                            <div class="text-muted small">'.$data->updated_at->diffForHumans().'</div>
                        </div>';
            })
            ->addColumn('action', function(Ticket $data) {
                return '<div class="d-flex gap-2">
                            <a href="'.route('admin.tickets.show', $data->ticket_id).'" class="btn btn-soft-primary btn-sm">
                                <i class="ri-eye-fill align-bottom me-1"></i> View
                            </a>
                        </div>';
            })
            ->rawColumns(['ticket_id', 'subject', 'user', 'status', 'updated_at', 'action'])
            ->toJson();
    }

    // List all tickets (can be enhanced with DataTables)
    public function index()
    {
        $tickets = Ticket::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.tickets.index', compact('tickets'));
    }

    // Show ticket details with the thread
    public function show($ticketId)
    {
        $ticket = Ticket::where('ticket_id', $ticketId)->firstOrFail();
        $replies = $ticket->replies()->orderBy('created_at')->get();
        return view('admin.tickets.show', compact('ticket', 'replies'));
    }

    // Admin posts a reply to a ticket
    public function reply(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        $attachments = [];
        
        //dd($request->file('attachments'));
        // Handle attachments
        if($request->hasFile('attachments')) {
            foreach($request->file('attachments') as $file) {
               // Upload image
               $attachments[] = Helpers::upload('tickets/', config('fileformats.image'), $file);  
            }  
        }


        TicketReply::create([
            'ticket_id' => $ticket->id,
            'sender_id' => auth('admin')->id(),
            'sender_type' => 'admin',
            'message' => $request->message,
            'attachments' => !empty($attachments) ? json_encode($attachments) : null
        ]);

        return redirect()->back()->with('success', 'Reply added successfully.');
    }

    // Update ticket status
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:open,pending,in-progress,resolved,closed',
        ]);

        $ticket->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket status updated successfully',
            'redirect_url' => route('admin.tickets.show', $ticket->ticket_id)
        ]);
    }


}

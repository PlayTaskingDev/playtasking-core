<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OcrTicket;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private function resolveModel($model)
    {
        return match (Str::lower($model)) {
            'ticket'     => \App\Models\Ticket::class,
            'ocr_ticket'  => \App\Models\OcrTicket::class,
            default      => abort(404, 'Unknown model type'),
        };
    }

    public function index()
    {
        $tickets = Ticket::all();
        $ocrTickets = OcrTicket::all();

        $all_tickets = $tickets->merge($ocrTickets)->sortByDesc('created_at');

        // Manual pagination setup
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $currentItems = $all_tickets->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $currentItems,
            $all_tickets->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.tickets.index', [
            'title'         => 'Panel | ' . trans('Tickets'),
            'description'   => 'Admin Panel',
            'tickets'       => $paginated,
        ]);
    }

    public function show($model, $id)
    {
        $class = $this->resolveModel($model);
        $ticket = $class::findOrFail($id);

        return view('admin.tickets.show', [
            'title'         => 'Panel | ' . trans('Tickets'),
            'description'   => 'Admin Panel',
            'ticket'        => $ticket->load('user','campaign'),
        ]);
    }

    public function destroy($model, $id)
    {
        $class = $this->resolveModel($model);
        $record = $class::findOrFail($id);
        $record->delete();
        
        return redirect()->route('admin.tickets.index', ['tenant' => tenant('id')])->with('success', 'Ticket deleted successfully.');
    }
}

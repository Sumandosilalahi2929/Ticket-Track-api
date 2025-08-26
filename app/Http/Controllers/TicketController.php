<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TicketResource;
use App\Http\Requests\TicketStoreRequest;

class TicketController extends Controller
{
    public function store(TicketStoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $ticket = new Ticket();
            $ticket->user_id = Auth::user()->id;
            $ticket->code = 'TIC-' . rand(10000, 99999);
            $ticket->title = $data['title'];
            $ticket->description = $data['description'];
            $ticket->priority = $data['priority'];
            $ticket->save();

            DB::commit();

            return response()->json([
                'message' => 'Tiket berhasil ditambahkan',
                'data' => new TicketResource($ticket)
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat tiket',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getAll()
    {
        try {
            $tickets = Ticket::all();
            return response()->json([
                'message' => 'Daftar tiket berhasil diambil',
                'data' => TicketResource::collection($tickets)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal mengambil daftar tiket',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $ticket = Ticket::find($id);

            if (!$ticket) {
                return response()->json([
                    'message' => 'Tiket tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'message' => 'Detail tiket berhasil diambil',
                'data' => new TicketResource($ticket)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal mengambil detail tiket',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $ticket = Ticket::find($id);

            if (!$ticket) {
                return response()->json([
                    'message' => 'Tiket tidak ditemukan'
                ], 404);
            }

            $ticket->update($request->all());

            DB::commit();

            return response()->json([
                'message' => 'Tiket berhasil diperbarui',
                'data' => new TicketResource($ticket)
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memperbarui tiket',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $ticket = Ticket::find($id);

            if (!$ticket) {
                return response()->json([
                    'message' => 'Tiket tidak ditemukan'
                ], 404);
            }

            $ticket->delete();

            DB::commit();

            return response()->json([
                'message' => 'Tiket berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus tiket',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}

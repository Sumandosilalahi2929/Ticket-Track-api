<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'completed_at' => $this->completed_at,
            'ticket_replies' => $this->replies->map(function ($reply) {
                return [
                    'id' => $reply->id,
                    'user' => $reply->user,
                    'content' => $reply->content,
                    'created_at' => $reply->created_at,
                ];
            }),
        ];
    }
}

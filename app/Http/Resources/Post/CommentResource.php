<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name = isset($this->parent) ? $this->parent->user->name : null;
        return[
            'id' => $this->id,
            'body' => $this->body,
            'date' => $this->date,
            'answered_for_user' => $name,
            'user' => new UserResource($this->user),

        ];
    }
}

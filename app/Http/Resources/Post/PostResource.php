<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $url = isset($this->image) ? $this->image->url : null;
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image_url' => $url,
            'date' => $this->created_at->diffForHumans(),
            'is_liked' => $this->is_liked ?? false,
            'likes_count' => $this->likedUsers->count(),
            'user' => new UserResource($this->user),
            'reposted_post' => new RepostedPostResource($this->repostedPost),
            'comments_count' => $this->comments_count,
            'reposted_by_post_count' => $this->reposted_by_post_count,
        ];
    }
}

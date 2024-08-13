<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostImage\StoreRequest;
use App\Http\Requests\UserImage\AvatarRequest;
use App\Http\Resources\PostImage\PostImageResource;
use App\Http\Resources\User\UserAvatarResource;
use App\Models\PostImage;
use App\Models\UserAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserAvatarController extends Controller
{
    public function store(AvatarRequest $request)
    {
        $path = Storage::disk('public')->putFile('/avatars', $request->file('avatar'));
        $image = UserAvatar::create([
           'path' => $path,
           'user_id' => auth()->id(),
        ]);
        return new UserAvatarResource($image);

    }
}

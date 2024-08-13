<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\User\StatRequest;
use App\Http\Requests\User\StoreRequestAvatar;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\LikedPost;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\SubscriberFollowing;
use App\Models\User;
use App\Models\UserAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Termwind\renderUsing;

class UserController extends Controller
{
    private function markLikedPosts($posts)
    {
        $likedPostIds = LikedPost::where('user_id', auth()->id())
            ->get('post_id')->pluck('post_id')->toArray();

        foreach ($posts as $post) {
            if (in_array($post->id, $likedPostIds)) {
                $post->is_liked = true;
            }
        }

        return $posts;
    }


    public function index()
    {
        $users = User::whereNot('id', auth()->id())->get();

        $followingIds = SubscriberFollowing::where('subscriber_id', auth()->id())
            ->get('following_id')->pluck('following_id')->toArray();

        foreach ($users as $user) {
            if (in_array($user->id, $followingIds)) {
                $user->is_followed = true;
            }
        }

        return UserResource::collection($users);
    }

    public function indexUser()
    {
        $user = Auth::user();

        if ($user) {
            // Возвращаем ID пользователя
            return response()->json(['id' => $user->id]);
        } else {
            // Если пользователь не аутентифицирован, возвращаем ошибку
            return response()->json(['error' => 'Unauthorized'], 401);
        }


    }

    public function storeAvatar(StoreRequestAvatar $request)
    {
        $data = $request->validated();
        $avatarId = $data['avatar_id'];
        unset($data['avatar_id']);

        try {
            DB::beginTransaction();

            $this->processImage($avatarId);
            UserAvatar::clearStorage();

            DB::commit();
        }catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()]);
        }

        return response()->json(['message' => 'Avatar updated successfully']);


    }


    private function processImage($avatarId)
    {

        if (isset($avatarId)){
            $userId = auth()->id();

            $currentAvatar = UserAvatar::where('user_id', $userId)->where('status', true)->first();
            if($currentAvatar){
                $currentAvatar->update([
                    'status' => false
                ]);
            }


            $avatar = UserAvatar::find($avatarId);
            if ($avatar){
                $avatar->update([
                    'status' => true,
                    'user_id' => auth()->id()
                ]);
            }

        }
    }





    public function indexProfile()
    {
        $profile = User::with('avatar')->where('id', auth()->id())->firstOrFail();



        return new UserResource($profile);
    }

    public function post(User $user)
    {

        $user = new UserResource($user);

        $posts = $user->posts()->withCount('repostedByPost')->latest()->get();

        $posts = $this->markLikedPosts($posts);

        return response()->json([
            'user' => $user,
            'posts' => PostResource::collection($posts),
        ]);

    }

    public function toggleFollowing(User $user)
    {

        $res = auth()->user()->followings()->toggle($user->id);

        $data['is_followed'] = count($res['attached']) > 0;

        return $data;

    }

    public function followingPost()
    {
        $followedIds = auth()->user()->followings()->latest()->get()->pluck('id')->toArray();

        $likedPostIds = LikedPost::where('user_id', auth()->id())
            ->get('post_id')->pluck('post_id')->toArray();

        $posts = Post::whereIn('user_id', $followedIds)->withCount('repostedByPost')
            ->whereNotIn('id', $likedPostIds)->get();




        return PostResource::collection($posts);
    }

    public function stat(StatRequest $request)
    {
        $data = $request->validated();

        $userId = isset($data['user_id']) ? $data['user_id'] : auth()->id();

        $result = [];
        $result['subscribers_count'] = SubscriberFollowing::where('following_id', $userId)->count();
        $result['followings_count'] = SubscriberFollowing::where('subscriber_id', $userId)->count();



        $postIds = Post::where('user_id', $userId)->get('id')->pluck('id')->toArray();

        $result['likes_count'] = LikedPost::whereIn('post_id', $postIds)->count();

        $result['posts_count'] = count($postIds);

        return response()->json(['data' => $result]);

    }




}

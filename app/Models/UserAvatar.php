<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserAvatar extends Model
{
    use HasFactory;

    protected $table = 'user_avatars';
    protected $guarded = false;

    public function getUrlAttribute()
    {
        return url('storage/' . $this->path);


    }

    public static function clearStorage()
    {
        $userId = auth()->id();

        $activeAvatar = UserAvatar::where('user_id', $userId)->where('status', 1)->first();

        $avatars = UserAvatar::where('user_id', $userId)->get();


        foreach ($avatars as $avatar) {
            // Удаляем файл аватара с диска
            if ($activeAvatar && $avatar->id == $activeAvatar->id) {
                continue;
            }

            if (Storage::disk('public')->exists($avatar->path)) {
                Storage::disk('public')->delete($avatar->path);
            }

            // Удаляем запись из базы данных
            $avatar->delete();
        }
    }
}

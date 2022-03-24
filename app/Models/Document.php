<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ReviewNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $with = ['author'];

    protected $fillable = ['author_id', 'approver_id', 'data', 'type', 'status', 'uuid'];

    public function author() : Relation
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function approver() : Relation
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($document) {
            $users = User::where('id', '!=', $document->author_id)->get();
            Notification::send($users, new ReviewNotification($document));
        });
    }
}

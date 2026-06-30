<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sqids\Sqids;

class Link extends Model
{
    use HasFactory;

    const int CODE_MIN_LENGTH = 6;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function booted(): void
    {
        static::created(function (self $link) {
            if (empty($link->code)) {
                $sqids = new Sqids(minLength: self::CODE_MIN_LENGTH);

                $link->updateQuietly([
                    'code' => $sqids->encode([$link->id]),
                ]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(LinkVisit::class);
    }
}

<?php

namespace App\Models;

use App\Components\Decoder\DecoderInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $hits
 * @property string $url
 * @property \Carbon\Carbon $expires_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 */
class Url extends Model
{
    use HasFactory;

    protected $table = 'urls';

    protected $fillable = ['url', 'expires_at'];

    protected $dates = ['expires_at'];

    protected $hidden = ['deleted_at'];

    protected $appends = ['code'];

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * @return string|null
     */
    public function getCodeAttribute(): ?string
    {
        if ($this->id) {
            return app(DecoderInterface::class)->encode($this->id);
        }

        return null;
    }
}

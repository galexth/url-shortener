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
 *
 * magic
 *
 * @property string $short_code
 * @property string $short_link
 */
class Url extends Model
{
    use HasFactory;

    protected $table = 'urls';

    protected $fillable = ['url', 'expires_at'];

    protected $dates = ['expires_at'];

    protected $hidden = ['deleted_at'];

    protected $appends = ['short_code', 'short_link'];

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getCode()
    {
        return $this->id ? app(DecoderInterface::class)->encode($this->id) : null;
    }

    /**
     * @return string|null
     */
    public function getShortLinkAttribute(): ?string
    {
        return $this->id ? (config('app.url') . '/' . $this->getCode()) : null;
    }

    /**
     * @return string|null
     */
    public function getShortCodeAttribute(): ?string
    {
        return $this->getCode();
    }
}

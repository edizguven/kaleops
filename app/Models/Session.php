<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    protected $table = 'sessions';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [];

    protected $casts = [];

    /**
     * Oturuma ait kullanıcı (user_id null olabilir - misafir oturumları)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * last_activity Unix timestamp'ini okunabilir tarih/saat olarak döndürür
     */
    public function getLastActivityAtAttribute(): ?string
    {
        if (empty($this->last_activity)) {
            return null;
        }
        return \Carbon\Carbon::createFromTimestamp($this->last_activity)->format('d.m.Y H:i:s');
    }

    /**
     * User agent'ı kısaltılmış gösterim (ilk ~80 karakter)
     */
    public function getShortUserAgentAttribute(): string
    {
        $ua = $this->user_agent ?? '';
        if (strlen($ua) > 80) {
            return substr($ua, 0, 77) . '...';
        }
        return $ua ?: '—';
    }
}

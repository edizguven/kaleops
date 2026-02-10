<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class JobFile extends Model
{
    protected $fillable = ['job_id', 'file_path', 'original_name'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Dosyayı diskten sil
     */
    public function deleteFileFromStorage(): bool
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->delete($this->file_path);
        }
        return true;
    }
}

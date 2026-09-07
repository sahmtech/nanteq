<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Sound extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function soundProgress()
    {
        return $this->hasOne(SoundProgress::class)->where('trainee_id', auth()->user()->id);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($record) {
            if ($record->xray_videos) {
                Storage::disk('local')->delete($record->xray_videos);
            }
            if ($record->natural_videos) {
                Storage::disk('local')->delete($record->natural_videos);
            }
            if ($record->audio) {
                Storage::disk('local')->delete($record->audio);
            }
        });

        static::updating(function ($record) {

            if ($record->isdirty('xray_videos')) {
                $originalFile = $record->getOriginal('xray_videos');
                if ($originalFile) {
                    Storage::disk('local')->delete($originalFile);
                }
            }
            if ($record->isdirty('natural_videos')) {
                $originalFile = $record->getOriginal('natural_videos');
                if ($originalFile) {
                    Storage::disk('local')->delete($originalFile);
                }
            }
            if ($record->isdirty('audio')) {
                $originalFile = $record->getOriginal('audio');
                if ($originalFile) {
                    Storage::disk('local')->delete($originalFile);
                }
            }
        });
    }
}
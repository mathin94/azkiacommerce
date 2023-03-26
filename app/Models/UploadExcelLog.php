<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadExcelLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'messages',
        'error_count',
        'process_count',
    ];

    public function addMessage($message)
    {
        $messages = json_decode($this->messages);
        array_push($messages, $message);

        $this->messages     = json_encode($messages);
        $this->error_count += 1;
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->messages = '[]';
        });
    }
}

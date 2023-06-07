<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bank_id',
        'account_name',
        'account_number',
        'branch'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    protected function bankName(): Attribute
    {
        return Attribute::make(get: fn () => $this->bank->name);
    }

    protected function bankCode(): Attribute
    {
        return Attribute::make(get: fn () => $this->bank->code);
    }

    protected function accountLabel(): Attribute
    {
        return Attribute::make(get: function () {
            return "{$this->bank_name} - {$this->account_name} - {$this->account_number}";
        });
    }
}

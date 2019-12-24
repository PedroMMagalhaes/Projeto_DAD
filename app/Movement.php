<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Movement extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'wallet_id',
        'type',
        'typeString',
        'transfer',
        'transfer_movement_id',
        'type_payment',
        'type_paymentString',
        'category_id',
        'iban',
        'mb_entity_code',
        'mb_payment_reference',
        'description',
        'source_description',
        'date',
        'start_balance',
        'end_balance',
        'value'
    ];

    public static function getCategory($category_id){
        if ($category_id <> null){
            $category = DB::table('categories')->where('id', $category_id)->first();
            return $category->name;
        }
        return '';
    }

    public static function typeToStr($type){
        switch ($type){
            case 'e':
                return 'Expense';
            case 'i':
                return 'Income';
        }
        return '';
    }

    public function isExpense(){
        return $this->type === "e";
    }
    public function isIncome(){
        return $this->type === "i";
    }

    public static function typePaymentToStr($type_payment){
        switch ($type_payment){
            case 'c':
                return 'Cash';
            case 'bt':
                return 'Bank Transfer';
            case 'mb':
                return 'MB payment';
            }
        return null;
    }

    public function isCash(){
        return $this->type === "c";
    }
    public function isBankTransfer(){
        return $this->type === "bt";
    }
    public function isMB(){
        return $this->type === "mb";
    }
    public function isTransfer(){
        return $this->type === null;
    }

    
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'business_details';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = array('service_id', 'text', 'business_description'); 

    public function businessDetailImages()
    {
        return $this->hasMany(ServiceImage::class, 'business_detail_id', 'id');
    }
    public function serviceTitle()
    {
        return $this->hasMany(OurService::class, 'id', 'service_id');
    }
}

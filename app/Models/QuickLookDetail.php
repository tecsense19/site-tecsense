<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickLookDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quick_look_details';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = array('service_id', 'text');


    public function quickLookDetailImages()
    {
        return $this->hasMany(ServiceImage::class, 'quick_look_detail_id', 'id');
    }
    public function serviceTitle()
    {
        return $this->hasMany(OurService::class, 'id', 'service_id');
    }
}

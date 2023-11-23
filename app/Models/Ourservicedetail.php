<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ourservicedetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ourservicedetails';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = array('service_id', 'service_detail_title', 'servicedetail_pic');

    public function serviceDetailImages()
    {
        return $this->hasMany(ServiceImage::class, 'servicedetial_id', 'id');
    }
    public function serviceTitle()
    {
        return $this->hasMany(OurService::class, 'id', 'service_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Featuredetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'features_details';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = array('service_id', 'text', 'features_detail_title', 'features_detail_description', 'features_detail_pic');

    public function featuresDetailImages()
    {
        return $this->hasMany(ServiceImage::class, 'features_detail_id', 'id');
    }

    public function serviceTitle()
    {
        return $this->hasMany(OurService::class, 'id', 'service_id');
    }
}

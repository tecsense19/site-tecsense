<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurService extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'our_services';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = array('service_title', 'service_description', 'service_black_logo', 'service_white_logo');

    public function serviceImages()
    {
        return $this->hasMany(ServiceImage::class, 'service_id', 'id');
    }
}

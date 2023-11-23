<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyChooseDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'why_choose_details';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = array('service_id', 'why_choose_detail_title', 'why_choose_detail_description','why_choose_detail_pic');

    public function serviceTitle()
    {
        return $this->hasMany(OurService::class, 'id', 'service_id');
    }

    public function whyChooseDetailImages()
    {
        return $this->hasMany(ServiceImage::class, 'why_choose_detail_id', 'id');
    }
}

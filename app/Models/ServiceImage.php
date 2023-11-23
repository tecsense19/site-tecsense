<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_images';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = array('service_id', 'technology_id', 'testimonial_id', 'expert_id', 'why_choose_id', 'about_id', 'working_process_id', 'vision_id', 'team_id', 'servicedetial_id', 'why_choose_detail_id','best_result_detail_id','quick_look_detail_id','business_detail_id','features_detail_id', 'title','step_title', 'description', 'image_path');
}

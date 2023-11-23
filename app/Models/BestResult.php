<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestResult extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'best_results_details';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = array('service_id', 'text', 'best_result_detail_pic', 'step_title', 'main_title'); 
    
    
    public function bestResultDetailImages()
    {
        return $this->hasMany(ServiceImage::class, 'best_result_detail_id', 'id');
    }
    public function serviceTitle()
    {
        return $this->hasMany(OurService::class, 'id', 'service_id');
    }
}

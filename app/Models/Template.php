<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    
    /**
     * Create 3-days trial for app.
     * @param string $template_id
     * @return string|boolean
     */
    static function getMallsCountByTemplateId( $template_id ){
        $malls= Mall::where('template_id',$template_id)->count();
        return $malls;
    }
}

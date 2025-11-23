<?php

namespace Frontend;

class HtmlWrapper 
{

    public function __construct()
    {
    }

    public function getLinkWrapper($data, $type, $mailto=false)
    {
        if(!empty($data)) {
            if($mailto) {
                return $type . ' : <a href="mailto:'.$data.'">'.$data.'</a>';
            }
            return $type . ' : <a href="'.$data.'" target="_blank">'.$data.'</a>';
        }
        return '';
    }

    public function getSocialsWrapper($data, $type)
    {
        if(str_contains($data, 'https://www.' . $type . '.com')) {
            return '<a class="'.$type.'" href="'. $data . '" target="_bank"></a>';
        }
        return '';
    }

}
<?php 
namespace VladDnepr\SimpleMMO\Model;
 
class AbstractModel {

    protected $fields = array();

    function __construct($data = array())
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this -> $key = $value;
            }
        }
    }

    /**
     * Dump all model data as array
     * @return array
     */
    public function dump()
    {
        return array_intersect_key(get_object_vars($this), array_flip($this -> fields));
    }


}
<?php
class builderModel extends Model
{
    final public function executeQuery($sQuery)
    {
        return $this->query($sQuery);
    }
}
<?php

namespace Railroad\MusoraApi\Serializer;

use League\Fractal\Serializer\DataArraySerializer;

class DataSerializer extends DataArraySerializer{

    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data):array
    {
        return $data;
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data):array
    {
        return  $data;
    }

}
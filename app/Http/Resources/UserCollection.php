<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    private $success;
    public function __construct($resource, $success=true)
    {
        parent::__construct($resource);
        $this->success = $success;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        array_walk_recursive($data, function (&$value) {
            $value = $value ?? "";
        });
        return [
            'success' => $this->success,
            'data' => $data,
        ];
    }
}



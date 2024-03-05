<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $singleResponse;
    private $statusText;
    public function __construct($resource, $statusText = true ,$singleResponse = false)
    {
        parent::__construct($resource);
        $this->singleResponse = $singleResponse;
        $this->statusText = $statusText;
    }
    /**
     * Transform the resource into an array.
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
        if(!$this->singleResponse){
            return  $data;
        }
        return [
            'success' => $this->statusText,
            'data' =>  $data,
        ];
    }
}

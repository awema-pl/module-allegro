<?php

namespace AwemaPL\Allegro\Sections\Applications\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EloquentApplication extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'client_id' => $this->client_id,
           'client_secret' => $this->client_secret,
            'sandbox' => $this->sandbox,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

<?php

namespace AwemaPL\Allegro\Sections\Accounts\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EloquentAccount extends JsonResource
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
            'username' => $this->username,
            'expires_at' => optional($this->refreshTokenExpiresAt)->format('Y-m-d H:i:s'),
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

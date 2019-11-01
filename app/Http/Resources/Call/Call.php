<?php

namespace App\Http\Resources\Call;

use App\Helpers\DateHelper;
use App\Http\Resources\Contact\ContactShort as ContactShortResource;
use App\Http\Resources\Emotion\Emotion as EmotionResource;
use Illuminate\Http\Resources\Json\Resource;

class Call extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'object' => 'call',
            'called_at' => DateHelper::getTimestamp($this->called_at),
            'content' => $this->content,
            'contact_called' => $this->contact_called,
            'emotions' => EmotionResource::collection($this->emotions),
            'account' => [
                'id' => $this->account->id,
            ],
            'contact' => new ContactShortResource($this->contact),
            'created_at' => DateHelper::getTimestamp($this->created_at),
            'updated_at' => DateHelper::getTimestamp($this->updated_at),
        ];
    }
}

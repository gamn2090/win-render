<?php

namespace App\Transformers;

use Musonza\Chat\Transformers\Transformer;

class ParticipantTransformer extends Transformer
{

    public function transform($item)
    {
        return [
            'id' => $item->id,
            'conversation_id' => $item->conversation_id,
            'messageable_id' => $item->messageable_id,
            'messageable_type' => $item->messageable_type,
            'settings' => $item->settings
        ];
    }
}
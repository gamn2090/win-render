<?php

namespace App\Services;

use App\Models\User;

class ClientService {

    public function getByID($id){
        return User::where('id', $id)->first();
    }

    public function getByUUID($uuid){
        return User::where('uuid', $uuid)->first();
    }

    public function uuidToID($uuid){
        if($uuid == null){
            return null;
        }
        return User::where('uuid', $uuid)->value('id');
    }
}
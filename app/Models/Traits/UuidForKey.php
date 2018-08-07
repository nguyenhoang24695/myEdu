<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 27/04/2016
 * Time: 4:03 CH
 */

namespace App\Models\Traits;
use Rhumsaa\Uuid\Uuid;

trait UuidForKey {

    /**
     * Boot the Uuid trait for the model.
     *
     * @return void
     */
    public static function bootUuidForKey()
    {
        static::creating(function ($model) {
            $model->incrementing = false;
            $model->{$model->getKeyName()} = $model->generateNewId()->toString();
        });
    }

    /**
     * Get a new version 4 (random) UUID.
     *
     * @return \Rhumsaa\Uuid\Uuid
     */
    public function generateNewId()
    {
        return Uuid::uuid4();
    }

    public static function findUUID($uuid) {
        return self::find($uuid);
    }
}
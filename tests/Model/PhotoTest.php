<?php

use PHPUnit\Framework\TestCase;
use model\Photo;
use model\Annonce;

class PhotoTest extends TestCase
{
    public function testTableName()
    {
        $model = new Photo();
        $this->assertEquals('photo', $model->getTable());
    }

    public function testPrimaryKey()
    {
        $model = new Photo();
        $this->assertEquals('id_photo', $model->getKeyName());
    }

    public function testTimestampsDisabled()
    {
        $model = new Photo();
        $this->assertFalse($model->timestamps);
    }

    public function testAnnonceRelation()
    {
        $photo = new Photo();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $photo->annonce());
    }
}
<?php

use PHPUnit\Framework\TestCase;
use model\ApiKey;

class ApiKeyTest extends TestCase
{
    public function testTableName()
    {
        $model = new ApiKey();
        $this->assertEquals('apikey', $model->getTable());
    }

    public function testPrimaryKey()
    {
        $model = new ApiKey();
        $this->assertEquals('id_key', $model->getKeyName());
    }

    public function testTimestampsDisabled()
    {
        $model = new ApiKey();
        $this->assertFalse($model->timestamps);
    }
}
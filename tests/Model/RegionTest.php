<?php

use PHPUnit\Framework\TestCase;
use model\Region;

class RegionTest extends TestCase
{
    public function testTableName()
    {
        $model = new Region();
        $this->assertEquals('region', $model->getTable());
    }

    public function testPrimaryKey()
    {
        $model = new Region();
        $this->assertEquals('id_region', $model->getKeyName());
    }

    public function testTimestampsDisabled()
    {
        $model = new Region();
        $this->assertFalse($model->timestamps);
    }
}
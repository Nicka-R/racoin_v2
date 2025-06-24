<?php

use PHPUnit\Framework\TestCase;
use model\Departement;

class DepartementTest extends TestCase
{
    public function testTableName()
    {
        $model = new Departement();
        $this->assertEquals('departement', $model->getTable());
    }

    public function testPrimaryKey()
    {
        $model = new Departement();
        $this->assertEquals('id_departement', $model->getKeyName());
    }

    public function testTimestampsDisabled()
    {
        $model = new Departement();
        $this->assertFalse($model->timestamps);
    }
}
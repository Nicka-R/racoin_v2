<?php

use PHPUnit\Framework\TestCase;
use model\Categorie;

class CategorieTest extends TestCase
{
    public function testTableName()
    {
        $model = new Categorie();
        $this->assertEquals('categorie', $model->getTable());
    }

    public function testPrimaryKey()
    {
        $model = new Categorie();
        $this->assertEquals('id_categorie', $model->getKeyName());
    }

    public function testTimestampsDisabled()
    {
        $model = new Categorie();
        $this->assertFalse($model->timestamps);
    }
}
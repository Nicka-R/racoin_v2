<?php

use PHPUnit\Framework\TestCase;
use model\SousCategorie;

class SousCategorieTest extends TestCase
{
    public function testTableName()
    {
        $model = new SousCategorie();
        $this->assertEquals('sous_categorie', $model->getTable());
    }

    public function testPrimaryKey()
    {
        $model = new SousCategorie();
        $this->assertEquals('id_sous_categorie', $model->getKeyName());
    }

    public function testTimestampsDisabled()
    {
        $model = new SousCategorie();
        $this->assertFalse($model->timestamps);
    }
}
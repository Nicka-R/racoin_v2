<?php

use PHPUnit\Framework\TestCase;
use model\Annonceur;

class AnnonceurTest extends TestCase
{
    public function testTableName()
    {
        $model = new Annonceur();
        $this->assertEquals('annonceur', $model->getTable());
    }

    public function testPrimaryKey()
    {
        $model = new Annonceur();
        $this->assertEquals('id_annonceur', $model->getKeyName());
    }

    public function testTimestampsDisabled()
    {
        $model = new Annonceur();
        $this->assertFalse($model->timestamps);
    }

    public function testAnnonceRelation()
    {
        $annonceur = new Annonceur();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $annonceur->annonce());
    }

    public function testCRUD()
    {
        $annonceur = new Annonceur();
        $annonceur->email = 'test2@example.com';
        $annonceur->nom_annonceur = 'Testeur2';
        $annonceur->telephone = '0123456789';
        $this->assertTrue($annonceur->save());

        $found = Annonceur::find($annonceur->id_annonceur);
        $this->assertNotNull($found);
        $this->assertEquals('Testeur2', $found->nom_annonceur);

        $found->nom_annonceur = 'Modifié';
        $this->assertTrue($found->save());
        $updated = Annonceur::find($found->id_annonceur);
        $this->assertEquals('Modifié', $updated->nom_annonceur);

        $id = $updated->id_annonceur;
        $this->assertTrue($updated->delete());
        $this->assertNull(Annonceur::find($id));
    }
}
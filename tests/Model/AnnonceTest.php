<?php

use PHPUnit\Framework\TestCase;
use app\Model\Annonce;
use app\Model\Annonceur;
use app\Model\Photo;

class AnnonceTest extends TestCase
{
    public function testTableName()
    {
        $model = new Annonce();
        $this->assertEquals('annonce', $model->getTable());
    }

    public function testPrimaryKey()
    {
        $model = new Annonce();
        $this->assertEquals('id_annonce', $model->getKeyName());
    }

    public function testTimestampsDisabled()
    {
        $model = new Annonce();
        $this->assertFalse($model->timestamps);
    }

    public function testAnnonceurRelation()
    {
        $annonce = new Annonce();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $annonce->annonceur());
    }

    public function testPhotoRelation()
    {
        $annonce = new Annonce();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $annonce->photo());
    }

    public function testCRUD()
    {
        $annonceur = new Annonceur();
        $annonceur->email = 'test@example.com';
        $annonceur->nom_annonceur = 'Testeur';
        $annonceur->telephone = '0123456789';
        $annonceur->save();

        $annonce = new Annonce();
        $annonce->id_annonceur = $annonceur->id_annonceur;
        $annonce->ville = 'Paris';
        $annonce->id_departement = 1;
        $annonce->prix = 100;
        $annonce->mdp = password_hash('secret', PASSWORD_DEFAULT);
        $annonce->titre = 'Titre test';
        $annonce->description = 'Description test';
        $annonce->id_categorie = 1;
        $annonce->date = date('Y-m-d');
        $this->assertTrue($annonce->save());

        $found = Annonce::find($annonce->id_annonce);
        $this->assertNotNull($found);
        $this->assertEquals('Paris', $found->ville);

        $found->ville = 'Lyon';
        $this->assertTrue($found->save());
        $updated = Annonce::find($found->id_annonce);
        $this->assertEquals('Lyon', $updated->ville);

        $id = $updated->id_annonce;
        $this->assertTrue($updated->delete());
        $this->assertNull(Annonce::find($id));
        $annonceur->delete();
    }
}
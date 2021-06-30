<?php

namespace App\DataFixtures;

use App\Entity\ToolType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ToolTypeFixtures extends Fixture
{

    public const BOL = 'bol';
    public const BROUETTE = 'brouette';
    public const CISEAU = 'ciseau';
    public const COMPAS = 'compas';
    public const COUTEAU = 'couteau';
    public const CLOUS = 'clous';
    public const CUILLERE = 'cuillere';
    public const ECHELLE = 'echelle';
    public const HACHE = 'hache';
    public const MARTEAU = 'marteau';
    public const METRE = 'metre';
    public const NIVEAU = 'niveau';
    public const PELLE = 'pelle';
    public const PERCEUSE = 'perceuse';
    public const PINCE = 'pince';
    public const PIOCHE = 'pioche';
    public const RATEAU = 'rateau';
    public const SCEAU = 'sceau';
    public const SCIE = 'scie';
    public const SECATEUR = 'secateur';
    public const SPATULE = 'spatule';
    public const TAILLE_HAIE = 'taille-haie';

    
    public function load(ObjectManager $manager)
    {
        $tailleHaie = new ToolType();
        $tailleHaie->setLabel("taille haie");
        $manager->persist($tailleHaie);
        $this->addReference(self::TAILLE_HAIE, $tailleHaie);
        
        $spatule = new ToolType();
        $spatule->setLabel("spatule");
        $manager->persist($spatule);
        $this->addReference(self::SPATULE, $spatule);
        
        $secateur = new ToolType();
        $secateur->setLabel("secateur");
        $manager->persist($secateur);
        $this->addReference(self::SECATEUR, $secateur);
        
        $scie = new ToolType();
        $scie->setLabel("scie");
        $manager->persist($scie);
        $this->addReference(self::SCIE, $scie);
        
        $sceau = new ToolType();
        $sceau->setLabel("sceau");
        $manager->persist($sceau);
        $this->addReference(self::SCEAU, $sceau);
        
        $rateau = new ToolType();
        $rateau->setLabel("rateau");
        $manager->persist($rateau);
        $this->addReference(self::RATEAU, $rateau);

        $pioche = new ToolType();
        $pioche->setLabel("pioche");
        $manager->persist($pioche);
        $this->addReference(self::PIOCHE, $pioche);

        $pince = new ToolType();
        $pince->setLabel("pince");
        $manager->persist($pince);
        $this->addReference(self::PINCE, $pince);

        $perceuse = new ToolType();
        $perceuse->setLabel("perceuse");
        $manager->persist($perceuse);
        $this->addReference(self::PERCEUSE, $perceuse);

        $pelle = new ToolType();
        $pelle->setLabel("pelle");
        $manager->persist($pelle);
        $this->addReference(self::PELLE, $pelle);

        $niveau = new ToolType();
        $niveau->setLabel("niveau");
        $manager->persist($niveau);
        $this->addReference(self::NIVEAU, $niveau);

        $metre = new ToolType();
        $metre->setLabel("metre");
        $manager->persist($metre);
        $this->addReference(self::METRE, $metre);
        
        $marteau = new ToolType();
        $marteau->setLabel("marteau");
        $manager->persist($marteau);
        $this->addReference(self::MARTEAU, $marteau);
        
        $hache = new ToolType();
        $hache->setLabel("hache");
        $manager->persist($hache);
        $this->addReference(self::HACHE, $hache);

        $echelle = new ToolType();
        $echelle->setLabel("echelle");
        $manager->persist($echelle);
        $this->addReference(self::ECHELLE, $echelle);
        
        $cuillere = new ToolType();
        $cuillere->setLabel("cuillere");
        $manager->persist($cuillere);
        $this->addReference(self::CUILLERE, $cuillere);
        
        $bol = new ToolType();
        $bol->setLabel("bol");
        $manager->persist($bol);
        $this->addReference(self::BOL, $bol);

        $brouette = new ToolType();
        $brouette->setLabel("brouette");
        $manager->persist($brouette);
        $this->addReference(self::BROUETTE, $brouette);

        $ciseau = new ToolType();
        $ciseau->setLabel("ciseau");
        $manager->persist($ciseau);
        $this->addReference(self::CISEAU, $ciseau);

        $compas = new ToolType();
        $compas->setLabel("compas");
        $manager->persist($compas);
        $this->addReference(self::COMPAS, $compas);

        $couteau = new ToolType();
        $couteau->setLabel("couteau");
        $manager->persist($couteau);
        $this->addReference(self::COUTEAU, $couteau);

        $clous = new ToolType();
        $clous->setLabel("clous");
        $manager->persist($clous);
        $this->addReference(self::CLOUS, $clous);

        $manager->flush();
    }
}

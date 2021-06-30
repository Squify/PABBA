<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tutorial;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TutorialFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $items = [
            'title' => [
                "Comment planter ses pieds de tomates",
                "Comment créer son propre composte",
                "Créer son petit potager personnel",
                "Tarte a la courgette",
            ],
            'type' => [
                $this->getReference(TutorialTypeFixtures::TUTORIAL_TYPE_JARDINAGE),
                $this->getReference(TutorialTypeFixtures::TUTORIAL_TYPE_RECYCLAGE),
                $this->getReference(TutorialTypeFixtures::TUTORIAL_TYPE_JARDINAGE),
                $this->getReference(TutorialTypeFixtures::TUTORIAL_TYPE_CUISINE),
            ],
            'description' => [
                "
                A la fin des dernières gelé, trouvez un emplacement bien ensoleillé, de préférence 
                exposé au soleil couchant qui offre une plus longue exposition au chaud, ou contre
                un mur ensoleillé par exemple.
                <br> 
                Arrosez vos plants le matin ou quelques heures
                avant de planter : ainsi, ils se libèreront plus facilement du godet, en un seul bloc.
                <br>
                Supprimez les deux feuilles du bas du pied, creusez un trou d'une vingtaine de centimètres, 
                et enfoncez le plant sur 10 cm de tige : cela l'aidera à refaire des racines et surtout à 
                aller puiser la fraicheur plus en profondeur lorsque la chaleur sera là. Inutile de tasser 
                la terre autour du pied, cela écrasera les racines qui auront du mal à se développer.
                <br>
                Autour du pied, creusez légèrement en couronne à 10 cm du pied et versez votre arrosage : 
                ainsi, l'eau irriguera bien le pied.
                <br>
                Espacez les pieds de 50 cm environ et les rangs de 70 cm. 
                <br>
                Là encore, n'oubliez pas de noter la variété des différents pieds de tomates pour vous y retrouver !   
                <br>
                N'oubliez pas d'arroser les pieds régulièrement, encore plus en cas de canicule
                ",
                "
                Dans un coin reculé de votre jardin, ou dans une boite airmétique, vous pouvez y entreposer
                tous vos déchés organiques, en partant de la coquille d'oeuf jusqu'a la peau de banane en 
                passant par les cheveux fraichement coupés de votre fils.
                <br>
                Remuer une fois tout les deux ou trois mois votre composte pour l'aéré
                <br>
                Vous pouvez utiliser votre composte pour 'nourrir' vos plantation, attention à ne pas le 
                dépausé trop près de vos plante, cela pourrai les bruler
                ",
                "
                Prenez des planches de bois. 
                <br>
                1- Faite un carré/rectangle avec 4 plaches, faite selon la taille du potagé que vous souhaitez et clouez solidement les entres elles.
                <br>
                2- Faite le tour de votre carré en disposant des planche de manière verticale à l'intérieur de ce carré, et fixé les à l'aide des clous.
                <br>
                3- Solidifiez le tout en refaisant un carré sur le haut du carré de plance. Installez une bache à l'intérieur et mettez y de la terre avec du composte.
                <br>
                4- Vous pouvez maintenant planter vos légumes pour les voir pousser chez vous !
                ",
                "
                1. Epluchez et coupez en rondelles les courgettes.<br>
                2. Pelez et hachez finement un oignon.<br>
                3. Nettoyez et épongez 2 tomates. Coupez-les en dés.<br>
                4. Faites chauffer un filet d'huile d'olive dans une poêle.<br>
                5. Faites-y revenir les courgettes en rondelles pendant 5 minutes.<br>
                6. Ajoutez l'oignon haché et laissez dorer.<br>
                7. Ajoutez enfin les tomates en dés et laissez cuire encore 5 minutes.<br>
                8. Retirez du feu et versez le tout dans un saladier.<br>
                9. Ajoutez 2 oeufs entiers, 20cl de crème fraîche liquide, du sel et du poivre. Mélangez bien.<br>
                10. Préchauffez le four à 180°C.<br>
                11. Déroulez la pâte brisée dans un moule à tarte recouvert de papier sulfurisé.<br>
                12. Piquez le fond avec une fourchette.<br>
                13. Versez la préparation aux courgettes sur la pâte brisée dans le moule à tarte.<br>
                14. Enfournez et faites cuire la tarte aux courgettes pendant 30 minutes, jusqu'à ce qu’elle soit bien dorée.<br>
                15. A la sortie du four, laissez tiédir la tarte aux courgettes sur une grille puis démoulez-la sur un plat de service.<br>
                16. Servez la tarte aux courgettes tiède ou froide accompagnée d'une salade verte assaisonnée.
                "
            ],
            'tools' => [
                [$this->getReference(ToolTypeFixtures::PELLE), $this->getReference(ToolTypeFixtures::METRE)],
                [],
                [$this->getReference(ToolTypeFixtures::MARTEAU), $this->getReference(ToolTypeFixtures::PERCEUSE),  $this->getReference(ToolTypeFixtures::CLOUS) ],
                [$this->getReference(ToolTypeFixtures::COUTEAU)],

            ]
        ];

        for ($i=0; $i<sizeof($items['title']); $i++) 
        {
            $tutorial = new Tutorial();
            $tutorial->setTitle($items['title'][$i])
                ->setType($items['type'][$i])
                ->setDescription($items['description'][$i])
                ->setUser($faker->randomElement(UserFixtures::USERS))
                ->setDisable(false)
                ->setUpdatedAt($faker->dateTime('now'));
            foreach ($items['tools'][$i] as $tool ) {
                $tutorial->addTool($tool);
            }
            $manager->persist($tutorial);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            TutorialTypeFixtures::class,
            ToolTypeFixtures::class,
        ];
    }
}

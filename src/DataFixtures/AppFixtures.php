<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\Goal;
use App\Entity\Partner;
use Faker\Factory;
use App\Entity\Item;
use App\Entity\Type;
use App\Entity\User;
use App\Entity\Place;
use App\Entity\Rank;
use App\Entity\Reward;
use App\Entity\State;
use App\Entity\ToCount;
use App\Entity\ToolType;
use App\Entity\Tutorial;

use App\Entity\TutorialType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $users = [];

//      User Fixture
        $user = new User();
        $user->setEmail("user@user.fr")
            ->setEnable(1)
            ->setPassword($this->encoder->encodePassword($user, "user"))
            ->setRoles(["ROLE_USER"])
            ->setFirstname('user');
        $manager->persist($user);
        $users[] = $user;

        $admin = new User();
        $admin->setEmail("admin@admin.fr")
            ->setEnable(1)
            ->setPassword($this->encoder->encodePassword($admin, "admin"))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('admin');
        $manager->persist($admin);
        $users[] = $admin;

        $moderator = new User();
        $moderator->setEmail("moderator@moderator.fr")
            ->setEnable(1)
            ->setPassword($this->encoder->encodePassword($moderator, "moderator"))
            ->setRoles(["ROLE_MODERATOR"])
            ->setFirstname('moderator');
        $manager->persist($moderator);
        $users[] = $moderator;

        for ($i=0; $i < 50; $i++) {
            $user = new User();
            $user->setEmail("user$i@user.fr")
                ->setEnable(1)
                ->setPassword($this->encoder->encodePassword($user, "user"))
                ->setRoles(["ROLE_USER"])
                ->setFirstname("user$i");
            $manager->persist($user);
            $users[] = $user;
        }

//      Place Type Fixtures
        $typeLabels = ["Jardin", "Événement", "Recyclage", "Point de collecte"];
        foreach ($typeLabels as $label) {
            $placeType = new Type();
            $placeType->setLabel($label);
            $manager->persist($placeType);
            $type[]=$placeType;
        }

//      Place Fixtures
        $places = [];
        for($i=0; $i<15; $i++) {
            $place = new Place();
            $place->setUser($admin)
                ->setType($faker->randomElement($type))
                ->setAddress($faker->address)
                ->setIsValid($faker->boolean)
                ->setOpen($faker->paragraph)
                ->setComments($faker->paragraph)
                ->setLatitude($faker->latitude(47.60, 48.00))
                ->setLongitude($faker->longitude(1.70, 2.10));
                // ([47.843601, 1.939258])
            $manager->persist($place);
            $places[] = $place;
        }

//      Tutorial Type Fixtures
        $typeLabels = ["Jardinage", "Cuisine", "Recyclage", "Prise en main d'outillage"];
        foreach ($typeLabels as $label) {
            $tutorialType = new TutorialType();
            $tutorialType->setLabel($label);
            $manager->persist($tutorialType);
            $tabTutorial[]=$tutorialType;
        }

//      Tool Type Fixtures
//      Pour la ligne en dessous il faudait avoir un trie sur le EntityType du formulaire, j'ai pas eu le temps de voir ça (sinon ça fait beaucoup de données)
//      $typeLabels = ["Aérographe", "Affiloire", "Agrafeuse", "Aiguille", "Alêne", "Alésoir", "Alésoir à cliquet", "Appareil à battre les collets", "Appareil à vitrer", "Arrache-clou", "Arrache-manivelle", "Auge", "Bac à pinceau", "Balai chinois", "Bambane", "Banc à étirer", "Baratte", "Barre à mine", "Batée", "Batte", "Batte à feu", "Batte à semis", "Batte de carreleur", "Bêche", "Bédane", "Biberon de poudre à tracer", "Bigorne", "Binette", "Bétonnière", "Boîte à onglets", "Bol", "Bol à plâtre", "Bombe", "Boulonneuse", "Bourroir", "Broche", "Bridou", "Briquet", "Broche de carreleur", "Brosse", "Brosse à encoller", "Brosse à badigeon", "Brosse à laquer", "Brosse à rechampir", "Brosse à vernir", "Brosse à tapisser", "Brosse coudée pour radiateurs", "Brosse métallique", "Brouette", "Burin", "Bussetto", "Cage d'écureuil", "Calibre à limites", "Cale", "Cale à poncer", "Cale d'épaisseur", "Clée à molette", "Cale étalon", "Canne", "Canne de verrier", "Cardeuse", "Carrelette", "Chaîne", "Chaîne d'arpenteur", "Chalumeau", "Chalumeau oxyacétylénique", "Chasse", "Chasse-pointes", "Chasse-goupilles", "Chemin de fer", "Chevillette", "Chignole", "Chinois", "Cisaille", "Cisaille à gazon", "Cisaille à haie", "Cisaille à tôle", "Cisaille guillotine", "Ciseau", "Ciseau à bois", "Ciseau à brique", "Ciseau à rainer", "Ciseau anglais", "Ciseau d'électricien", "Ciseau de couturière", "Ciseau de dentellière", "Ciseau de maçon", "Ciseau tailleur de pierre", "Clé", "Clé anglaise", "Clé à chaîne", "Clé à cliquet", "Clé à cône", "Clé à ergots", "Clé à molette", "Clé à œilSuppliesTypecontrecoudée", "Clé à pédales", "Clé à pipe débouchée", "Clé à téton de rayon", "Clé à tube", "Clé d'accordeur", "Clé de lavabo", "Clé dynamométrique", "Clé Guillemin", "Clé plate", "Clé plate spéciale pour la pose des fixations lavabo", "Clé six pans", "Clé Torx", "Clé tricoise", "Cliquet", "Cognée", "Coin", "Comparateur (appareil de mesure)", "Compas", "Compas à ressort", "Compas à verge", "Compas à charnière", "Compas d'épaisseur", "Compas de Mordente", "Compas de transfert", "Compte-fils", "Corde à treize nœuds", "Cordeau", "Cordeau à tracer", "Coupe-verre", "Coupe-tube", "Couteau", "Couteau à colle", "Couteau à démastiquer", "Couteau à enduire", "Couteau à mastiquer", "Couteau à lame rétractableSuppliesType(CutterSuppliesTypeen anglais)", "Couteau de peinture", "Couteau de vitrier", "Couteau suisse", "Craie", "Crayon", "Crayon de charpentier", "Crayon de menuisier", "Crayon de maçon", "Cric", "Croc à fumier", "Croissant d'élagage", "Cube de mesure", "Cuillère", "Cuillère à mesurer", "Débroussailleuse", "Décapeur thermique", "Décolleuse", "Défonceuse", "Dégauchisseuse", "Démonte roue-libre", "Démonte-obus", "Départoir", "Dérive-chaîne", "Dévisse-cassette", "Diable", "Diamant", "Disqueuse", "Doloire", "Doucine", "Douille", "Drille", "Échafaudage", "Échelle", "Échenilloir", "Écouvillon", "Écran à peinture", "Égoïne", "Élagueuse", "Élastique", "Élingue", "Emporte pièce", "Enclume", "Encolleuse", "Éponge", "Épuisette", "Équerre", "Équerre à coulisse", "Équerre de maçon", "Équerre de mécanicien", "Équerre de menuisier", "Établi", "Étau", "Extracteur (outil)", "Faucille", "FAUX", "Fer à souder", "Fermoir", "Fiche", "Fil à plomb", "Filet", "Filière", "Filière à têtes interchangeables", "Fontaine de dégraissage", "Foret", "Fouet à chaîne", "Fourche", "Fourche bêche", "Fraise", "Fusil", "Gaule", "Gaufroir", "Genouillère", "Gouge", "Gouge à asperges", "Gouge de bottier", "Gradine", "Gradine à point d'orge", "Grain d'orge", "Grattoir", "Grattoir à déjointer", "Greffoir", "Grelinette", "Griffe", "Griffe à cintrer", "Griffe de jardin", "Griffe de zingueur", "Guillaume", "Guimbarde", "Hache", "Hachette", "Hachette de plâtrier", "Herminette", "Herse", "Houe", "Incinérateur de jardin", "Inclinomètre", "Jauge", "Jauge d'épaisseur", "Lame", "Lame abrasive", "Lame à enduire", "Lamelleuse", "Laminoir", "Laminoir à fils", "Laminoir à plaques", "Lampe à souder", "Langue de chat", "Laye", "Levier", "Lime", "Lingotière à bascule", "Lisseuse", "Machine à coudre", "Machine à crépir", "Machine à plâtre", "Maillet", "Maillet de carreleur", "Maillet de menuisier", "Maillet de ferblantier", "Maillet en caoutchouc", "Maillet polyvalent", "Mandrin", "Marteau", "Marteau américainSuppliesTypeouSuppliesTypeMarteau arrache clou", "Marteau bourgeois", "Marteau compo-cast", "Marteau de bottier", "Marteau d'électricien", "Marteau de charpentier", "Marteau de coffreur", "Marteau de menuisier", "Marteau-piqueur", "Marteau de serrurier", "Marteau de soudeur", "Marteau de vitrier", "Marteau rivoir", "Marteau de tapissier ou Ramponneau", "Martelette", "Masse", "Massette", "Massette portugaise", "Mèche", "Merlin", "Mètre", "Mètre à ruban", "Mètre pliant", "Meule", "Meuleuse", "Meuleuse à disque diamant", "Micromètre", "Moine", "Monture de scie à métaux avec lame", "Motoculteur", "Moule", "Multimètre", "Niveau", "Niveau à bulle", "Niveau à eau", "Niveau d'angle, appelé aussiSuppliesTypeinclinomètre.", "Niveau laser", "Niveau optique", "Ohmmètre", "Outils divers de bottier", "Ouvre-boîtes", "Ordinateur", "Outil multifonctions (oscillant)", "Palette", "Palan", "Palmer ouSuppliesTypeMicromètre", "Pelle", "Perceuse", "Perceuse à colonne", "Perforateur", "Perche", "Perche d'électricien", "Pied à coulisse", "Pied-de-biche", "Pierre", "Pierre à gréser", "pierre à aiguiser", "pierre à raser", "Pince", "Pince à avoyer", "Pince à becs", "Pince à cintrer", "Pince à dénuder", "Pince à tubes", "Pince brucelle", "Pince à circlips", "Pince à long bec", "Pince coupante", "Pince coupe-boulon", "Pince coupe-câble", "Pince crocodile", "Pince de bottier-cordonnier", "Pince de carreleur", "Pince de pose", "Pince dérive-chaîne", "Pince-étau", "Pince gouge", "Pince-monseigneur", "Pince multi-prise", "Pince Perdrielle", "Pince perroquet", "Pince universelle", "Pinceau", "Pioche", "Pistolet", "Pistolet à colle", "Pistolet arroseur", "Pistolet à peinture", "Plane", "Plane de charron", "Plantoir", "Platoir", "Platoir à jointer", "Pochoir", "Poinçon", "Pointe", "Pointe à tracer", "Pointeau", "Pointerolle", "Polissoir", "Polka", "Pompe", "Ponceuse", "Ponceuse à bande", "Ponceuse excentrique", "Ponceuse orbitale", "Ponceuse vibrante", "Poste à souder", "Poste à souder à l'arc", "Presse\"15 lettresSuppliesType\" et \"SuppliesType6 lettres pour presseSuppliesType\"", "PressoirSuppliesType\"SuppliesType8 lettresSuppliesType\"", "Queue de rat", "Quart de pouce", "Raclette", "Raclette de carreleur", "Rabot", "Rabot-guillaume", "Rabot à moulure dit doucine", "Rabot à recaler", "Rabot à surfacer", "Racloir", "Ramponneau", "Râpe", "Rapporteur", "Rapporteur d'angles", "Raquette", "Raquette de carreleur", "Rasoir", "Râteau", "Règle", "Règle à calcul", "Réglet", "Réglet toupilleur", "Ressort à cintrer", "Rifloir", "Riveteuse", "Rouanne", "Rouet", "Roule", "Rouleau", "Rouleau à bras", "Sarcloir", "Scalpel", "Scarificateur", "Sceau", "Scie", "Scie à archet", "Scie à béton cellulaire", "Scie à bûches", "Scie à chantourner", "Scie à dos", "Scie à guichet", "Scie à métaux", "Scie à onglet", "Scie à panneaux", "Scie à queue d'aronde", "Scie à ruban", "Scie circulaire", "Scie cloche", "Scie d'élagage", "Scie de long", "Scie égoïne", "Scie passe-partout", "Scie sauteuse", "Scie vilebrequin", "Sciotte", "Sécateur", "Semoir", "Semoir à bras", "Semoir à main", "Serfouette", "Serpe", "Serpette", "Serre-fils", "Serre-joint", "Smille", "Soufflet", "Souffleuse", "Spatule", "Stylo", "Taille-haie", "Taillant", "Taloche", "Tamis", "Tampon", "Tamponnoir", "Taraud", "Tarière", "Té", "Tenaille", "Tendeur à feuillard", "Tête d'alésage", "Têtu de maçon", "Théodolite", "Tire fort", "Tondeuse à gazon", "Tour", "Tourne-à-gauche", "Tournevis", "Type", "Tournevis coudé", "Tournevis de précision", "Tournevis testeur", "Tournevis gainé", "Empreinte", "Tournevis empreinte fendue", "Tournevis empreinte cruciforme", "Tournevis empreinte étoile", "Tranchet", "Transplantoir", "Trépied", "Tronçonneuse", "Truelle", "Truelle à brique", "Truelle à joint", "Truelle d'angle", "Truelle Berthelet", "Truelle langue de chat", "Truelle triangulaire", "Trusquin", "Tyrolienne", "Urinette", "Valet", "Varlope", "Vé", "Verre", "Vilebrequin", "Vis d'Archimède", "Visseuse", "Vrille", "Wastringue"] ;
        $typeLabels = ["Pince", "Râteau", "Pelle", "Perceuse", "Visseuse", "Clous"] ;
        foreach ($typeLabels as $label) {
            $toolTip = new ToolType();
            $toolTip->setLabel($label);
            $manager->persist($toolTip);
            $tabToolType[] = $toolTip;
        }

        /// Tutorial fixtures
        for($i=0; $i<5; $i++) {
            $tutorial = new Tutorial();
            $tutorial->setTitle($faker->text(10))
                ->setType($faker->randomElement($tabTutorial))
                ->setDescription($faker->text(100))
                ->setUser($admin)
                ->setDisable($faker->boolean(20))
                ->setUpdatedAt($faker->dateTime('now'))
            ;
            // ([47.843601, 1.939258])
            $manager->persist($tutorial);
        }

        $states = ["Neuf", "Utilisé", "Usé", "Cassé"];
        foreach ($states as $etat) {
            $state = new State();
            $state->setLabel($etat);
            $manager->persist($state);
            $tabStates[]=$state;

        }

        // Item fixtures
        $items = [];
        for ($i=0; $i < 30; $i++) {
            $item = new Item();
            $item->setName($faker->name())
                ->setOwner($admin)
                ->setState($faker->randomElement($tabStates))
                ->setStatus(0)
                ->setCategory($faker->randomElement($tabToolType));
            $manager->persist($item);
            $items[] = $item;
        }

        // EventType Fixtures
        $eventTypes = [];
        for ($i=0; $i < 5; $i++){
            $eventType = new EventType();
            $eventType->setLabel($faker->word);
            $manager->persist($eventType);
            $eventTypes[] = $eventType;
        }

        // Event Fixtures
        $events = [];
        for ($i=0; $i < 50; $i++) {
            $event = new Event();
            $event
                ->setTitle($faker->sentence())
                ->setDescription($faker->paragraph())
                ->setEventAt($faker->dateTimeBetween('-2 days', '+1 week'))
                ->setIsPublished($faker->boolean(75))
                ->setPlace($faker->randomElement($places))
                ->setEventType($faker->randomElement($eventTypes))
            ;
            // Ajout des participants
            $participants = $faker->randomElements($users, rand(2,8));
            foreach ($participants as $participant) {
                $event->addParticipant($participant);
            }

            $organisers = $faker->randomElements($event->getParticipants(), rand(2, $event->getParticipants()->count()/2 ));
            foreach ($organisers as $organiser) {
                $event->addOrganiser($organiser);
            }

            $events[] = $event;
            $manager->persist($event);

        }

        // Partner Fixtures
        $partners = [];
        foreach (["Bricorama", 'Leroy Merlin', 'Brico dépôt'] as $item) {
            $p = new Partner();
            $p->setName($item)
                ->setIsActive(true);
            $partners[] = $p;
            $manager->persist($p);
        }

        // Rank Fixtures
        for ($i=1; $i < 6; $i++){
            $rank = new Rank();
            $rank->setName('niveau '. $i)
                ->setStart(($i * 150) - 150)
                ->setEnd($i * 150)
                ->setIsActive(1);
            $manager->persist($rank);
        }

        // ToCount Fixtures
        $toCounts = [];
        foreach (['tutorials seen', 'tutorials created', 'places shared', 'events created', 'events participated', 'tools shared'] as $libelle) {
            $toCount = new ToCount();
            $toCount->setLibelle($libelle);
            $toCounts[] = $toCount;
            $manager->persist($toCount);
        }

        // Reward Fixtures
        $rewards = [];
        for ($i=0; $i < 5; $i++) {
            $reward = new Reward();
            $reward->setIsActive(true)
                ->setLink($faker->words(3, true))
                ->setName($faker->word())
                ->setPartner($faker->randomElement($partners))
                ->setDescription($faker->paragraph());
            $rewards[] = $reward;
            $manager->persist($reward);
        }

        // Goal Fixtures
        for ($i=0; $i < 5; $i++) {
            $goal = new Goal();
            $goal->setLibelle($faker->name())
                ->setToCount($faker->randomElement($toCounts))
                ->setReward($faker->randomElement($rewards))
                ->setObjective($faker->randomDigitNot([0, null]))
                ->setDescription($faker->paragraph());
            $manager->persist($goal);
        }        

        $manager->flush();
    }
}

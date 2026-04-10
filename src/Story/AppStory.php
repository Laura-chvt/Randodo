<?php

namespace App\Story;

use App\Factory\HikeFactory;
use App\Factory\LocationFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {
        UserFactory::createMany(20);

        UserFactory::createOne([
            'email'     => 'teste@test.fr',
            'roles'     => ['ROLE_ADMIN'],
            'name'  => 'test',
            'firstname' => 'test'
        ]);

        $locationsData = [
            'ballons' => ['name' => 'Ballons des Vosges', 'gps' => '47.9000, 7.0000'],
            'nord'    => ['name' => 'Vosges du Nord', 'gps' => '48.9500, 7.4833'],
            'plaine'  => ['name' => 'Plaine d\'Alsace', 'gps' => '48.2500, 7.4500'],
            'kaysers' => ['name' => 'Vallée de Kaysersberg', 'gps' => '48.1393, 7.2625'],
            'champ'   => ['name' => 'Massif du Champ du Feu', 'gps' => '48.3999, 7.2667'],
        ];

        $locations = [];
        foreach ($locationsData as $key => $data) {
            $locations[$key] = LocationFactory::createOne($data);
        }

        $hikesData = [
            [
                'name' => 'Le tour du Lac Blanc', 'height' => 400, 'time' => 180, 'level' => 'Moyen',
                'length' => 8.5, 'family' => false, 'location' => $locations['ballons'],
                'description' => 'Une magnifique randonnée autour du célèbre Lac Blanc avec passage sur les crêtes.'
            ],
            [
                'name' => 'Ascension du Grand Ballon', 'height' => 600, 'time' => 300, 'level' => 'Difficile',
                'length' => 12.0, 'family' => false, 'location' => $locations['ballons'],
                'description' => 'Le point culminant des Vosges. Une vue imprenable sur les Alpes par temps clair.'
            ],
            [
                'name' => 'Promenade au Château du Haut-Kœnigsbourg', 'height' => 150, 'time' => 120, 'level' => 'Facile',
                'length' => 5.0, 'family' => true, 'location' => $locations['plaine'],
                'description' => 'Petite balade familiale autour de la forteresse la plus célèbre d\'Alsace.'
            ],
            [
                'name' => 'Le Sentier des Roches', 'height' => 550, 'time' => 240, 'level' => 'Difficile',
                'length' => 9.0, 'family' => false, 'location' => $locations['ballons'],
                'description' => 'Le sentier le plus alpin et spectaculaire du massif vosgien. Sensations garanties !'
            ],
            [
                'name' => 'La Cascade du Nideck', 'height' => 250, 'time' => 120, 'level' => 'Moyen',
                'length' => 6.0, 'family' => true, 'location' => $locations['nord'],
                'description' => 'Randonnée rafraîchissante menant à une belle cascade et aux ruines d\'un château.'
            ],
            [
                'name' => 'Autour du Mont Sainte-Odile', 'height' => 200, 'time' => 150, 'level' => 'Facile',
                'length' => 7.0, 'family' => true, 'location' => $locations['champ'],
                'description' => 'Découverte du Mur Païen et du monastère surplombant la plaine d\'Alsace.'
            ],
            [
                'name' => 'Le tour des 3 Lacs', 'height' => 750, 'time' => 360, 'level' => 'Difficile',
                'length' => 14.0, 'family' => false, 'location' => $locations['ballons'],
                'description' => 'Un grand classique exigeant : Lac Noir, Lac Blanc et Lac des Truites.'
            ],
            [
                'name' => 'Le Rocher de Dabo', 'height' => 100, 'time' => 90, 'level' => 'Facile',
                'length' => 4.0, 'family' => true, 'location' => $locations['nord'],
                'description' => 'Courte montée vers un promontoire de grès rose surmonté d\'une chapelle.'
            ],
            [
                'name' => 'Vignoble de Riquewihr', 'height' => 50, 'time' => 120, 'level' => 'Facile',
                'length' => 5.5, 'family' => true, 'location' => $locations['kaysers'],
                'description' => 'Balade gourmande au milieu des vignes l\'un des plus beaux villages de France.'
            ],
            [
                'name' => 'Le Hohneck par les crêtes', 'height' => 300, 'time' => 240, 'level' => 'Moyen',
                'length' => 10.0, 'family' => false, 'location' => $locations['ballons'],
                'description' => 'Rencontre avec les chamois sur le troisième sommet du massif.'
            ],
        ];

        foreach ($hikesData as $hike) {
            HikeFactory::createOne($hike);
        }
    }
}

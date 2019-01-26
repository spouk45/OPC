<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 15/09/2018
 * Time: 11:19
 */

namespace App\DataFixtures;


use App\Entity\Configuration;
use App\Entity\Extraction;
use App\Entity\HeatingSource;
use App\Entity\HeatingType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        foreach ($this->getHeatingTypeData() as [$name]) {
            $heatingType = new HeatingType();
            $heatingType->setName($name);
            $manager->persist($heatingType);
        }

        foreach ($this->getHeatingSourceData() as [$name]) {
            $heatingSource = new HeatingSource();
            $heatingSource->setName($name);
            $manager->persist($heatingSource);
        }

        foreach ($this->getExtractionTypeData() as [$name]) {
            $extractionType = new Extraction();
            $extractionType->setName($name);
            $manager->persist($extractionType);
        }

        foreach ($this->getParamData() as [$name,$value]) {
            $configuration = new Configuration();
            $configuration->setName($name);
            $configuration->setValue($value);
            $manager->persist($configuration);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            // ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            // ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['greg', 'admin', 'admin', 'greg@mongreg.fr', ['ROLE_ADMIN']],
        ];
    }

    private function getHeatingTypeData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['chaudière', 'chauffe eau', 'chauffe bain',],
        ];
    }

    private function getHeatingSourceData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['propane', 'gaz de ville', 'électrique',],
        ];
    }

    private function getExtractionTypeData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['conduit', 'ventouse'],
        ];
    }

    private function getParamData():array
    {
        return [
            // $userData = [$paramName, $value];
            ['API_KEY_GOOGLE_MAPS', ''],
            ['API_KEY_DEV_HERE', ''],
            ['APP_CODE_DEV_HERE', ''],
        ];
    }

}


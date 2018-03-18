<?php

namespace Smartie\DataFixtures;

use Smartie\Entity\AppRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class AppRoleFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $role = new AppRole();
        $role->setName('ROLE_USER');

        $manager->persist($role);
        $manager->flush();
    }
}
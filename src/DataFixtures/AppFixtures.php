<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder=$encoder;
    }


    public function load( ObjectManager $em)
    {
        // $product = new Product();
        // $manager->persist($product);

       

        $names=["admin1","admin2","admin3"];

        $roleRepository=$em->getRepository(Role::class);

        //$userRepository=$em->getRepository(User::class);

        $getAdminRole=$roleRepository->findOneBy(["id"=>1]);

       

        foreach($names as $index=>$name){
            $newUser=new User();
            $newUser->setfirstName($names[$index]);
            $newUser->setlastName($names[$index]);
            $newUser->setEmail("admin".$index."@admin.com");
            $newUser->setAddress("100 Adresse Fictif");
            $newUser->setZipCode("75000");
            $newUser->setTelephone("0608974755");
            $newUser->setCity("Paris");
            $plainPassword="password";
            $hashedPassword=$this->encoder->encodePassword($newUser,$plainPassword);
            $newUser->setPassword($hashedPassword);
            $newUser->setComment("this is a comment for an admin user, this is a comment for an admin user");
            $newUser->setRole($getAdminRole);
            $newUser->setIsHashed(true);
            $em->persist($newUser);
        }

        

        $em->flush();
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;



class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',TextType::class,['attr'=>["class"=>"d-flex w-200",],'label'=>"Prenom"])
            ->add('lastname',TextType::class,['attr'=>["class"=>"d-flex w-200"],'label'=>"Nom"])
            ->add('email',EmailType::class,["attr"=>["class"=>"d-flex w-200 email-class"],'label'=>"Email"])
            ->add('telephone',TextType::class,["attr"=>["class"=>"d-flex w-200"],'label'=>"Telephone"])
            ->add('address',TextType::class,["attr"=>["class"=>"d-flex w-200"],'label'=>'Address'])
            ->add('zipcode',TextType::class,["attr"=>["class"=>"d-flex w-200"],'label'=>'Code Postal'])
            ->add('city',TextType::class,["attr"=>["class"=>"d-flex w-200"],'label'=>'Ville'])
            ->add('comment',TextareaType::class,["attr"=>["class"=>"d-flex w-200","rows"=>5],'label'=>"laisser un commentaire"])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

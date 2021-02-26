<?php 
 namespace App\Controller;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\Routing\Annotation\Route;
 use App\Entity\User;
 use App\Entity\Role;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\HttpFoundation\Request;
 use App\Form\UserType;
 use App\Form\UserFindEmailType;
 

 class HomeController extends AbstractController{
     
    /**
     * @Route("/",name="home_page")
     */

     public function renderHome(Request $request){
         $user=new User();
         //creation formulaire de contact
         $form=$this->createForm(UserType::class,$user);
        
        $form->handleRequest($request);
         
        if($form->isSubmitted() && $form->isValid()){
            

            $errors=false;
            //controle champs telephone
            if(strlen($form->get('telephone')->getViewData())!=10){
                $errors=true;
                
                $this->addFlash("error","le numero de telephone doit contenir exactement 10 chiffres");
            }

            //si tout va bien

            if(!$errors){
               $em=$this->getDoctrine()->getManager();
               $roleRepository=$em->getRepository(Role::class);
               
               $getUserRole=$roleRepository->findOneBy(['roleTitle'=>'ROLE_USER']);
                //attribution par defaut des attributs du nouveau utilisateur 
               $user->setRole($getUserRole);

               $user->setPassword(false);

               $user->setIsHashed(false);
                // persist et insertion en bdd
               $em->persist($user);

               $em->flush();

               $this->addFlash("success","merci, votre message a bien éte prise en compte");
            }

            
        }

         

         return $this->render('home.html.twig',['form'=>$form->createView()]);
     }

     /**
      * @Route("/home/fill_email",name="fill_email")
      */

     public function fillEmail(Request $request){
        $form=$this->createForm(UserFindEmailType::class,null);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //dd($form->get("email")->getViewData());
           
            $email=$form->get("email")->getViewData();

            $em=$this->getDoctrine()->getManager();

            $userRepo=$em->getRepository(User::class);

            $findUser=$userRepo->findOneBy(["email"=>$email]);

            if($findUser){
                return $this->redirectToRoute("modify_information",["id"=>$findUser->getId()]);
            }

            else{
                throw new \Exception("Email not found");
            }
            
        }

        

        return $this->render('fill_email.html.twig',["form"=>$form->createView()]);
     }


     /**
      * @Route("/modify_form_information/{id}",name="modify_information")
      */

      public function modifyFormInformation($id,Request $request){
          $em=$this->getDoctrine()->getManager();

          $userRepo=$em->getRepository(User::class);

          $findUser=$userRepo->findOneBy(["id"=>$id]);

          $form=$this->createForm(UserType::class,$findUser);

          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()){
            $errors=false;

            if(strlen($form->get('telephone')->getViewData())!=10){
                $errors=true;
                
                $this->addFlash("error","le numero de telephone doit contenir exactement 10 chiffres");
            }

            if(!$errors){
                $em->persist($findUser);
                $em->flush();
                $this->addFlash("success","vous avez bien modifié vos information");
                return $this->redirectToRoute("home_page");
            }
          }

          return $this->render("modify_information.html.twig",["form"=>$form->createView()]);
      }
 }

?>
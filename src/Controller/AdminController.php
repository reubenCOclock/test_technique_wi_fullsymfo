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
 class AdminController extends AbstractController{ 
    /**
    * @Route("/admin_home",name="admin_home")
    */

    public function adminHomePage(){
        $em=$this->getDoctrine()->getManager();
        $userRepo=$em->getRepository(User::class);
    
        $users=$userRepo->findBy(["Role"=>1]);
        $arrayComments=[];
        foreach($users as $user){
            //dump($user->getComment());
            //dump(preg_split('/ [\n \r ]/',$user->getComment()));
            $commentToArray=explode("\n",$user->getComment());
            //$user->setComment($commentToArray);
          
            $arrayComments[]=$commentToArray;
        }

     

       

        
        return $this->render('/admin/admin_home.html.twig',["users"=>$users,"arrayComments"=>$arrayComments]);
    }

    /**
     * @Route("/admin/modifyUser/{id}",name="modify_user")
     */

    public function modifyAdmin($id,Request $request){
        $em=$this->getDoctrine()->getManager();
        $userRepo=$em->getRepository(User::class);

        $user=$userRepo->findOneBy(["id"=>$id]);

        $form=$this->createForm(UserType::class,$user);

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()){
             $em->persist($user);
             $em->flush();
             $this->addFlash("success","l'utilisateur a bien été modifié");
             return $this->redirectToRoute("admin_home");
         }

        return $this->render('admin/modify_user.html.twig',['form'=>$form->createView()]);
    }
    /**
     * @Route("/admin/deleteUser/{id}",name="delete_user")
     */

    public function deleteUser($id){
        $em=$this->getDoctrine()->getManager();
        $userRepo=$em->getRepository(User::class);
        
        $user=$userRepo->findOneBy(["id"=>$id]);

        $em->remove($user);
        $em->flush();

        $this->addFlash("success","utilisateur supprimé");
        
        return $this->redirectToRoute("admin_home");
    }


 }
 
 

?>
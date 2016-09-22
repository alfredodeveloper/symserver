<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
	

    public function pruebasAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
		$em = $this->getDoctrine()->getManager();
		$users=$em->getRepository('BackendBundle:User')->findAll();
		return $helpers->json($users);
		die();
    }
	
	public function loginAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
		$jwt_auth = $this->get("app.jwtAuth");
		
		$json =$request->get("json",null);
		
		if ($json != null){
			$params = json_decode($json);
			
			$email= !empty($params->email) ? $params->email : null;
			$password= !empty($params->password) ? $params->password : null;
			$getHash = !empty($params->gethash) ? $params->gethash : null;
			
			
			
			$emailConstraint = new Assert\Email();
			$emailConstraint->message ="This email is not valid.";
			
			$validate_email = $this->get("validator")->validate($email,$emailConstraint);
			
			if (count($validate_email) == 0 && $password != null){
				
				if ($getHash != null){
					$signup = $jwt_auth->signup($email,$password,1);
				} else {
					$signup = $jwt_auth->signup($email,$password);
				}
				
				return new \Symfony\Component\HttpFoundation\JsonResponse($signup);
			} else {
				return $helpers->json(array(
					"status"=>"error",
					"data"=>"Login not valid"
				));
				die();
			}
		} else {
			return $helpers->json(array(
					"status"=>"error",
					"data"=>"Send json with post"
				));
			die();
		}
		
		
    }
	
	
	
}

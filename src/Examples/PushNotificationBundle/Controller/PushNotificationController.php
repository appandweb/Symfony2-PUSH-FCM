<?php

namespace Examples\PushNotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Examples\PushNotificationBundle\Entity\PushMessagesAndroid;

class PushNotificationController extends Controller {
            
    public function sendPushMessageAction(Request $request) {
        
       if ($request->getMethod() != 'POST') {
           return new Response("Request is not POST");
       }
      
       $title = $request->request->get("title");
       $message = $request->request->get("message");
       
       if (!$title || !$message) {
           return new Response('Empty paramters');
       }
       $em = $this->getDoctrine()->getManager();
        
       $devices = $em->getRepository("PushNotificationBundle:Device")->findAll(); //we will send the notification to all devices
        
       if (!$devices) {
           return new Response("There aren't any devices registered");
       }
        
       $tokens = array(); //we must store the tokens which we want to send a message
       foreach ($devices as $d) {
           $tokens[] = $d->getToken();
       }
              
       $params = array ("title"    =>  $title,    "message"   =>  $message, "id"  => 55); // the id put whatever you want
              
       return new Response(PushMessagesAndroid::sendMessage($tokens, $params) );       
    }    
}
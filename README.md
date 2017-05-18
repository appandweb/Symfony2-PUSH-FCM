# Symfony2-PUSH-FCM
Server-side PUSH notifications using Firebase (FCM), implemented in Symfony2 with a POST request

In this repository, i will teach how implement PUSH notifications in a Symfony2's project

### Steps:

1. Create a new Bundle (this is not 100% necesary, but it is good to separate code of push notification from another functions )
2. Create a Entity that store the tokens of your users
3. Create a class to send push messages
4. Call your new class from a controller and return  a Response
5. Add new route to the routing.yml of your new bundle

## Create a new Bundle

To create a new bundle you only have yo run the next command in your project's folde:

php app/console generate:bundle

and ask some questions.

If this command does not work, you can create manually in litle steps:

note: In this example i will create a Bundle called PushNotificationBundle, you can name it as you want, but remember, the name **must** end with Bundle

1. Create new Folder called  PushNotificationBundle in your namespace, the route could be (ProjectDirectory/src/Examples/PushNotificationBundle)
After this step, all we will do , it will be inside the new PushNotificationBundle folder.
2. create a new php Class called PushNotificationBundle.php (or the same name as your bundle.)
``` [php]
<?php
namespace Examples\PushNotificationBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
class PushNotificationBundle extends Bundle {}
```
3. Register your new Bundle in app/Kernel.php
``` [php]
$bundles = array(
            ...           
            new Examples\PushNotificationBundle\PushNotificationBundle(),            
        );
```
4. create the new folders: Controller,Entity and Resources

Now, we have created a bundle.


## Create a Entity that store the tokens of your users

You must store the tokens of your user in the database, probably do you have one but i will create 1 of example, if  you have done your database schema ignore this step.

In this example we will create  a new Entity called Devices

create the new file in Entity folder

``` [php]
<?php


namespace Examples\PushNotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Examples\PushNotificationBundle\Entity\Device
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Device {
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id; // private or protected is necesary for doctrine
    
    /**
     * @var string $user
     *
     * @ORM\Column(name="user", type="string", length=150)
     */    
    //this could be a foreign key, but in this example we have used a string
    private $user;
    
    /**
     * @var string $token
     *
     * @ORM\Column(name="token", type="string", length=255)
     */        
    private $token;
    
    /**
     * @var string $trademark
     *
     * @ORM\Column(name="trademark", type="string", length=150)
     */        
    private $manufacturer;
    
    
    
    function getId() {
        return $this->id;
    }

    

    function getToken() {
        return $this->token;
    }

   function setToken($token) {
        $this->token = $token;
    }

    // Dont make setId, doctrine MUST do it for you

    

    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }

    
    function getManufacturer() {
        return $this->manufacturer;
    }

    function setManufacturer($manufacturer) {
        $this->manufacturer = $manufacturer;
    }

}

```

now we must update the doctrine schema with this command from our project's folder:

php app/console doctrine:schema:update --force

Now, our class is ready to store tokens of our users's devices


## Create a class to send push messages

Now we will create a new Class called PushMessagesAndroid.php in Entity folder

``` [php]
<?php

namespace Examples\PushNotificationBundle\Entity;

class PushMessagesAndroid {

    private static $androidAuthKey = "your api key here";
    
    public static function sendMessage($tokenArray,$params) {
        $data = array(
            'registration_ids' => $tokenArray, 
            'data' => $params
        );
        $headers = array(
            "Content-Type:application/json",
            "Authorization:key=" . self::$androidAuthKey
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        curl_close($ch);  
        
       return $result; //we return the response of the server, this will be the data thats the controller will return in a Response
    }       
}
```

## Call your new class from a controller and return  a Response

create a new controller called PushNotificationController.php in the Controller folder
``` [php]
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
```

## Add new route to the routing.yml of your new bundle

In our case we must create the routing.yml because our bundle is new

1. Create a new folder called 'config' in the Resources folder of our bundle
2. Create a new file inside config called 'routing.yml'
3. Create the route of our bundle in app/config/routing.yml
``` [yaml]
push_messages:
    resource: "@PushNotificationBundle/Resources/config/routing.yml"
    prefix:   /push-messages
```

4. Create the route to send push messages in the routing.yml of **OUR** bundle
``` [yaml]
push_notifications_send:
    path:     /send
    defaults: { _controller: PushNotificationBundle:PushNotification:sendPushMessage } 
                                 # BundleName:ControllerName:FunctionName
```

Now our web aplication can send push messages with Firebase
Thanks for read, if you see and error or anything , tell me and i correct or improve.
I expect that this tutorial can help you

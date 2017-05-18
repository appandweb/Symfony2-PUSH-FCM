# Symfony2-PUSH-FCM
Server-side PUSH notifications using Firebase (FCM), implemented in Symfony2

In this repository, i will teach how implement PUSH notifications in a Symfony2's project

### Steps:

1. Create a new Bundle (this is not 100% necesary, but it is good to separate code of push notification from another functions )
2. Create a Entity that store the tokens of your users
3. Create a class to send push messages
4. Call your new class from a controller and return  a Response
5. add new route to the routing.yml of your new bundle

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



## Create a class to send push messages

Now we will create a new Class called PushNotifications.php

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
     * @var string $owner
     *
     * @ORM\Column(name="owner", type="string", length=150)
     */    
    //this could be a foreign key, but in this example we have used a string
    private $owner;
    
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

    function getOwner() {
        return $this->owner;
    }

    function getToken() {
        return $this->token;
    }

   

    // Dont make setId, doctrine MUST do it for you

    function setOwner($owner) {
        $this->owner = $owner;
    }

    function setToken($token) {
        $this->token = $token;
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


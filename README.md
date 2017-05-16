# Symfony2-PUSH-FCM
Server-side PUSH notifications using Firebase (FCM), implemented in Symfony2

In this repository, i will teach how implement PUSH notifications in a Symfony2's project

### Steps:

1. Create a new Bundle
2. Create a class to send push messages
3. Call your new class from a controller and return  a Response
4. add new route in routing.yml of your new bundle

## Create a new Bundle

To create a new bundle you only have yo run the next command in your project's folde:

php app/console generate:bundle

if this command does not work, you can create manually in litle steps:

note: In this example i will create a Bundle called PushNotificationBundle, you can name it as you want, but remember, the name **must** end with Bundle

1. Create new Folder called  PushNotificationBundle in your namespace, the route could be (ProjectDirectory/src/Examples/PushNotificationBundle)
After this step, all we will do , it will be inside the new PushNotificationBundle folder.
2. create a new php Class called PushNotificationBundle.php
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

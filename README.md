#Simple PHP Wrapper for Feathers REST APIs

```include('feathers.php');
   try {
     $f = feathers::create([
         'host' => 'http://localhost:3030',
         'email' => 'email',
         'password' => 'password'
     ]);
   } catch(Exception $e) {
     echo die('Cound Not Authenticate');
   }
   var_dump($f->find('servicename'));
   ```
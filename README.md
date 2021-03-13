# PABBA Project

Default docker configuration : 
- Mysql <br>
   > **user** : root  
     **password** : root  
     **external-port** : 3350  
     **internal-port** : 3350   
     **url** : mysql://root:root@mysql:3306/pabba   
- PhpMyAdmin <br>
    > **host** : http://localhost:5003  
      **user** : root  
      **password** : root  
- Website <br>
    > http://localhost:33                   
## 1° Install the project
```yaml
composer install
yarn install 
yarn dev
docker-compose up -d
docker exec -it pabba_php bash # access to the php container
cd sf4
php bin/console doc:sc:up -f    
``` 
## 2° Create user
````yaml
php bin/console app:user:create #for normal user
php bin/console app:user:create --super-admin #for admin user
````
## 3° Configuration
```yaml
# file .env

MAILJET_APIKEY_PUBLIC=
MAILJET_APIKEY_PRIVATE=
```

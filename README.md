# Project description :

**BloodBank**

this project was build to help the HR of BS 23 to store bloodGroups and location of an employe.
so that in emergency when any of employee needs blood of specific type then HR could call out for help 
to that employee of having that specific blood type.

- **this projects provides:**
    1. Admin panel
    2. User panel

- **Admin panel:**
    1. can enter user (in bulk and in single)
    2. can edit any user
    3. can cange user role
    4. can ubdate own information
    5. can search for Donors
    6. can see own Donotion timeline

- **User panel:**
    1. can ubdate own information
    2. can search for Donors
    3. can see own Donotion timeline

# instalation guid `ubuntu`

1. **clone both projects from the link given below:**

    > [Click hear for `Api` project](https://github.com/TafhimFaisal/BloodBank_api)  <br>
    > [Click hear for `FrontEnd` project](https://github.com/TafhimFaisal/BloodBank_FronEnd) 
        
    > In `/var/www/html/` folder 

2. **create two file three file named**  

    - BloodBank.admin.conf
	- BloodBank.api.conf
	- BloodBank.conf

    >In `etc/nginx/sites-available/` folder

3. **Pest the code below in the file accordingly:**
 
    * **BloodBank.admin.conf:**

        ```
        server {
            
            listen 80; 
            
            root /var/www/html/bloodbank_frontend/;
            index index.html index.htm index.php admin-login.php;
            
            server_name BloodBank.admin www.BloodBank.admin; 
            
            
            
            location / {
                try_files $uri $uri/ /index.php?$query_string;
                autoindex on;
                autoindex_exact_size off;
            }
            
            location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php7.2-fpm.sock;
            }
            
            location ~ /\.ht {
                deny all;
            }
        }	
        ```
    * **BloodBank.api.conf:**

        ```
        server {
            
            listen 80; 
            
            root /var/www/html/bloodbank_api/public/;
            index index.html index.htm index.php;
            
            server_name BloodBank.api www.BloodBank.api; 

            location / {
                try_files $uri $uri/ /index.php?$query_string;
                autoindex on;
                autoindex_exact_size off;
            }
            
            location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php7.2-fpm.sock;
            }
            
            location ~ /\.ht {
                deny all;
            }
        }

        ```
    * **BloodBank.conf:**

        ```
        server {
            
            listen 80; 
            
            root /var/www/html/bloodbank_frontend/;
            index index.html index.htm index.php user-login.php;
            
            server_name BloodBank.com www.BloodBank.com; 
            
            location / {
                try_files $uri $uri/ /index.php?$query_string;
                autoindex on;
                autoindex_exact_size off;
            }
            
            location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php7.2-fpm.sock;
            }
            
            location ~ /\.ht {
                deny all;
            }
        }

        ```
4. **Run this code below :**

    - sudo ln -s /etc/nginx/sites-available/BloodBank.conf /etc/nginx/sites-enabled
    - sudo ln -s /etc/nginx/sites-available/BloodBank.admin.conf /etc/nginx/sites-enabled
    - sudo ln -s /etc/nginx/sites-available/BloodBank.api.conf /etc/nginx/sites-enabled
    
    <br>
5. **Run `sudo nano /etc/hosts` and** 

	- *`your ip`*     www.BloodBank.api
    - *`your ip`*     www.BloodBank.com
    - *`your ip`*     www.BloodBank.admin

    <br>
6. **give permission to the folder to the folder** 

	- sudo chmod -R 755 /var/www/html/
	- sudo chown -R $USER:$USER /var/www/html/
	- sudo chown -R $USER:$USER /var/www/html/bloodbank_api/public/


# **set up the lumen project:**

1. composer install
2. composer update
3. php artisan key:generate
4. create database
5. register the database name in .env file
6. php artisan jwt:secret
7. php artisan migrate:fresh --seed


        


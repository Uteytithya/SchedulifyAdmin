# SchedulifyAdmin

A backend for Schedulify build on laravel.

## Steps to Run the Project

1. clone the repository :

```
  https://github.com/Uteytithya/SchedulifyAdmin.git
```

2. Install the Dependencies :

```
   composer install 
   php artisan migrate 
   php artisan jwt:secret

```
3. Set up enviroment  : 
   
   Create a file : .env 


   Copy this file [Click here to open .env.example](./.env.example) to .env  
   
   Then, run the following command to generate key for application : 

   ```
      php artisan key:generate
   ```
4. Run the Project :

   ```
     php artisan serve
   ```


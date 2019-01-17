<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

</p>

## Installation

```bash
    composer install --optimize-autoloader --no-dev
```
```bash
    php artisan migrate
```

## List API
#1 REGISTER:
    Method URL: /api/register
    HTTP method: POST
    Content types: application/x-www-form-urlencoded, application/json
    Arguments: 
        name (varchar, required), 
        password (varchar, required), 
        email (varchar, required)

#2 LOGIN:
    Method URL: /api/login
    HTTP method: POST
    Content types: application/x-www-form-urlencoded, application/json
    Arguments: 
        name (varchar, required), 
        password (varchar, required)
        
#3 List Users:
    Method URL: /api/listUser
    HTTP method: GET
    Param: 
        key: token, value: {token response}
     
#4 get User By Id:
    Method URL: /api/user/{id}
    HTTP method: GET
    Param: 
        key: token, value: {token response}     
    Required: Admin token
     



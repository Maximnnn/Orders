Installation:

git clone https://github.com/Maximnnn/Printify.git

composer install

copy .env.example to .env

configure .env (database)

php artisan key:generate

php artisan migrate:refresh --seed

php artisan serve



Api Routes:

Registration:

POST api/register

Login:

POST api/login {email:"test@test.com", password: "qwerty"}    returns json{api_token: token}

Logout:

GET api/logout

Create Product:

POST api/products (required color,type,size,price, api_token)

Create Order:

POST api/orders (required api_token, products[] = [{id:product_id, count: some_integer}, {...}])

Get Orders:

GET api/orders?api_token=token

GET api/orders?api_token=token&type=some_type //to filter orders by product type

# weather-api

api imports and provides weather data

#1. prepare db

mysql docker container 

docker-compose up

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load

#2. import data command

app:data:import --country=DE  --city=Berlin

country, city optional 

#3. api

php bin/console server:start 

open api page - http://127.0.0.1:8000/api


# Australian Cities Weather App powered by Laravel

## § Features

* Basics: Laravel + Custom Command
* Third-party weather: OpenWeatherMap (https://openweathermap.org/)

## $ Quick Start

```sh
$ git clone https://github.com/JanetZhuo/weather-back-end.git
$ cd weather-back-end/

# Install dependencies
$ composer install

# Generate key
$ php artisan key:generate

# Run!
$ php artisan serve --port=8000

```
Please follow steps to run front end code https://github.com/JanetZhuo/weather-front-end

Then open http://localhost:3000

## $ Project Structure

Only main changes will be elaborated below.

```
Commands/
├── Weather # Custom command
Kernal # Schedule daily

Controllers/
├── ApiController # Has functions to send request to open weather and process the response
Middleware/
└── AccessControlAllowOrigin # Add cors middleware to able to communicate with open weather

routes/
└── api # Has two route which our front end code can hit

config/
└── weather # Config WEATHER_APP_ID here to protect key
```

## $ Console Application

You can use php artisan weather:forecast Command to get a report of weather in console.

Please enter Australia city names and seperate them with comma(no space).
```sh
# Feel free to run some test!
$ php artisan weather:forecast Canberra,Perth

# Will show err message said city not found
$ php artisan weather:forecast Ffdasgr

# Will show  message said the city does not belong to AU
$ php artisan weather:forecast Shanghai

```
<img width="936" alt="weather console demo" src="https://user-images.githubusercontent.com/45689833/121609665-af9b6000-ca97-11eb-9acf-303541f524a1.png">


## § TODO

* Weather data payload process improment
* More info & better table styling for console application
* Deployment

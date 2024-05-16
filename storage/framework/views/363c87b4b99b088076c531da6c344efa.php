<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            font-family: 'Arial', sans-serif;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            }

        /* Styling for the main container */
        .container {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Styling for the search card */
        .search-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .search-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Styling for card body */
        .card-body {
            padding: 20px;
        }

        /* Styling for form labels */
        .form-group label {
            font-weight: bold;
        }

        /* Styling for primary button */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s, border-color 0.3s;
        }

        /* Hover effect for primary button */
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b5;
        }

        /* Styling for secondary button */
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            transition: background-color 0.3s, border-color 0.3s;
        }

        /* Hover effect for secondary button */
        .btn-secondary:hover {
            background-color: #565e64;
            border-color: #4e555b;
        }

        /* Styling for card title */
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Styling for card text */
        .card-text {
            font-size: 1.1rem;
        }

        /* Styling for weather result container */
        #weatherResult {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding: 20px;
            display: none;
        }

        /* Styling for each forecast day */
        .forecast-day {
            display: inline-block;
            margin-right: 20px;
            padding: 30px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.80);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Styling for forecast container */
        #forecast {
            overflow: auto;
            white-space: nowrap;
        }

        /* Styling for last forecast day */
        .forecast-day:last-child {
            margin-right: 0;
        }

        /* Hover effect for forecast day */
        .forecast-day:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.2);
        }

        /* Styling for weather icons */
        .forecast-day img {
            margin-right: 10px;
            width: 80px;
            height: 80px;
            animation: fadeIn 1s ease-in-out;
        }

        /* Animation for weather icons */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Weather App</h1>
    <div class="search-card" id="searchCard">
        <div class="card-body">
            <form id="weatherForm">
                <div class="form-group">
                    <label for="city">Enter Municipality/Town name</label>
                    <input type="text" class="form-control" id="city" placeholder="e.g., New York">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Get Weather</button>
            </form>
        </div>
    </div>
    <div class="alert alert-danger" role="alert" id="errorAlert" style="display: none;">
        City not found. Please try again.
    </div>
    <div class="card" id="weatherResult" style="display: none;">
        <div class="card-body">
            <h5 class="card-title" id="weatherCity"></h5>
            <div id="forecast"></div>
            <button class="btn btn-secondary btn-block mt-3" id="backButton">Search Again</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('weatherForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const city = document.getElementById('city').value.trim();
        getWeather(city);
    });

    document.getElementById('backButton').addEventListener('click', function() {
        document.getElementById('weatherResult').style.display = 'none';
        document.getElementById('searchCard').style.display = 'block';
    });

    function getWeather(city) {
        const apiKey = '<?php echo e(env("OPENWEATHER_API_KEY")); ?>'; // Laravel Blade syntax to echo the environment variable
        const apiUrl = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&units=metric&appid=${apiKey}`;

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.cod === "200") {
                    document.getElementById('weatherCity').textContent = data.city.name;
                    displayForecast(data.list);
                    document.getElementById('weatherResult').style.display = 'block';
                    document.getElementById('searchCard').style.display = 'none';
                    document.getElementById('errorAlert').style.display = 'none';
                } else {
                    document.getElementById('weatherResult').style.display = 'none';
                    document.getElementById('errorAlert').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching weather data:', error);
                document.getElementById('weatherResult').style.display = 'none';
                document.getElementById('errorAlert').style.display = 'block';
            });
    }

    function displayForecast(forecastList) {
        const forecastElement = document.getElementById('forecast');
        forecastElement.innerHTML = '';
        const days = [];

        for (let i = 0; i < forecastList.length; i++) {
            const forecast = forecastList[i];
            const date = new Date(forecast.dt_txt).toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' });

            if (!days.includes(date) && days.length < 5) {
                days.push(date);

                const weatherIcon = `https://openweathermap.org/img/wn/${forecast.weather[0].icon}@2x.png`;

                const forecastDay = `
                    <div class="forecast-day" style="background-color: ${getBackgroundColor(forecast.weather[0].main)};">
                        <img src="${weatherIcon}" alt="${forecast.weather[0].description}">
                        <div>
                            <p class="card-text"><strong>${date}</strong></p>
                            <p class="card-text">${forecast.weather[0].description}</p>
                            <p class="card-text"><strong>Temp:</strong> ${forecast.main.temp}°C</p>
                            <p class="card-text"><strong>Wind Speed:</strong> ${forecast.wind.speed} m/s</p>
                            <p class="card-text"><strong>Humidity:</strong> ${forecast.main.humidity}%</p>
                            <p class="card-text"><strong>UV Level:</strong> ${getUVLevel(forecast.uvIndex)}</p>
                        </div>
                    </div>
                `;

                forecastElement.insertAdjacentHTML('beforeend', forecastDay);
            }
        }
    }

    function getUVLevel(uvIndex) {
        // Determine UV level based on UV index
        if (uvIndex < 3) {
            return 'Low';
        } else if (uvIndex < 6) {
            return 'Moderate';
        } else if (uvIndex < 8) {
            return 'High';
        } else if (uvIndex < 11) {
            return 'Very High';
        } else {
            return 'Extreme';
        }
    }

    function getBackgroundColor(weather) {
        switch(weather.toLowerCase()) {
            case 'clear':
                return '#99FFFF'; // Shade of blue
            case 'clouds':
                return '#A8A8A8'; // Gray66
            case 'rain':
                return '#7885AB'; // ShadowBlue
            case 'snow':
                return '#fffafa'; // Snow
            case 'thunderstorm':
                return '#b0c4de'; // LightSteelBlue
            case 'drizzle':
                return '#afeeee'; // PaleTurquoise
            default:
                return '#f5f5f5'; // WhiteSmoke
        }
    }
</script>


</body>
</html>
<?php /**PATH C:\xampp\htdocs\WeatherApp\resources\views/weather.blade.php ENDPATH**/ ?>
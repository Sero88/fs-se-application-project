This app uses PHP and Apache. To run, simply place the folder web-app-sesquivel in your web root folder, examples: 

public_html/web-app-sesquivel
httpdocs/web-app-sesquivel
www/web-app-sesquivel
Localhost:8080/web-app-sesquivel


To see this app in action please visit: esergio.com/web-app-sesquivel.


Potential improvements
If this app were to run in a production environment, I would set up a database to save data retrieved from the API.
I would then set a cron job to fetch the data every hour or so. 
This way, speed of web app would improve since it wouldn’t have to make calls to the API every time there is a request. 
Also, this would prevent the web app from breaking whenever the API is down since it would have the local data to work with.
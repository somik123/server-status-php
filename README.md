# server-status
Just add it to your prefered folder and open it in browser. 

<img src="https://raw.githubusercontent.com/somik123/server-status-php/main/screenshot.png" />


Requrires PHP to be configured as per this setup: https://somik.org/ubuntu-22-04-nginxphp/

(Can replace nginx with apache or lighttpd or something else)

For testing purpose, run this command once inside the folder: 
`php -S localhost:8080`

Do note that it is not recommended to run this command on production servers. 

If you need to access it from another server, run with the command: 
`php -S 0.0.0.0:8080`


And then access it over http://localhost:8080/ or http://serverIP:8080/


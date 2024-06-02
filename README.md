# OwnShare - open-source filesharing

**This project is in a very early stage and under active development!**

OwnShare is an open-source filesharing software that can be used as a direct alternative to services like wetransfer or workupload. It used PHP as its backend and static HTML as its frontend so that it can be deployed anywhere. The frontend is built using SvelteKit and build using its static adapter.

## Features
![ownshare_3](https://github.com/Das-Felix/ownshare/assets/62439997/16a9b33a-69ad-42cd-9998-2cb20cf65157)
![ownshare_1](https://github.com/Das-Felix/ownshare/assets/62439997/2f518b0c-e9d5-4d46-acce-bbf2747173e9)
![ownshare_4](https://github.com/Das-Felix/ownshare/assets/62439997/c777e5d5-49aa-4aa8-8c3f-cf21acc9bf0e)
![ownshare_5](https://github.com/Das-Felix/ownshare/assets/62439997/a52fecb2-e6c2-4271-bbc3-9dbefa6808b2)
![ownshare_6](https://github.com/Das-Felix/ownshare/assets/62439997/26852954-9cfc-4f76-9e99-1afe620e0437)

##Custom Theme
You can customize the look of your download page by editing the template.html and style.css file inside the theme folder. The template.html file uses Handlebars as its template engine. You can look into the default theme files to see the structure.

## Installation

Requirements: PHP Environment, MySQL Database

1. Download the latest release and upload it to your PHP Environment
2. Edit the config.json file in OwnShares root folder and change the backendAddress to point to the api folder. This address has to be an absolute URL starting with “https://”
3. Edit the config.php file in the api folder. Enter your Database credentials there. Set APP_CORS_URLS to “”. This field only has to be set if your backend runs on a different domain than your frontend.
4. go to <ownshare root>/api/installer.php if everything is set correctly you should get a success screen informing you of the default credentials. You can then login at <ownshare root>/auth/login

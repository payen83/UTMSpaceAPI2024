# UTMSpaceAPI2024
 
How to use this source code

1. Install XAMPP, and start the Apache and mySQL

2. Download this source code as zip

3. Extract the zip file in c:\xampp\htdocs (or in Mac under Application ->MAMP->htdocs)

4. Rename the root folder as 'api'

5. At browser, go to http://localhost/phpmyadmin (http://localhost:8888/phpmyadmin for macOS user)

6. At the middle of the screen, go to SQL tab, type the SQL queries below:

 CREATE DATABASE ispaceDB;

7. Then click button GO at the bottom

8. Click on the database "ispaceDB" at left side bar.

9. Then, at the menu tab in the middle, find the tab name export 

10. Upload sql file "ispaceDB.sql" and click export


How to Test the API
- Download and install Postman, make sure you sign up and log in.
- Open Postman, click on import, then choose filename "api.postman_collection_windows.json" (for Windows) or "ispace api.postman_collection_macOS.json" (for macOS)  in \api folder 
- Run and test API!

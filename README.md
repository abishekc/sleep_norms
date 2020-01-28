Version 1.0

[changelog]: tbd

[Local Server Setup]:
Windows:
The latest downloads of MAMP (My Apache - MySQL - PHP server) are located at:
    - https://www.mamp.info/en/downloads/

Download the latest version, follow set up and open preferences pane.
Note: MAMP Pro is not required in order for the simple server to work.

After opening the preferences window, the Ports Tab should be defined as follows:
- Apache Port: 8888
- Nginx Port: 8888
- MySQL Port: 8889

Under the Web Server Tab:
    - Web Server should be set to Apache
    - The document root shuold be set to the folder in which the project resides.
    - It is important that the root is the folder in which 'index.php' is defined.
    
After these settings have been chosen, move the 'my.cnf' from the root folder to this path:
- macOS:
    ~\Applications\MAMP\conf\
- Windows:
    \Where MAMP is installed\conf\

This configuration file allows the dataset to be uploaded. Now we must set up the database in order to get our website working!

Fire up MAMP and hit start servers. It will open a webpage (it may take a while the first time) and on the left column, there is a link to open the phpMyAdmin page. Click the link and you should be greeted with a new tab that allows database preferences to be set and viewed.

At the top, open the 'User Accounts' Tab. Click on 'Add user account'
The setting should be as follows:
- User name: sleep
- Password: password

IMPORTANT: leave the other login information to their defaults. Click the 'Check All' button near Global Privileges in order to grant all priveliges to this user account.

Clikc Go at the bottom in order to set the user account.

Once done, click the 'Databases' tab at the top of the webpage. There should be an option to create a database near the top. Under the 'Database Name' textfield, enter 'myDB' and make sure the dropdown is set to 'utf8_general_ci' and click create.

Once done, click on 'myDB' in the table below. This is now the seetings page for this particular database. At the top again, there is an 'Import' tab, click on this tab in order to upload the data. 

Choose the df_all.csv file to upload. The format should be selected .csv. Leave the default options except for 'Lines terminated with' which should be '\n' or 'auto'. The first option that says 'The first line of the file onctains the table column names', should be checked. Click Go. This process will take a while, do NOT close thewindow or reload the page, you will have to do the entire process once again. After doing so, there should be an TABLE 1 option under myDB under the file structure on the left pane of the window. Click on this table and you should see all the columns and their names and variable types. If you click the 'Browse' option near the top, you can scroll through all the data uploaded.

After all these setps are completed, you can go ahead and open up your prefered browser and in the address bar, type in 'localhost' or 'localhost:8888' or 'localhost:8889'. One of these options should work. Otherwise the TCP/IP ports maybe be in use already.t


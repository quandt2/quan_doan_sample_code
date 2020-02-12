1. property-data-display contains exercise 1, magic-key contains exercise 2
2. unrar property-data-display
3. setup config.yaml file
	mysql:
    		connect: mysql:host=localhost:3306;dbname=amarki	=> host of mysql, port and db name 
    		user: root						=> user to login mysql
    		pass: root						=> password to login mysql
    		options:						=> option to use PDO to access MySQL
      			PDO::ATTR_ERRMODE: PDO::ERRMODE_EXCEPTION
      			PDO::ATTR_DEFAULT_FETCH_MODE: PDO::FETCH_NUM
	log:								=> log config
  		channel: amarki    					=> log channel name
  		file: app.log						=> log file name (in folder src/Log/app.log)
4. setup magicKeyConfig.yaml file
	link: http://localhost:8000/magicKeyGen.php			=> link to Magic Key API
	salt: thisisMagicKeyToday					=> salt value to gen Magic Key

5. set up server to use property-data-display/index.php with Apache2, NginX and PHP5.6
	for example: http://localhost/index.php
6. unrar magic-key to set up another host for magic Key API 
	for example: http://localhost:8000/magicKeyGen.php
7. create DB with the same name in config file, import data for table property, this is a must.
8. use browser to launch http://localhost/index.php
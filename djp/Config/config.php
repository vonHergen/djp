<?php

[lwdb]
type = "mysqli"
prefix = ""
user = "root"
pass = ""
host = "localhost"
name = "djpdb"      

[path]
client = /var/www/djp/djp
media = /var/www/djp/djp/Media/

[url]
domain = "http://localhost/"
client["base"] = "http://localhost/djp/"
client["index"] = "http://localhost/djp/index.php"
client["admin"] = "http://localhost/djp/admin.php"
media = "http://localhost/djp/Media/"
pics = "http://localhost/djp/Media/images/"

[roles]
conflfield = "1";
confuser = "2";
confeducation = "2";
confrole = "2";
confsubject = "1";


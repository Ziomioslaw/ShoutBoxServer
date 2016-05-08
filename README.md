# ShoutBoxServer
PHP ShoutBox server.

Based on [Flight](http://flightphp.com/) PHP microframework.

## Installation

After moving into server require changes in .htaccess:
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ api.php [QSA,L]
```

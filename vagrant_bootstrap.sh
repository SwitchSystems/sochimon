#!/usr/bin/env bash

# Install packages
aptitude update
aptitude safe-upgrade -y
aptitude install -y apache2 php5 php5-intl php5-memcached php5-xdebug php5-cli php5-mcrypt php5-mysql memcached ssmtp


#Enable apache modules
a2enmod rewrite

#Create apache config
echo "<VirtualHost *:80>
	ServerName sochimon.vagrant

	SetEnv APPLICATION_ENV dev

	DocumentRoot /vagrant/public/
	<Directory />
		Options FollowSymLinks
		AllowOverride None
	</Directory>
	<Directory /vagrant/public/>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride none
		Order allow,deny
		allow from all

		RewriteEngine On
		# Remove trailing slash from all requests (unless they are actual directories)
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule ^(.+[^/])/$ /$1 [R=301,L]
		# Redirect all requests to index.php (unless they are actual directories or files)
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule ^.*$ index.php [NC,L]
	</Directory>

	ErrorLog /var/log/apache2/sochimon_error.log

	# Possible values include: debug, info, notice, warn, error, crit,
	# alert, emerg.
	LogLevel warn

	CustomLog /var/log/apache2/sochimon_access.log combined


</VirtualHost>" > /etc/apache2/sites-available/sochimon
a2ensite sochimon

#Update PHP config
echo "xdebug.default_enable = 1
xdebug.max_nesting_level = -1
xdebug.var_display_max_children = -1
xdebug.var_display_max_data = -1
xdebug.var_display_max_depth = -1
xdebug.remote_enable = On
xdebug.show_exception_trace = Off
xdebug.remote_connect_back = On
xdebug.remote_autostart = Off
xdebug.remote_handler=dbgp
xdebug.idekey = vagrant
xdebug.remote_log = /var/log/apache2/xdebug.log
xdebug.overload_var_dump = 1
xdebug.profiler_output_name = "cachegrind.out.%t-%s"
xdebug.profiler_output_dir = /var/log/apache2/profile
xdebug.profiler_enable = 1
xdebug.profiler_enable_trigger = 1
xdebug.trace_format = 1
xdebug.auto_trace = 0" >> /etc/php5/conf.d/20-xdebug.ini

echo "error_reporting = E_ALL | E_STRICT
display_errors = On
display_startup_errors = On
html_errors = On
user_agent=\"PHP\"
post_max_size = 10M
upload_max_filesize = 10M" >> /etc/php5/apache2/php.ini

service apache2 restart

# file permissions
chmod 777 /vagrant/files

echo "PATH=$PATH:/vagrant/vendor/bin
export PATH" >> /home/vagrant/.bashrc

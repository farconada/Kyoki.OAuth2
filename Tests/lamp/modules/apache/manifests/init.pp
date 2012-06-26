# Class: apache
#
# This module manages apache
#
# Parameters:
#
# Actions:
#
# Requires:
#
# Sample Usage:
#
# [Remember: No empty lines between comments and class definition]
class apache {
	package { "apache2":
		ensure => present,
		require => Exec["apt-get update"]
	}
	
	file { "/etc/apache2/mods-enabled/rewrite.load":
	    ensure => link,
	    target => "/etc/apache2/mods-available/rewrite.load",
	    require => Package["apache2"]
	}

	file { "/etc/apache2/sites-available/default":
	    ensure => present,
	    source => "/vagrant/modules/apache/files/webroot",
	    require => Package['apache2'],
	  }
	
	service { "apache2":
		ensure => running,
		require => Package["apache2"],
		subscribe => [
		      File["/etc/apache2/mods-enabled/rewrite.load"],
		      File["/etc/apache2/sites-available/default"]
		    ]
		
	}

}
class { "apache": stage => "pre" }

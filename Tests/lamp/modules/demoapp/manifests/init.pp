class demoapp {


	file {'/var/www/FLOW3/Packages/Application/Acme.Demoapp':
	    ensure => directory,
		source => '/vagrant/Acme.Demoapp',
		recurse => true,
		owner => 'www-data',
		group => 'www-data',
		require => File['/var/www/FLOW3/Packages/Application'],
		notify => [Exec['flow3-dbupdate'],Exec['flow3-permissions']]

	}
}
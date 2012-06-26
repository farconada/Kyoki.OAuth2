class oauth {
	exec {'kyoki-dbupdate':
		path => ['/usr/bin/','/bin/','/var/www/FLOW3'],
		command => 'flow3 doctrine:update',
		cwd => '/var/www/FLOW3',
		require => Exec['flow3-checkout']
		
	}

	exec {'kyoki-oauth2':
		command => '/usr/bin/git clone git://github.com/farconada/Kyoki.OAuth2.git /var/www/FLOW3/Packages/Applications/Kyoki.OAuth2',
		require => File['/var/www/FLOW3/Packages/Application'],
		creates => '/var/www/FLOW3/Packages/Applications/Kyoki.OAuth2',
		notify => Exec['kyoki-dbupdate']

	}
	
	file {'flow3-settings':
		path => '/var/www/FLOW3/Configuration/Settings.yaml',
		content => template('/vagrant/modules/oauth/files/Settings.erb'),
		notify => [Exec['kyoki-dbupdate'],Exec['flow3-cacheflush']],
		require => Exec['kyoki-oauth2']
		
	}
	
	file {'/var/www/FLOW3/Configuration/Routes.yaml':
		ensure => present,
		content => '/vagrant/modules/oauth/files/Routes.yaml',
		require => Exec['kyoki-oauth2'],
		notify => [Exec['kyoki-dbupdate'],Exec['flow3-cacheflush']],
	}
	
	mysqldb { $flow3db_name:
		user => $flow3db_username,
		password => $flow3db_passwd
	}
}

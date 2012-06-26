class oauth {


	exec {'kyoki-oauth2':
		command => '/usr/bin/git clone git://github.com/farconada/Kyoki.OAuth2.git /var/www/FLOW3/Packages/Applications/Kyoki.OAuth2',
		require => File['/var/www/FLOW3/Packages/Application'],
		creates => '/var/www/FLOW3/Packages/Applications/Kyoki.OAuth2',
		notify => Exec['flow3-dbupdate']

	}
	
	file {'flow3-settings':
		path => '/var/www/FLOW3/Configuration/Settings.yaml',
		content => template('/vagrant/modules/oauth/files/Settings.erb'),
		notify => [Exec['flow3-dbupdate'],Exec['flow3-cacheflush']],
		require => Exec['kyoki-oauth2']
		
	}
	
	file {'/var/www/FLOW3/Configuration/Routes.yaml':
		ensure => present,
		source => '/vagrant/modules/oauth/files/Routes.yaml',
		require => Exec['kyoki-oauth2'],
		notify => Exec['flow3-cacheflush'],
	}

}

class flow3 {
	exec {'flow3-dbupdate':
    		path => ['/usr/bin/','/bin/','/var/www/FLOW3'],
    		command => 'flow3 doctrine:update',
    		cwd => '/var/www/FLOW3',
    		require => Exec['flow3-checkout']

    	}

	exec {'flow3-clone':
		command => '/usr/bin/git clone git://git.typo3.org/FLOW3/Distributions/Base.git /var/www/FLOW3 --recursive',
		creates => '/var/www/FLOW3'
	}
	
	exec {'flow3-update':
		cwd => '/var/www/FLOW3',
		command => '/usr/bin/git submodule foreach "git pull origin master"',
		onlyif => 'ls -l /var/www/FLOW3/.git'
	}
	
	exec {'flow3-checkout':
		cwd => '/var/www/FLOW3',
		command => '/usr/bin/git checkout $flow3VersionTag ',
		require => Exec['flow3-clone']
	}
	
	exec {'flow3-permissions':
		command => 'chown www-data:www-data -R /var/www/FLOW3',
		require => Exec['flow3-checkout']
	
	}

	file {'/var/www/FLOW3/Packages/Application':
		ensure => directory,
		require => Exec['flow3-clone']
	}
	
	exec {'flow3-cacheflush':
		path => ['/usr/bin/','/bin/','/var/www/FLOW3'],
		command => 'flow3 flow3:cache:flush',
		cwd => '/var/www/FLOW3',
		require => Exec['flow3-checkout']
		
	}

	mysqldb { $flow3db_name:
    		user => $flow3db_username,
    		password => $flow3db_passwd
    	}
}

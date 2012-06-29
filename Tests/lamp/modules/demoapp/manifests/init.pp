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

	exec {'demoapp-createaccount':
    		path => ['/usr/bin/','/bin/','/var/www/FLOW3'],
    		command => 'flow3 init:createaccount',
    		cwd => '/var/www/FLOW3',
    		require => [Exec['flow3-checkout'], File['/var/www/FLOW3/Packages/Application/Acme.Demoapp'],Exec['flow3-dbupdate']]

    	}

    exec {'demoapp-createapi':
    		path => ['/usr/bin/','/bin/','/var/www/FLOW3'],
    		command => 'flow3 init:createclientapi',
    		cwd => '/var/www/FLOW3',
    		require => [Exec['flow3-checkout'], File['/var/www/FLOW3/Packages/Application/Acme.Demoapp'],Exec['flow3-dbupdate']]

    	}
    exec {'demoapp-createscope':
    		path => ['/usr/bin/','/bin/','/var/www/FLOW3'],
    		command => 'flow3 init:createscope',
    		cwd => '/var/www/FLOW3',
    		require => [Exec['flow3-checkout'], File['/var/www/FLOW3/Packages/Application/Acme.Demoapp'],Exec['flow3-dbupdate']]

    	}

    file {'/var/www/FLOW3/Web/client':
    	    ensure => directory,
    		source => '/vagrant/client',
    		recurse => true,
    		owner => 'www-data',
    		group => 'www-data',
    		require => Exec['flow3-checkout']
    	}
}

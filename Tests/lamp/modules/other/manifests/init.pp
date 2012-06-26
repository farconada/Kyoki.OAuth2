class other {
  $packages = ["curl", "vim", "git-core"]
  package { $packages:
    ensure => present,
    require => Exec["apt-get update"]
  }
}

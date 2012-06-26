Exec {
  path => ["/usr/bin", "/bin", "/usr/sbin", "/sbin", "/usr/local/bin", "/usr/local/sbin"]
}
stage { "pre": before => Stage["main"] }

$flow3VersionTag = 'FLOW3-1.1.0-beta3'
$mysqlpw = "toor"
$flow3db_name = "flow3db"
$flow3db_passwd = "flow3passwd"
$flow3db_username = "flow3user"

include bootstrap
include other
include apache
include php
include mysql
include flow3
include oauth
include demoapp

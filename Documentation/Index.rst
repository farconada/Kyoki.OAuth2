OAuth 2.0 provider
============================

Domain Objects
------------------------
OAUthClient
It's the API identifier. It has:

 - id: API identifier
 - secret: API secret "password"
 - redirectUri: only API requests that match this URI are allowed
 - description
 - party (AbstractParty): the party who created the API

An API could be issued by the Admin by a commandline and common in every mobile app that consumes the website services

OAuthScope
When you request access to a resource you are asking for an access level. This access level is determined by the scope.
The scope is just and string that must match

End points
-------------------------


Configuration
-------------------------
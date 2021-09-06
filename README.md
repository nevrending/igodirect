# iGoDirect Group - Developer Task

![PHP](https://img.shields.io/badge/php-%5Ev7.4-blue?style=flat-square)

## Requirements

1. PHP ^7.4
2. `bcmath` and `pdo_mysql` PHP extensions
3. MySQL or MariaDB or similar
4. Composer ^2
5. Google Maps API Key

## Setup

1. Create a new database and import `database.sql`
2. Duplicate `.env.example` into `.env`
3. Fill the DB connection details and Google Maps API Key in `.env` file
4. Run `composer install`
5. The application should now be accessible from your FQDN

## Caveats

1. Redirects made with assumption that this application will be accessed from an FQDN or a subdomain. e.g `igodirect.test` or `igodirect.yefta.com` or any other FQDNs

## Future Improvements

The following can be made as future improvements to this application, including but not limited to:

1. Account Deletion
2. 2FA Recovery Codes
3. Password strength is currently set at minimum of 8 characters for ease of development and testing, but later on it would be better to add requirement that it needs alphanumeric and symbols combination

# Author

[Yefta Sutanto](https://github.com/nevrending)

# Copyright

2021 &copy; Yefta Sutanto

http://dev.maxmind.com/geoip/legacy/install/country/

Step 1 – Download database

For GeoIP clients, go to the download files (https://www.maxmind.com/en/download_files) page. You may need to enter your username and password. From there, you can download the binary or CSV formats of the GeoIP databases you purchased. Then you will want to upload the databases to your web server.

If you are not a client, you can use the free GeoLite database. You may download it from the GeoLite page.

If you are using the wget program to download the GeoLite file, please use the -N option to only download if the file has been updated:

$ wget -N http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz
# Required tools
* curl for Windows: https://curl.se/windows/ 
* Bruno for API testing: https://www.usebruno.com/downloads or
* Postman https://www.postman.com/downloads/
* Tooling for C# development 
    * .NET SDK
    * IDE (e.g. Microsoft VS Code: https://code.visualstudio.com/ with C# Dev Kit extension )
* To run applications as containers, you need Podman: https://github.com/containers/podman-desktop/releases/download/v1.27.1/podman-desktop-1.27.1-setup.exe
 
# API docs
* https://documentation.prod.parking.scheidt-bachmann.net/docs/tutorial/authentication-guide#accessing-the-swagger-documentation
* Access token URL: https://auth.preprod.parking.scheidt-bachmann.net/auth/realms/de_studentui/protocol/openid-connect/token
* Get Facility: https://pm.preprod.parking.scheidt-bachmann.net/capacity-manager/v2/de_studentui/occupancy/facilities
* Facility counters: https://pm.preprod.parking.scheidt-bachmann.net/capacity-manager/v2/de_studentui//occupancy/standardcounters/:locationId

# Postman collection
* [Postman](./docs/Parking%20API.postman_collection.json)

# Get JSON instead XML
Format of returned data is controlled by HTTP (GET) Request Header: _Accept_
* Use `Accept: aplication/json` to get data in JSON
* Use `Accept: aplication/xml` to get data in XML

Format of sent data is also contolled by HTTP (POST) Request Header: _Content-Type_
* Use `Content-Type: aplication/json` if Request Body contains JSON
* Use `Content-Type: aplication/xml` if Request Body contains  XML



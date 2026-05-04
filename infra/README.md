# How to run Envoy proxy
* Install [Podman](https://github.com/containers/podman-desktop/releases/download/v1.27.1/podman-desktop-1.27.1-setup.exe) 
* Replace <put-secret-here> in _envoy.yaml_ with OAuth client secret
* From this directory run `podman-compose up`  
* Another option is to:
  * Install [Docker](https://docs.docker.com/desktop/setup/install/windows-install/) only
  * Run Envou as: 
```
docker run --rm -it ^
  -p 9080:8080 ^
  -v %cd%\envoy.yaml:/etc/envoy/envoy.yaml ^
  envoyproxy/envoy:v1.38-latest
```
 
# How to access API
* Use _http://localhost:9080_ instead _https://pm.preprod.parking.scheidt-bachmann.net_ 
* Example: http://localhost:9080/capacity-manager/v2/de_studentui/occupancy/facilities


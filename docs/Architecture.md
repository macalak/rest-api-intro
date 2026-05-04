# Application Architecture

## Envoy as Backend For Frontent 
Envoy acts as a backend for frontend (BFF), uses OAuth 2.0 Client Credentials grant to obtain an 
access token from an IdP (e.g. Keycloak), automatically refreshes it, and injects it into every 
upstream API request as an Authorization: Bearer … header.

![bff](./bff-architecture.png)

* Token acquisition handled entirely by Envoy
* Automatic refresh before expiry
* Frontend never sees tokens
* Backend API = Parking API

## Envoy configuration

```
static_resources:
  listeners:
    - name: http_listener
      address:
        socket_address:
          address: 0.0.0.0
          port_value: 8080
      filter_chains:
        - filters:
            - name: envoy.filters.network.http_connection_manager
              typed_config:
                "@type": type.googleapis.com/envoy.extensions.filters.network.http_connection_manager.v3.HttpConnectionManager
                stat_prefix: ingress_http
                route_config:
                  name: local_route
                  virtual_hosts:
                    - name: backend
                      domains: ["*"]
                      routes:
                        - match:
                            prefix: "/"
                          route:
                            cluster: backend_api
                http_filters:
                  # 🔐 OAuth2 token injection
                  - name: envoy.filters.http.credential_injector
                    typed_config:
                      "@type": type.googleapis.com/envoy.extensions.filters.http.credential_injector.v3.CredentialInjector
                      overwrite: true
                      credential:
                        name: envoy.http.injected_credentials.oauth2
                        typed_config:
                          "@type": type.googleapis.com/envoy.extensions.http.injected_credentials.oauth2.v3.OAuth2
                          token_endpoint:
                            uri: http://keycloak:8080/realms/demo/protocol/openid-connect/token
                            cluster: oauth_token
                            timeout: 3s
                          client_credentials:
                            client_id:
                              inline_string: envoy-bff
                            client_secret:
                              inline_string: envoy-bff-secret
                          scopes:
                            - api.read
                  - name: envoy.filters.http.router

  clusters:
    # Backend API
    - name: backend_api
      connect_timeout: 5s
      type: logical_dns
      lb_policy: round_robin
      load_assignment:
        cluster_name: backend_api
        endpoints:
          - lb_endpoints:
              - endpoint:
                  address:
                    socket_address:
                      address: backend
                      port_value: 8081

    # OAuth2 token endpoint
    - name: oauth_token
      connect_timeout: 5s
      type: logical_dns
      lb_policy: round_robin
      load_assignment:
        cluster_name: oauth_token
        endpoints:
          - lb_endpoints:
              - endpoint:
                  address:
                    socket_address:
                      address: keycloak
                      port_value: 8080
```

* Envoy fetches token using client credentials
* Caches token internally
* Refreshes token automatically before expiry
* Injects Authorization: Bearer <token> into every reques



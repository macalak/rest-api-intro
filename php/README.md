# How PHP web application differs from SPA Java Script application

The main difference lies in where the application logic runs and how the HTML is generated. A modern JavaScript Single-Page Application (SPA) 
shifts the rendering and user experience entirely to the user's web browser, while a traditional PHP application handles page generation 
on the web server before sending it over. 

* SPA Java Script app
  * Code runs in browser
  * Transfers raw data (JSON) over the network.
* PHP app
  * Code runs on backend
  * Transfers fully formatted HTML markup.

## SPA Java Script app flow 

```mermaid
sequenceDiagram
    autonumber
    actor User as User / Browser
    participant App as JS Frontend (Client)
    participant API as REST API (Server)

    User->>App: Navigates to page / clicks a link
    Note over App: JS initiates background request<br/>(e.g., using fetch() or Axios)
    App->>API: HTTP GET /api/products
    API-->>App: Returns raw JSON data
    Note over App: JS renders UI components<br/>and injects data into the DOM
    App-->>User: Visual page updates instantly (No Reload)
```

## PHP application flow

```mermaid
sequenceDiagram
    autonumber
    actor User as User / Browser
    participant PHP as PHP Server (Backend)
    participant API as REST API (Server)

    User->>PHP: Navigates to page / clicks a link (Full Page Request)
    Note over PHP: PHP intercepts request and<br/>calls API using cURL / Guzzle
    PHP->>API: HTTP GET /api/products
    API-->>PHP: Returns raw JSON data
    Note over PHP: PHP decodes JSON and<br/>bakes data into HTML template
    PHP-->>User: Returns fully rendered HTML page (Full Reload)
```  

# How to execute test

* Create podman network: `podman network create my-api-net`
* Run HTTP server: `MSYS_NO_PATHCONV=1 podman run -d --name my-php-server --network my-api-net -p 8000:8000 -v "$(pwd)":/usr/src/myapp -w /usr/src/myapp php:8.3-cli php -S 0.0.0.0:8000`
* Run test: `MSYS_NO_PATHCONV=1 podman run --rm --network my-api-net -v "$(pwd)":/usr/src/myapp -w /usr/src/myapp php:8.3-cli php test.php`
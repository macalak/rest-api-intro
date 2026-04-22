# Introduction to REST APIs

## Workshop Goals (tailored for SPSKNM)

- What an API is and why applications use APIs
- Basics of the HTTP protocol
- What REST means in practice
- How to consume a REST API using JavaScript (browser)
- How to consume a REST API using C# (.NET console app)

---

## 1. What is an API? 

> API is a contract that allows programs to talk to each other.

**API = Application Programming Interface**

* Application - Any software system or program (backend service, library, OS component)
* Programming - Intended to be used by software, not humans directly. Accessed through code.
* Interface - A defined contract that specifies:
  * what operations are available
  * how to call them
  * what inputs and outputs to expect

### Simple analogies:

Parking Pay Station

 ![PS](/docs/payment_station.png)

Ticket Vending Machine

 ![TVM](/docs/tvm.png)

Coffee Machine

 ![CM](/docs/coffee_machine.png)


---

## 2. How the Web Works – HTTP Basics

**HTTP = Hypertext Transfer Protocol**

* Hypertext - Originally referred to documents containing links (hyperlinks). Today means any interconnected resources (HTML pages, JSON, images, videos, APIs)
* Transfer - Data is transferred between systems. Uses a request–response exchange.
* Protocol - A formal set of rules. Defines how messages are structured, sent, and interpreted.

HTTP (Hypertext Transfer Protocol) is the application‑level protocol that defines how clients and servers exchange data on the web.

### Request / Response model
```
Client  --->  HTTP Request  --->  Server
Client  <---  HTTP Response <---  Server
```

1. Client sends an HTTP request
2. Server processes it
3. Server returns an HTTP response

Structure of HTTP Request:
```text
┌─────────────────────────────────────────────────────────────────────────┐
│ Request line   (GET /api/parking/sessions/123?status=active HTTP/1.1)   │
├─────────────────────────────────────────────────────────────────────────┤
│ Headers        ( Authorization: Bearer eyJhbGciOi...                    |
| (0 or more)        Content-Type: application/json)                      │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ Blank line                                                              │
├─────────────────────────────────────────────────────────────────────────┤
│ Message body   ({"vehiclePlate": "ZA-123AB", "zoneId": "ZONE-1"})       │
| (optional)                                                              |
└─────────────────────────────────────────────────────────────────────────┘


POST /api/parking/sessions?notify=true HTTP/1.1
Host: api.example.com
Authorization: Bearer eyJhbGciOi...
Content-Type: application/json
Accept: application/json

{
  "vehiclePlate": "ZA-123AB",
  "zoneId": "ZONE-1",
  "durationMinutes": 60
}
```

Structure of HTTP Response:
```text
┌───────────────────────────────────────────────────┐
│ Status line    ( HTTP/1.1 200 OK )                │
├───────────────────────────────────────────────────┤
│ Headers        ( Content-Type: application/json   |
| (0 or more)      Content-Length: 154 )            │
│                                                   │
├───────────────────────────────────────────────────┤
│ Blank line                                        │
├───────────────────────────────────────────────────┤
│ Message body   ({"id": 123,"status": "active"})   |
| (optional)                                        │
└───────────────────────────────────────────────────┘


HTTP/1.1 200  OK
Location: /api/parking/sessions/123
Content-Type: application/json
Content-Length: 154

{
  "id": 123,
  "status": "active"
}
```
> Try CURL with verbose option: `curl -v`

### HTTP Methods
| Method | Purpose |
|------|--------|
| GET | Read data |
| POST | Create data |
| PUT | Update data |
| DELETE | Delete data |

### HTTP Status Codes
| Code | Meaning |
|----|--------|
| 200 | OK |
| 201 | Created |
| 400 | Bad request |
| 404 | Not found |
| 500 | Server error |

### URI (Uniform Resource Identifier)

A string that identifies a resource. In HTTP-based systems, we most often use URLs, which are a subset of URIs.

URI syntax:
```text
scheme ":" ["//" authority] path ["?" query] ["#" fragment]
```
* scheme - defines how the resource is accessed (http, https, ftp, ...)
* authority - identifies where the resource is hosted (server address)
* path - identifies the resource within the server
* query - used for filtering, sorting, pagination
* fragment - identifies a sub‑resource or section

```text
Scheme = how, Authority = where, Path = what, Query = how exactly, Fragment = client‑side detail
```

Example:
```text
https://api.example.com:8080/v1/parking/sessions/123?status=active#details
```


| Term  | Meaning |
| ----  | ----    |
| URI   | General identifier (umbrella term) |
| URL   | URI that locates a resource (used with HTTP: https://api.example.com/v1/parking/sessions/123) |
| URN   | URI that names a resource without location (urn:isbn:978-0132350884) |


---

### 3. REST Principles (15 minutes)

> Why to not use HTTP to implement API?

**REST = REpresentational State Transfer**

* Representational - A resource (e.g. a user, order, parking occupancy) is not sent directly. Instead, the server sends a representation of that resource (e.g JSON, XML, HTML, etc.)
* State - The state refers to the current state of a resource or application
* Transfer - The representation of state is transferred between client and server

Key ideas:
- Everything is a **resource**
- Each resource has a **URL**
- Use HTTP verbs (GET, POST, DELETE, ...)
- Data is usually **JSON**

Example:
```
curl https://jsonplaceholder.typicode.com/posts/1
```

JSON response:
```json
{
  "userId": 1,
  "id": 1,
  "title": "sunt aut facere repellat provident occaecati excepturi optio reprehenderit",
  "body": "quia et suscipit\nsuscipit recusandae consequuntur expedita et cum\nreprehenderit molestiae ut ut quas totam\nnostrum rerum est autem sunt rem eveniet architecto"
}
```

---
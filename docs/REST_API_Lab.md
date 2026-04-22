# 4. Demo API

We use a public demo API:
```
https://jsonplaceholder.typicode.com
```

Example endpoints:
- `/posts`
- `/users`

Bruno collection: [Demo API](./DemoAPI.zip)

---

# 5. Hands‑On: JavaScript Web App 

## Project structure
```
web-app/
├── index.html
├── app.js
└── README.md
```

## index.html
```html
<!DOCTYPE html>
<html>
<head>
  <title>REST API Demo</title>
</head>
<body>
  <h1>Posts from REST API</h1>
  <button onclick="loadPosts()">Load posts</button>
  <ul id="posts"></ul>

  <script src="app.js"></script>
</body>
</html>
```

## app.js
```javascript
function loadPosts() {
  fetch("https://jsonplaceholder.typicode.com/posts")
    .then(response => response.json())
    .then(data => {
      const list = document.getElementById("posts");
      list.innerHTML = "";

      data.slice(0, 5).forEach(post => {
        const item = document.createElement("li");
        item.textContent = post.title;
        list.appendChild(item);
      });
    })
    .catch(error => console.error(error));
}
```

- `fetch()` sends HTTP GET
- JSON parsing
- Updating the HTML page dynamically


## Simple Architecture Overview

```
[ Browser / App ]
       |
     HTTP
       |
[ REST API Server ]
       |
    Database
```

Key idea:
> Frontend and backend are independent and communicate via HTTP.

---

# 6. Hands‑On: C# Console App

## Create or use existing (ApiClient) project

- cerate ApiClient directory into VS Code
- open new Terminal 
```bash
cd ApiClient
dotnet new console
```

## Program.cs
```csharp
using System.Net.Http;
using System.Text.Json;

HttpClient client = new HttpClient();

var response = await client.GetStringAsync(
    "https://jsonplaceholder.typicode.com/posts"
);

var posts = JsonSerializer.Deserialize<Post[]>(response);

foreach (var post in posts.Take(5))
{
    Console.WriteLine(post.Title);
}

record Post(int Id, string Title);
```

## Run:

```bash
dotnet run
```

- HTTP client
- async / await
- JSON deserialization

---

# 7. Hands‑On: C# Parking API Client App 

Tasks:
- Familiarize yourself with [API documentation](https://documentation.prod.parking.scheidt-bachmann.net/docs/tutorial/authentication-guide#accessing-the-swagger-documentation)
- Create new C# project or use existing [RestApiClient](../RestApiClient/)
- Find out how to get list of parking Facilities using API
- Find out how to get occupancy data for selected parking Facility using API (based on location, e.g. "location-id":"SB2186.7153.040239")
- You can use [Bruno Collection](./ParkingAPI.zip) 
- Get access_token with provided credentials and use it in your program
- Log retrieved data into console

# 8. Wrap‑Up

## Key takeaways
- REST APIs are everywhere
- HTTP is simple but powerful
- One API can be used by many programming languages
- Frontend and backend are separated

---

# Optional Homework
- Display users instead of posts
- Show more fields
- Call API by ID
- Add POST request example

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

**Simple analogies:**

- ATM machine
- Remote control

---

## 2. How the Web Works – HTTP Basics

> HTTP = Hypertext Transfer Protocol

### Request / Response model
```
Client  --->  HTTP Request  --->  Server
Client  <---  HTTP Response <---  Server
```

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

> Why to not use HTTP to implement API?
---

### 3. REST Principles (15 minutes)

Key ideas:
- Everything is a **resource**
- Each resource has a **URL**
- Use HTTP verbs
- Data is usually **JSON**

Example:
```
GET https://api.example.com/students
```

JSON response:
```json
{
  "id": 1,
  "name": "Anna",
  "age": 17
}
```

---

### 4. Demo API (5 minutes)

We use a public demo API:
```
https://jsonplaceholder.typicode.com
```

Example endpoints:
- `/posts`
- `/users`

---

## 3. Hands‑On: JavaScript Web App (35 minutes)

### Project structure
```
rest-js-demo/
├── index.html
├── app.js
└── README.md
```

### index.html
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

### app.js
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

Teaching points:
- `fetch()` sends HTTP GET
- JSON parsing
- Updating the HTML page dynamically

---

## 4. Hands‑On: C# Console App (35 minutes)

### Create project
- load backend directory into VS Code
- open new Terminal 
```bash
cd backend
dotnet new console
```

### Program.cs
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

Run:
```bash
dotnet run
```

Teaching points:
- HTTP client
- async / await
- JSON deserialization

---

## 5. Simple Architecture Overview (10 minutes)

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

## 6. Wrap‑Up (10 minutes)

### Key takeaways
- REST APIs are everywhere
- HTTP is simple but powerful
- One API can be used by many programming languages
- Frontend and backend are separated

---

## Optional Homework
- Display users instead of posts
- Show more fields
- Call API by ID
- Add POST request example

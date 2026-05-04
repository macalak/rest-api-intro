/*
Simple script to load posts from JSONPlaceholder API and display the first 5 post titles in a list. 
What is used to call REST API and handle the response:
  - fetch() is a built-in JavaScript function that asks a server for data using HTTP
  - response.json() converts response data (in JSON) into a JavaScript object / array
  - .then() runs after data arrives, allowing us to work with the data
  - .catch() handles any errors that occur during the fetch process
What is used to manipulate the DOM:
    - document.getElementById() finds an element in the HTML by its ID
    - document.createElement() creates a new HTML element (like <li>)
    - element.textContent sets the text inside an HTML element
    - parent.appendChild() adds a new child element to a parent element in the DOM   
*/
function loadPosts() {
  fetch("https://jsonplaceholder.typicode.com/posts")
    .then(response => response.json())
    .then(data => {
      const list = document.getElementById("posts");
      list.innerHTML = "";

      data.slice(0, 10).forEach(post => {
        const item = document.createElement("li");
        item.textContent = `${post.id}: ${post.title}`;
        list.appendChild(item);
      });
    })
    .catch(error => console.error(error));
}
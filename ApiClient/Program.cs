using System.Net.Http;
using System.Text.Json;

HttpClient client = new HttpClient();


var options = new JsonSerializerOptions
{
    PropertyNameCaseInsensitive = true
};

var response = await client.GetStringAsync(
    "https://jsonplaceholder.typicode.com/posts"
);

var posts = JsonSerializer.Deserialize<Post[]>(response, options) ?? Array.Empty<Post>();

foreach (var post in posts.Take(5))
{
    Console.WriteLine(post.Id + ": " + post.Title);
}

record Post(int Id, string Title);
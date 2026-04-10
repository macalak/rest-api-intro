using System.Net.Http;
using System.Net.Http.Headers;
using System.Text.Json;

const string ApiUrl = "<put base url here>/occupancy/facilities";
const string ApiToken = "<put access token here";

using var client = new HttpClient();

client.DefaultRequestHeaders.Authorization =
    new AuthenticationHeaderValue("Bearer", ApiToken);

client.DefaultRequestHeaders.Accept.Add(
    new MediaTypeWithQualityHeaderValue("application/json")
);

Console.WriteLine("Calling REST API...");

HttpResponseMessage response = await client.GetAsync(ApiUrl);
Console.WriteLine($"Status: {response.StatusCode}");

response.EnsureSuccessStatusCode();

string json = await response.Content.ReadAsStringAsync();

Console.WriteLine("Raw JSON response:");
Console.WriteLine(json);
Console.WriteLine("-----------------------------");


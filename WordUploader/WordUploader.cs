using System;
using System.IO;
using System.Text.Json;
using System.Threading.Tasks;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using API.Models;
using API.Data;

public class WordUploader
{
    public static async Task UploadWordsAsync()
    {
        // Load the connection string from a config file or directly provide it
        var connectionString = "YourDatabaseConnectionString"; // Replace with your actual connection string or load it from a config

        var categories = new[]
        {
            "Actions", "Animals", "Emotions", "Nature", "Objects", "People", "Places", "All"
        };

        var filePaths = new[]
        {
            "Words List/Categories/Actions/WordsListActions.txt",
            "Words List/Categories/All/WordsList.txt",
            "Words List/Categories/Animals/WordsListAnimals.txt",
            "Words List/Categories/Emotions/WordsListEmotions.txt",
            "Words List/Categories/Nature/WordsListNature.txt",
            "Words List/Categories/Objects/WordsListObjects.txt",
            "Words List/Categories/People/WordsListPeople.txt",
            "Words List/Categories/Places/WordsListPlaces.txt"
        };

        // Set up DbContext with the connection string
        var optionsBuilder = new DbContextOptionsBuilder<AppDBContext>();
        optionsBuilder.UseNpgsql(connectionString); // If you're using PostgreSQL

        using (var context = new AppDBContext(optionsBuilder.Options))
        {
            for (int i = 0; i < categories.Length; i++)
            {
                var categoryName = categories[i];
                var filePath = filePaths[i];

                // Read the words from the text file
                var words = File.ReadAllLines(filePath);

                foreach (var word in words)
                {
                    var wordEntry = new WordleWords
                    {
                        word = word,
                        category_id = i + 1, // Assuming category_id starts from 1
                        category = new Category { category = categoryName }
                    };

                    // Add the word to the database
                    context.WordleWords.Add(wordEntry);
                }
            }

            // Save all changes to the database
            await context.SaveChangesAsync();
        }

        Console.WriteLine("Words uploaded successfully!");
    }
}

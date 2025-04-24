using System;
using System.Collections.Generic;
using System.IO;

namespace API
{
    class WordReader
    {
        public static List<string> GetAllWords(string basePath)
        {
            List<string> allWords = new List<string>();

            // Add words from the general WordsList.txt file  
            string generalFilePath = Path.Combine(basePath, "WordsList.txt");
            if (File.Exists(generalFilePath))
            {
                allWords.AddRange(ReadWordsFromFile(generalFilePath));
            }

            // Add words from category-specific files in subfolders  
            string categoriesFolderPath = Path.Combine(basePath, "Categories");
            if (Directory.Exists(categoriesFolderPath))
            {
                var categoryDirs = Directory.GetDirectories(categoriesFolderPath); // Get subfolders (e.g., Actions, Animals)  
                foreach (var categoryDir in categoryDirs)
                {
                    var categoryFile = Path.Combine(categoryDir, "WordsList.txt"); // Look for the WordsList.txt in each category folder  
                    if (File.Exists(categoryFile))
                    {
                        allWords.AddRange(ReadWordsFromFile(categoryFile));
                    }
                }
            }

            return allWords;
        }

        private static List<string> ReadWordsFromFile(string filePath)
        {
            List<string> words = new List<string>();
            try
            {
                var fileWords = File.ReadAllLines(filePath);
                words.AddRange(fileWords);
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error reading file {filePath}: {ex.Message}");
            }
            return words;
        }
    }
    //can not have 2 Main cant start swagger if we have 2
    //class WordImporterProgram
    //{
    //    static void Main(string[] args)
    //    {
    //        string baseDirectory = @"C:\Path\To\Words List"; // Adjust to your actual path  
    //        List<string> allWords = WordReader.GetAllWords(baseDirectory);

    //        // Print all the words (or process them as needed)  
    //        foreach (var word in allWords)
    //        {
    //            Console.WriteLine(word);
    //        }
    //    }
    //}
}
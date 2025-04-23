namespace API.Models
{
    public class WordleWords : Common
    {
        string word {  get; set; }
        int category_id { get; set; }
        Category category { get; set; }

    }
}

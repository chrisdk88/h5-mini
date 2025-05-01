namespace API.Models
{
    public class WordleWords : Common
    {
        public string word {  get; set; }
        public int category_id { get; set; }
        Category category { get; set; }
    }

    public class PostWord
    {
        public string word { get; set; }
        public int category_id { get; set; }
    }
}

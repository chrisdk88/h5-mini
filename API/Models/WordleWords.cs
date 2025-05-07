namespace API.Models
{
    public class WordleWords : Common
    {
        public string word { get; set; }

        public int category_id { get; set; }

        [ForeignKey("category_id")]
        public Category category { get; set; }
    }

    public static class WordTracker
    {
        public static List<int> LastUsedWordIds { get; set; } = new();
    }

    public class PostWord
    {
        public string word { get; set; }
        public int category_id { get; set; }
    }
}

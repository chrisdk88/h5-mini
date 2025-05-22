using API.Models.Common;

namespace API.Models.Wordle
{
    public class WordleWords : CommonBase
    {
        public string word { get; set; }

        public int category_id { get; set; }

        [ForeignKey("category_id")]
        public Category Category { get; set; }
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

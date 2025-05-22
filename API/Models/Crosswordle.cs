namespace API.Models
{
    public class Crosswordle : Common
    {
        public int wordCount { get; set; }
        public WordleWords word { get; set; }
    }

    public class CrosswordleWord : Common
    {
        public string word { get; set; }

        public int crosswordle_id { get; set; }
        public Crosswordle Crosswordle { get; set; }

        // Positioning info
        public int startRow { get; set; }
        public int startColumn { get; set; }
        public Direction Direction { get; set; }

        // Optional: indicate if it's the "anchor" word
        public bool isAnchor { get; set; }
    }

    public enum Direction
    {
        horizontal,
        vertical
    }

}

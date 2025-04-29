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

        public int CrosswordleId { get; set; }
        public Crosswordle Crosswordle { get; set; }

        // Positioning info
        public int StartRow { get; set; }
        public int StartColumn { get; set; }
        public Direction Direction { get; set; }

        // Optional: indicate if it's the "anchor" word
        public bool IsAnchor { get; set; }
    }

    public enum Direction
    {
        Horizontal,
        Vertical
    }

}

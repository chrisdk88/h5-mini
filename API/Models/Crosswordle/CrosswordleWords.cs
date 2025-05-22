using API.Models.Common;

namespace API.Models.Crosswordle
{
    public class CrosswordleWords : CommonBase
    {
        public string word { get; set; }
        public int wordCount { get; set; }
        public int category_id { get; set; }

        [ForeignKey("category_id")]
        public Category Category { get; set; }
    }

    public class CrosswordleWord : CommonBase
    {

        // Insert needed properties here

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

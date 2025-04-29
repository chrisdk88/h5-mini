namespace API.Models
{
    public class WordleSession : Common
    {
        public int player1_id {  get; set; }
        public int player2_id { get; set;}
        public int word_id { get; set; }
        public int winner_id { get; set; }
        public DateOnly DateOnly { get; set; }

    }

    public class postWordSession
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public int word_id { get; set; }
        public int winner_id { get; set; }
        public DateOnly DateOnly { get; set; }
    }
}

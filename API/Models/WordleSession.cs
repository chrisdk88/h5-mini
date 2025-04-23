namespace API.Models
{
    public class WordleSession : Common
    {
        int player1_id {  get; set; }
        public int player2_id { get; set;}
        int word_id { get; set; }
        int winner_id { get; set; }
        DateOnly DateOnly { get; set; }

    }
}

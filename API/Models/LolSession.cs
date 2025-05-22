namespace API.Models
{
    public class LolSession : Common
    {
        public User User { get; set; }
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public LolChampions Champions { get; set; }
        public int hero_id { get; set; }
        public int winner_id { get; set; }
    }
    public class PostLolSession
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public int hero_id { get; set; }
        public int winner_id { get; set; }
    }
}

namespace API.Models
{
    public class LolSession : Common
    {
        public User User { get; set; }
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public LolHeroes heroes { get; set; }
        public int hero_id { get; set; }
        public int winner_id { get; set; }
        public DateOnly DateOnly { get; set; }
    }
}

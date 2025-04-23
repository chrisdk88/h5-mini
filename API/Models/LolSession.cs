namespace API.Models
{
    public class LolSession : Common
    {
        User User { get; set; }
        int player1_id { get; set; }
        public int player2_id { get; set; }
        LolHeroes heroes { get; set; }
        int hero_id { get; set; }
        int winner_id { get; set; }
        DateOnly DateOnly { get; set; }
    }
}

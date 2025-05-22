using API.Models.Common;

namespace API.Models.Loldle
{
    public class LoldleSession : CommonBase
    {
        public User User { get; set; }
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public LoldleChampions Champions { get; set; }
        public int champion_id { get; set; }
        public int winner_id { get; set; }
    }
    public class PostLolSession
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public int champion_id { get; set; }
        public int winner_id { get; set; }
    }
}

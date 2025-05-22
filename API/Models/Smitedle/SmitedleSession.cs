using API.Models.Common;

namespace API.Models.Smitedle
{
    public class SmitedleSession : CommonBase
    {
        public User User { get; set; }
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public SmitedleGods Gods { get; set; }
        public int god_id { get; set; }
        public int winner_id { get; set; }

    }

    public class PostSmitedleSession
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public int god_id { get; set; }
        public int winner_id { get; set; }
    }
}

using API.Models.Common;

namespace API.Models.Helldivers2dle
{
    public class HD2dleSession : CommonBase
    {
        public User User { get; set; }
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public HD2dleStratagems Stratagem { get; set; }
        public int stratagem_id { get; set; }
        public int winner_id { get; set; }
    }
    public class PostHelldivers2Session
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public int stratagem_id { get; set; }
        public int winner_id { get; set; }
    }
}

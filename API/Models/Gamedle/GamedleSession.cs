using API.Models.Common;

namespace API.Models.Gamedle
{
    public class GamedleSession : CommonBase
    {
        public User User { get; set; }
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public GamedleGames Game { get; set; }
        public int game_id { get; set; }
        public int winner_id { get; set; }
    }
    public class PostGamedleSession
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public int game_id { get; set; }
        public int winner_id { get; set; }
    }
}

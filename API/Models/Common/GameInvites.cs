namespace API.Models.Common
{
    public class GameInvites : CommonBase
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public string status { get; set; }
        public string game_type { get; set; }
        public int game_session_id { get; set; }
    }
    public class PostGameInvite
    {
        public int player1_id { get; set; }
        public int player2_id { get; set; }
        public string status { get; set; }
        public string game_type { get; set; }
        public int game_session_id { get; set; }
    }
}

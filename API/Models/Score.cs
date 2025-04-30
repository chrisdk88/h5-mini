namespace API.Models
{
    public class Score : Common
    {
        public User user {  get; set; }
        public int user_id { get; set; }
        public string game_type { get; set; }
        public int points { get; set; }
        public bool is_multiplayer { get; set; }
        public int? game_session_id { get; set; } //null if single player
    }

    public class postScore 
    {
        public int UserId { get; set; }
        public int GamemodeId { get; set; }
        public int Score { get; set; }
        public bool IsMultiplayer { get; set; }
        public int? GameSessionId { get; set; } //null if single player
    }
}

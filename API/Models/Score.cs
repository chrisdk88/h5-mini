namespace API.Models
{
    public class Score : Common
    {
        public User user {  get; set; }
        public int user_id { get; set; }
        public string game_type { get; set; }
        public string game_mode { get; set; }
        public int points { get; set; }
        public bool is_multiplayer { get; set; }
        public int? game_session_id { get; set; } //null if single player
    }

    public class postScore 
    {
        public int User_id { get; set; }
        public string game_type { get; set; }
        public string game_mode { get; set; }
        public int points { get; set; }
        public bool is_multiplayer { get; set; }
        public int? game_session_id { get; set; } //null if single player
    }
}

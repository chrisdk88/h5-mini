namespace API.Models
{
    public class Score : Common
    {
        public string word { get; set; }
        public int user_id { get; set; }

        [ForeignKey("user_id")]
        public User user { get; set; }
        public string game_type { get; set; }
        public string game_mode { get; set; }
        public int points { get; set; }
        public TimeOnly game_time { get; set; }
        public int attempts {  get; set; }
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
        public GameTimeDto game_time { get; set; }
        public int attempts { get; set; }
        public string word { get; set; }

    }
    public class GameTimeDto
    {
        public int hour { get; set; }
        public int minute { get; set; }
        public int second { get; set; }
    }
}

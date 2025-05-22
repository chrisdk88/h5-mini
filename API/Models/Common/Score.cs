namespace API.Models.Common
{
    public class Score : CommonBase
    {
        public string word { get; set; }
        public int user_id { get; set; }

        [ForeignKey("user_id")]
        public User user { get; set; }
        public string game_type { get; set; }
        public string game_mode { get; set; }
        public int points { get; set; }
        public TimeOnly game_time { get; set; }
        public int attempts { get; set; }
        public bool is_multiplayer { get; set; }
        public int? game_session_id { get; set; } //null if single player
    }

    public class PostScore
    {
        public int user_id { get; set; }
        public string game_type { get; set; }
        public string game_mode { get; set; }
        public int points { get; set; }
        public bool is_multiplayer { get; set; }
        public int? game_session_id { get; set; } //null if single player
        public GameTimeDto GameTime { get; set; }
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
